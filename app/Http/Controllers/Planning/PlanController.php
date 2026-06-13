<?php
namespace App\Http\Controllers\Planning;
use App\Http\Controllers\Controller;
use App\Models\{Plan,Department,User,ActivityLog};
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Plan::with(['creator','assignee','department']);
        if (!$user->is_management) { $query->where('department_id', $user->department_id); }
        if ($request->status)     $query->where('status', $request->status);
        if ($request->priority)   $query->where('priority', $request->priority);
        if ($request->department) $query->where('department_id', $request->department);
        if ($request->search)     $query->where('title','like','%'.$request->search.'%');
        $plans       = $query->latest()->paginate(15);
        $departments = Department::active()->get();
        return view('planning.index', compact('plans','departments'));
    }

    public function create()
    {
        $user        = auth()->user();
        $departments = $user->is_management ? Department::active()->get() : collect([$user->department]);
        $employees   = User::active()->when(!$user->is_management, fn($q)=>$q->where('department_id',$user->department_id))->get();
        return view('planning.create', compact('departments','employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'department_id'=> 'required|exists:departments,id',
            'assigned_to'  => 'nullable|exists:users,id',
            'priority'     => 'required|in:low,medium,high,critical',
            'start_date'   => 'nullable|date',
            'due_date'     => 'nullable|date|after_or_equal:start_date',
            'attachments.*'=> 'nullable|file|max:10240',
        ]);
        $validated['created_by'] = auth()->id();
        $plan = Plan::create($validated);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/plans','public');
                $plan->attachments()->create(['uploaded_by'=>auth()->id(),'original_name'=>$file->getClientOriginalName(),'file_path'=>$path,'file_type'=>$file->getMimeType(),'file_size'=>$file->getSize()]);
            }
        }
        $this->log('create',$plan,'Created plan: '.$plan->title);
        return redirect()->route('plans.show',$plan)->with('success','Plan created successfully!');
    }

    public function show(Plan $plan)
    {
        $plan->load(['creator','assignee','approver','department','tasks','attachments.uploader']);
        return view('planning.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        $departments = auth()->user()->is_management ? Department::active()->get() : collect([auth()->user()->department]);
        $employees   = User::active()->where('department_id',$plan->department_id)->get();
        return view('planning.edit', compact('plan','departments','employees'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'assigned_to'=> 'nullable|exists:users,id',
            'priority'   => 'required|in:low,medium,high,critical',
            'status'     => 'required|in:pending,in_progress,completed,delayed,cancelled',
            'progress'   => 'required|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'due_date'   => 'nullable|date',
        ]);
        if ($validated['status']==='completed' && !$plan->completed_at) { $validated['completed_at'] = now()->toDateString(); }
        $plan->update($validated);
        $this->log('update',$plan,'Updated plan: '.$plan->title);
        return redirect()->route('plans.show',$plan)->with('success','Plan updated!');
    }

    public function destroy(Plan $plan) { $plan->delete(); return redirect()->route('plans.index')->with('success','Plan deleted.'); }

    public function approve(Request $request, Plan $plan)
    {
        $plan->update(['approval_status'=>'approved','approved_by'=>auth()->id(),'management_comment'=>$request->comment]);
        return back()->with('success','Plan approved.');
    }

    public function reject(Request $request, Plan $plan)
    {
        $request->validate(['comment'=>'required|string']);
        $plan->update(['approval_status'=>'rejected','management_comment'=>$request->comment,'status'=>'cancelled']);
        return back()->with('success','Plan rejected.');
    }

    public function updateProgress(Request $request, Plan $plan)
    {
        $request->validate(['progress'=>'required|integer|min:0|max:100']);
        $status = $request->progress==100 ? 'completed' : ($request->progress>0 ? 'in_progress' : 'pending');
        $plan->update(['progress'=>$request->progress,'status'=>$status]);
        return response()->json(['success'=>true,'progress'=>$request->progress,'status'=>$status]);
    }

    private function log(string $action, $model, string $desc)
    {
        ActivityLog::create(['user_id'=>auth()->id(),'action'=>$action,'model_type'=>get_class($model),'model_id'=>$model->id,'description'=>$desc,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent()]);
    }
}

<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\{DevelopmentRequest,Department};
use Illuminate\Http\Request;
class DevRequestController extends Controller {
    public function index() {
        $user = auth()->user();
        $requests = DevelopmentRequest::with(['department','requester'])
            ->when(!$user->is_management, fn($q)=>$q->where('requested_by',$user->id))
            ->latest()->paginate(15);
        return view('dev-requests.index', compact('requests'));
    }
    public function create() { return view('dev-requests.create', ['departments'=>Department::active()->get()]); }
    public function store(Request $request) {
        $v = $request->validate(['department_id'=>'required|exists:departments,id','title'=>'required|string|max:255','description'=>'required|string','type'=>'required|in:software,equipment,process,resource,training,other','priority'=>'required|in:low,medium,high,critical','estimated_budget'=>'nullable|numeric','expected_date'=>'nullable|date']);
        $v['requested_by'] = auth()->id();
        DevelopmentRequest::create($v);
        return redirect()->route('dev-requests.index')->with('success','Request submitted!');
    }
    public function show(DevelopmentRequest $devRequest) { return view('dev-requests.show', ['request'=>$devRequest->load(['department','requester','reviewer'])]); }
    public function destroy(DevelopmentRequest $devRequest) { $devRequest->delete(); return redirect()->route('dev-requests.index')->with('success','Request deleted.'); }
}

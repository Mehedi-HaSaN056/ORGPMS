<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\{User,Department,PerformanceReview,Achievement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department')->whereDoesntHave('roles',fn($q)=>$q->whereIn('name',['super_admin']));
        if (!auth()->user()->is_management) { $query->where('department_id',auth()->user()->department_id); }
        if ($request->department) $query->where('department_id',$request->department);
        if ($request->status)     $query->where('status',$request->status);
        if ($request->search)     $query->where(fn($q)=>$q->where('name','like','%'.$request->search.'%')->orWhere('email','like','%'.$request->search.'%')->orWhere('employee_id','like','%'.$request->search.'%'));
        $employees   = $query->paginate(20);
        $departments = Department::active()->get();
        return view('employees.index', compact('employees','departments'));
    }

    public function show(User $employee)
    {
        $employee->load(['department','kpis','achievements','performanceReviews.reviewer']);
        $stats = [
            'taskCompletion' => $employee->task_completion_rate,
            'avgKpi'         => round($employee->kpis->avg('score')??0,2),
            'totalTasks'     => $employee->tasks()->count(),
            'completedTasks' => $employee->tasks()->where('status','completed')->count(),
            'overdueTasks'   => $employee->tasks()->overdue()->count(),
            'loginCount'     => $employee->loginLogs()->where('is_successful',true)->count(),
        ];
        return view('employees.show', compact('employee','stats'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        $roles = \Spatie\Permission\Models\Role::all();
        return view('employees.create', compact('departments','roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'employee_id'  => 'nullable|string|unique:users',
            'phone'        => 'nullable|string|max:20',
            'designation'  => 'nullable|string|max:255',
            'department_id'=> 'required|exists:departments,id',
            'gender'       => 'nullable|in:male,female,other',
            'date_of_birth'=> 'nullable|date',
            'joining_date' => 'nullable|date',
            'address'      => 'nullable|string',
            'password'     => 'required|min:8|confirmed',
            'role'         => 'required|exists:roles,name',
            'avatar'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars','public');
        }
        $validated['password'] = Hash::make($validated['password']);
        $role = $validated['role'];
        unset($validated['role']);

        $employee = User::create($validated);
        $employee->assignRole($role);

        return redirect()->route('employees.show',$employee)->with('success','Employee created successfully!');
    }

    public function edit(User $employee)
    {
        $departments = Department::active()->get();
        $roles = \Spatie\Permission\Models\Role::all();
        return view('employees.edit', compact('employee','departments','roles'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,'.$employee->id,
            'phone'        => 'nullable|string|max:20',
            'designation'  => 'nullable|string|max:255',
            'department_id'=> 'required|exists:departments,id',
            'gender'       => 'nullable|in:male,female,other',
            'date_of_birth'=> 'nullable|date',
            'joining_date' => 'nullable|date',
            'address'      => 'nullable|string',
            'status'       => 'required|in:active,inactive,suspended',
            'role'         => 'nullable|exists:roles,name',
            'avatar'       => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('avatar')) {
            if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
            $validated['avatar'] = $request->file('avatar')->store('avatars','public');
        }
        if (!empty($validated['role'])) {
            $employee->syncRoles([$validated['role']]);
        }
        unset($validated['role']);
        $employee->update($validated);
        return redirect()->route('employees.show',$employee)->with('success','Employee updated.');
    }

    public function addAchievement(Request $request, User $employee)
    {
        $request->validate(['title'=>'required|string','description'=>'nullable|string','type'=>'required|in:task,kpi,attendance,communication,special','badge_icon'=>'nullable|string','badge_color'=>'nullable|string']);
        $employee->achievements()->create($request->only(['title','description','type','badge_icon','badge_color']));
        return back()->with('success','Achievement awarded!');
    }

    public function leaderboard()
    {
        $employees = User::active()->with(['tasks','kpis','achievements'])->get()
            ->map(fn($u) => [
                'user'        => $u,
                'score'       => $u->overall_performance_score,
                'badges'      => $u->achievements->count(),
                'completedTasks'=> $u->tasks->where('status','completed')->count(),
                'kpiAvg'      => round($u->kpis->avg('score')??0,2),
            ])
            ->sortByDesc('score')
            ->values();
        return view('employees.leaderboard', compact('employees'));
    }
}

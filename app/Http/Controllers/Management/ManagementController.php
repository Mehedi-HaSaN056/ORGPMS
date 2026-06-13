<?php
namespace App\Http\Controllers\Management;
use App\Http\Controllers\Controller;
use App\Models\{Plan,Task,Kpi,User,Department,LoginLog,ActivityLog,DevelopmentRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ManagementController extends Controller {
    public function dashboard() {
        $data = [
            'orgStats' => ['users'=>User::active()->count(),'plans'=>Plan::count(),'tasksCompleted'=>Task::where('status','completed')->count(),'avgKpi'=>round(Kpi::avg('score')??0,2)],
            'deptPerformance' => Department::active()->with(['tasks','kpis'])->get()->map(fn($d)=>['name'=>$d->name,'color'=>$d->color,'completion'=>$d->completion_rate,'kpi'=>$d->average_kpi_score]),
            'pendingApprovals' => Plan::where('approval_status','pending')->with(['creator','department'])->latest()->take(10)->get(),
            'overdueItems' => Task::overdue()->with(['assignee','department'])->take(10)->get(),
        ];
        return view('management.dashboard', compact('data'));
    }
    public function overview() { return view('management.overview'); }
    public function approvals() { $plans = Plan::where('approval_status','pending')->with(['creator','assignee','department'])->latest()->paginate(20); return view('management.approvals', compact('plans')); }
    public function devRequests() { $requests = DevelopmentRequest::with(['department','requester'])->latest()->paginate(20); return view('management.dev-requests', compact('requests')); }
    public function updateDevRequest(Request $request, DevelopmentRequest $req) {
        $v = $request->validate(['status'=>'required|in:under_review,approved,rejected,implemented','management_response'=>'nullable|string','budget_comment'=>'nullable|string']);
        $v['reviewed_by'] = auth()->id();
        $req->update($v);
        return back()->with('success','Request updated.');
    }
    public function loginLogs(Request $request) { $logs = LoginLog::with('user')->latest('logged_in_at')->paginate(30); return view('admin.login-logs', compact('logs')); }
    public function activityLogs() { $logs = ActivityLog::with('user')->latest()->paginate(30); return view('admin.activity-logs', compact('logs')); }
}

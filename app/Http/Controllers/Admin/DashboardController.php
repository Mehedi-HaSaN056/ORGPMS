<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{User,Department,Plan,Task,Kpi,Message,DevelopmentRequest,LoginLog,ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers'       => User::active()->count(),
            'totalDepartments' => Department::active()->count(),
            'totalPlans'       => Plan::count(),
            'totalTasks'       => Task::count(),
            'pendingApprovals' => Plan::where('approval_status','pending')->count(),
            'overdueTasks'     => Task::overdue()->count(),
            'unreadMessages'   => Message::where('receiver_id',auth()->id())->unread()->count(),
            'pendingRequests'  => DevelopmentRequest::where('status','pending')->count(),
            'departmentStats'  => Department::active()->with(['tasks','kpis','users'])->get()->map(function($dept) {
                return ['name'=>$dept->name,'color'=>$dept->color,'userCount'=>$dept->users->count(),'completionRate'=>$dept->completion_rate,'kpiScore'=>$dept->average_kpi_score];
            }),
            'monthlyTaskStats' => Task::select(DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(*) as total'),DB::raw("SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed"))->whereYear('created_at',now()->year)->groupBy('month')->orderBy('month')->get(),
            'topPerformers'    => User::active()->with(['kpis','tasks'])->get()->sortByDesc(fn($u)=>$u->overall_performance_score)->take(5),
            'recentActivities' => ActivityLog::with('user')->latest()->take(10)->get(),
            'recentLogins'     => LoginLog::with('user')->where('is_successful',true)->latest('logged_in_at')->take(10)->get(),
        ];
        return view('admin.dashboard', compact('data'));
    }

    public function analytics(Request $request)
    {
        $year=$request->get('year',now()->year); $month=$request->get('month',now()->month);
        return response()->json([
            'kpiTrends' => Kpi::select(DB::raw('month'),DB::raw('AVG(score) as avg_score'))->where('year',$year)->groupBy('month')->orderBy('month')->get(),
            'departmentComparison' => Department::active()->with(['kpis'=>fn($q)=>$q->where('year',$year)->where('month',$month)])->get()->map(fn($d)=>['name'=>$d->name,'color'=>$d->color,'score'=>round($d->kpis->avg('score')??0,2)]),
            'taskStatusBreakdown'  => Task::select('status',DB::raw('COUNT(*) as count'))->groupBy('status')->get(),
        ]);
    }
}

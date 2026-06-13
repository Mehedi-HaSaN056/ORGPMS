<?php
namespace App\Http\Controllers\Department;
use App\Http\Controllers\Controller;
use App\Models\{Plan,Task,Kpi,Message,User};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $deptId = $user->department_id;
        $data = [
            'myPendingTasks'   => Task::where('assigned_to',$user->id)->where('status','pending')->count(),
            'myCompletedTasks' => Task::where('assigned_to',$user->id)->where('status','completed')->count(),
            'myOverdueTasks'   => Task::where('assigned_to',$user->id)->overdue()->count(),
            'myKpiScore'       => $user->getMonthlyKpiScore(now()->year,now()->month),
            'taskCompletionRate'=> $user->task_completion_rate,
            'unreadMessages'   => Message::where('receiver_id',$user->id)->unread()->count(),
            'upcomingDeadlines'=> Task::where('department_id',$deptId)->where('assigned_to',$user->id)->where('due_date','>=',now())->where('due_date','<=',now()->addDays(7))->whereNotIn('status',['completed','cancelled'])->orderBy('due_date')->with('plan')->take(5)->get(),
            'recentPlans'      => Plan::where('department_id',$deptId)->latest()->with(['creator','assignee'])->take(5)->get(),
            'monthlyProgress'  => Task::where('department_id',$deptId)->select(DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(*) as total'),DB::raw("SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed"))->whereYear('created_at',now()->year)->groupBy('month')->orderBy('month')->get(),
        ];
        return view('dashboard.department', compact('data','user'));
    }
}

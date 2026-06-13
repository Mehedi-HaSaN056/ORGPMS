<?php
namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use App\Models\{Department,User,Plan,Task,Kpi,PerformanceReview};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function kpiReport(Request $request)
    {
        $request->validate(['year'=>'required|integer','month'=>'nullable|integer']);
        $year  = $request->year;
        $month = $request->month;

        $query = Kpi::with(['user','department'])->where('year',$year);
        if ($month) { $query->where('month',$month); }
        if (!auth()->user()->is_management) { $query->where('department_id',auth()->user()->department_id); }

        $kpis     = $query->get();
        $deptData = $kpis->groupBy('department_id')->map(fn($g) => ['dept'=>$g->first()->department?->name,'avg'=>round($g->avg('score'),2),'count'=>$g->count()]);

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.kpi-pdf', compact('kpis','deptData','year','month'));
            return $pdf->download("kpi-report-{$year}.pdf");
        }
        if ($request->format === 'excel') {
            return Excel::download(new \App\Exports\KpiReportExport($year,$month), "kpi-report-{$year}.xlsx");
        }
        return view('reports.kpi', compact('kpis','deptData','year','month'));
    }

    public function performanceReport(Request $request)
    {
        $year  = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $query = PerformanceReview::with(['user','department','reviewer'])->where('year',$year)->where('month',$month);
        if (!auth()->user()->is_management) { $query->where('department_id',auth()->user()->department_id); }

        $reviews = $query->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.performance-pdf', compact('reviews','year','month'));
            return $pdf->download("performance-report-{$year}-{$month}.pdf");
        }
        return view('reports.performance', compact('reviews','year','month'));
    }

    public function departmentReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        $depts = Department::active()->with([
            'tasks'  => fn($q) => $q->whereYear('created_at',$year),
            'plans'  => fn($q) => $q->whereYear('created_at',$year),
            'kpis'   => fn($q) => $q->where('year',$year),
            'users',
        ])->get();

        $report = $depts->map(fn($d) => [
            'name'          => $d->name,
            'color'         => $d->color,
            'employees'     => $d->users->count(),
            'total_tasks'   => $d->tasks->count(),
            'completed_tasks'=> $d->tasks->where('status','completed')->count(),
            'total_plans'   => $d->plans->count(),
            'approved_plans'=> $d->plans->where('approval_status','approved')->count(),
            'avg_kpi'       => round($d->kpis->avg('score')??0,2),
            'completion_rate'=> $d->tasks->count() > 0 ? round($d->tasks->where('status','completed')->count()/$d->tasks->count()*100,2) : 0,
        ]);

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.department-pdf', compact('report','year'));
            return $pdf->download("department-report-{$year}.pdf");
        }
        return view('reports.department', compact('report','year'));
    }

    public function employeeReport(Request $request, User $user)
    {
        $year  = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $data  = [
            'user'        => $user->load('department'),
            'kpis'        => Kpi::where('user_id',$user->id)->where('year',$year)->get(),
            'tasks'       => Task::where('assigned_to',$user->id)->whereYear('created_at',$year)->get(),
            'reviews'     => PerformanceReview::where('user_id',$user->id)->where('year',$year)->get(),
            'workLogs'    => \App\Models\WorkLog::where('user_id',$user->id)->whereYear('log_date',$year)->get(),
            'achievements'=> $user->achievements,
        ];
        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.employee-pdf', compact('data','year','month'));
            return $pdf->download("employee-report-{$user->name}-{$year}.pdf");
        }
        return view('reports.employee', compact('data','year','month'));
    }
}

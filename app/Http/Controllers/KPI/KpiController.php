<?php
namespace App\Http\Controllers\KPI;
use App\Http\Controllers\Controller;
use App\Models\{Kpi,Department,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Kpi::with(['user','department','evaluator']);
        if (!$user->is_management) { $query->where('department_id',$user->department_id); }
        if ($request->user_id)    $query->where('user_id',$request->user_id);
        if ($request->month)      $query->where('month',$request->month);
        if ($request->year)       $query->where('year',$request->year);
        if ($request->department) $query->where('department_id',$request->department);
        $kpis        = $query->latest()->paginate(20);
        $departments = Department::active()->get();
        $employees   = User::active()->when(!$user->is_management,fn($q)=>$q->where('department_id',$user->department_id))->get();
        return view('kpi.index', compact('kpis','departments','employees'));
    }

    public function create()
    {
        $user        = auth()->user();
        $employees   = User::active()->when(!$user->is_management,fn($q)=>$q->where('department_id',$user->department_id))->get();
        $departments = $user->is_management ? Department::active()->get() : collect([$user->department]);
        return view('kpi.create', compact('employees','departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'department_id'=> 'required|exists:departments,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'target'       => 'required|numeric|min:0',
            'achieved'     => 'required|numeric|min:0',
            'metric_unit'  => 'required|string|max:20',
            'year'         => 'required|integer|min:2020|max:2035',
            'month'        => 'required|integer|min:1|max:12',
            'period'       => 'required|in:monthly,quarterly,yearly',
            'remarks'      => 'nullable|string',
        ]);
        $validated['evaluated_by'] = auth()->id();
        $kpi = Kpi::create($validated);
        return redirect()->route('kpi.show',$kpi)->with('success','KPI created successfully!');
    }

    public function show(Kpi $kpi)
    {
        $kpi->load(['user','department','evaluator']);
        return view('kpi.show', compact('kpi'));
    }

    public function update(Request $request, Kpi $kpi)
    {
        $validated = $request->validate(['achieved'=>'required|numeric|min:0','remarks'=>'nullable|string','status'=>'required|in:pending,in_progress,completed,approved']);
        $kpi->update($validated);
        return back()->with('success','KPI updated.');
    }

    public function approve(Kpi $kpi)
    {
        $kpi->update(['status'=>'approved','approved_at'=>now()]);
        return back()->with('success','KPI approved.');
    }

    public function departmentReport(Request $request)
    {
        $year=$request->get('year',now()->year); $month=$request->get('month',now()->month);
        $report = Department::active()->get()->map(function($dept) use($year,$month) {
            $kpis = Kpi::where('department_id',$dept->id)->where('year',$year)->where('month',$month)->get();
            return ['department'=>$dept->name,'color'=>$dept->color,'avg_score'=>round($kpis->avg('score')??0,2),'total_kpis'=>$kpis->count(),'approved'=>$kpis->where('status','approved')->count(),'employees'=>$kpis->pluck('user_id')->unique()->count()];
        });
        return view('kpi.department-report', compact('report','year','month'));
    }

    public function employeeKpiChart(Request $request, User $user)
    {
        $year = $request->get('year', now()->year);
        $data = Kpi::where('user_id',$user->id)->where('year',$year)->orderBy('month')->get(['month','score','title']);
        return response()->json($data);
    }
}

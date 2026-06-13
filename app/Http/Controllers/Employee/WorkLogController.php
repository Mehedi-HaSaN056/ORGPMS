<?php
namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\{WorkLog,Task};
use Illuminate\Http\Request;
class WorkLogController extends Controller {
    public function index() {
        $logs = WorkLog::where('user_id',auth()->id())->with('task')->latest('log_date')->paginate(20);
        return view('work-logs.index', compact('logs'));
    }
    public function store(Request $request) {
        $v = $request->validate(['task_id'=>'nullable|exists:tasks,id','description'=>'required|string','hours_spent'=>'nullable|numeric|min:0|max:24','log_date'=>'required|date']);
        $v['user_id'] = auth()->id();
        $v['department_id'] = auth()->user()->department_id;
        WorkLog::create($v);
        return back()->with('success','Work log added.');
    }
    public function show(WorkLog $workLog) { return view('work-logs.show', compact('workLog')); }
}

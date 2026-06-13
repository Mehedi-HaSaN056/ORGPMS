<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class DepartmentController extends Controller {
    public function index() { return view('admin.departments.index', ['departments' => Department::withCount('users')->get()]); }
    public function create() { return view('admin.departments.create'); }
    public function store(Request $request) {
        $v = $request->validate(['name'=>'required|string|max:100','code'=>'required|string|max:20|unique:departments','description'=>'nullable|string','color'=>'nullable|string','icon'=>'nullable|string']);
        $v['slug'] = Str::slug($v['name']);
        Department::create($v);
        return redirect()->route('admin.departments.index')->with('success','Department created.');
    }
    public function edit(Department $department) { return view('admin.departments.edit', compact('department')); }
    public function update(Request $request, Department $department) {
        $v = $request->validate(['name'=>'required|string|max:100','description'=>'nullable|string','color'=>'nullable|string','icon'=>'nullable|string','is_active'=>'boolean']);
        $v['slug'] = Str::slug($v['name']);
        $department->update($v);
        return redirect()->route('admin.departments.index')->with('success','Department updated.');
    }
    public function destroy(Department $department) { $department->delete(); return back()->with('success','Department deleted.'); }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Http\Request;
class RoleController extends Controller {
    public function index() { return view('admin.roles.index', ['roles' => Role::withCount('users')->with('permissions')->get()]); }
    public function create() { return view('admin.roles.create', ['permissions' => Permission::all()]); }
    public function store(Request $request) {
        $r = $request->validate(['name'=>'required|string|unique:roles','permissions'=>'array']);
        $role = Role::create(['name'=>$r['name'],'guard_name'=>'web']);
        if (!empty($r['permissions'])) $role->syncPermissions($r['permissions']);
        return redirect()->route('admin.roles.index')->with('success','Role created.');
    }
    public function edit(Role $role) { return view('admin.roles.edit', ['role'=>$role,'permissions'=>Permission::all()]); }
    public function update(Request $request, Role $role) {
        $r = $request->validate(['permissions'=>'array']);
        $role->syncPermissions($r['permissions'] ?? []);
        return back()->with('success','Role permissions updated.');
    }
    public function destroy(Role $role) { $role->delete(); return back()->with('success','Role deleted.'); }
}

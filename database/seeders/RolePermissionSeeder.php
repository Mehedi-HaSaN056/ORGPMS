<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};
class RolePermissionSeeder extends Seeder {
    public function run(): void {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'view plans','create plans','edit plans','delete plans','approve plans',
            'view kpi','create kpi','edit kpi','approve kpi',
            'view employees','create employees','edit employees','delete employees',
            'view messages','send messages','broadcast messages',
            'view reports','generate reports',
            'view dev-requests','create dev-requests','approve dev-requests',
            'view admin panel','manage departments','manage roles',
            'view all departments','view login logs','view activity logs',
        ];
        foreach ($permissions as $p) { Permission::firstOrCreate(['name'=>$p,'guard_name'=>'web']); }
        $roles = [
            'super_admin' => $permissions,
            'admin'       => array_diff($permissions, []),
            'management'  => ['view plans','approve plans','view kpi','approve kpi','view employees','view messages','send messages','broadcast messages','view reports','generate reports','view dev-requests','approve dev-requests','view all departments'],
            'department_head' => ['view plans','create plans','edit plans','view kpi','create kpi','edit kpi','view employees','view messages','send messages','view reports','view dev-requests','create dev-requests'],
            'employee'    => ['view plans','create plans','view kpi','view messages','send messages','view dev-requests','create dev-requests'],
        ];
        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name'=>$roleName,'guard_name'=>'web']);
            $role->syncPermissions($perms);
        }
    }
}

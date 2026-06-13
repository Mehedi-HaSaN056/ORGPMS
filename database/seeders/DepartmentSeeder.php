<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;
class DepartmentSeeder extends Seeder {
    public function run(): void {
        $depts = [
            ['name'=>'QC Department','code'=>'QC','color'=>'#10b981','icon'=>'bi-shield-check'],
            ['name'=>'IT Department','code'=>'IT','color'=>'#4f46e5','icon'=>'bi-laptop'],
            ['name'=>'Production Department','code'=>'PROD','color'=>'#f59e0b','icon'=>'bi-gear'],
            ['name'=>'VAT Department','code'=>'VAT','color'=>'#8b5cf6','icon'=>'bi-receipt'],
            ['name'=>'Mechanical Department','code'=>'MECH','color'=>'#6b7280','icon'=>'bi-wrench'],
            ['name'=>'Marketing Department','code'=>'MKT','color'=>'#ef4444','icon'=>'bi-megaphone'],
            ['name'=>'Accounts Department','code'=>'ACC','color'=>'#14b8a6','icon'=>'bi-calculator'],
            ['name'=>'Management','code'=>'MGMT','color'=>'#1d4ed8','icon'=>'bi-building'],
        ];
        foreach ($depts as $d) {
            $d['slug'] = Str::slug($d['name']);
            Department::firstOrCreate(['code'=>$d['code']], $d);
        }
    }
}

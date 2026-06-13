<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User, Department};
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder {
    public function run(): void {
        $mgmt = Department::where('code','MGMT')->first();
        $it   = Department::where('code','IT')->first();
        $qc   = Department::where('code','QC')->first();

        $superAdmin = User::firstOrCreate(['email'=>'superadmin@orgpms.com'], ['name'=>'Super Admin','password'=>Hash::make('password'),'department_id'=>$mgmt?->id,'designation'=>'Super Administrator','employee_id'=>'EMP-001','status'=>'active','joining_date'=>now()->subYear()]);
        $superAdmin->assignRole('super_admin');

        $admin = User::firstOrCreate(['email'=>'admin@orgpms.com'], ['name'=>'System Admin','password'=>Hash::make('password'),'department_id'=>$mgmt?->id,'designation'=>'Administrator','employee_id'=>'EMP-002','status'=>'active','joining_date'=>now()->subYear()]);
        $admin->assignRole('admin');

        $mgr = User::firstOrCreate(['email'=>'management@orgpms.com'], ['name'=>'Mr. Management','password'=>Hash::make('password'),'department_id'=>$mgmt?->id,'designation'=>'General Manager','employee_id'=>'EMP-003','status'=>'active','joining_date'=>now()->subMonths(18)]);
        $mgr->assignRole('management');

        $head = User::firstOrCreate(['email'=>'it.head@orgpms.com'], ['name'=>'IT Head','password'=>Hash::make('password'),'department_id'=>$it?->id,'designation'=>'IT Department Head','employee_id'=>'EMP-004','status'=>'active','joining_date'=>now()->subMonths(12)]);
        $head->assignRole('department_head');

        $emp = User::firstOrCreate(['email'=>'employee@orgpms.com'], ['name'=>'John Employee','password'=>Hash::make('password'),'department_id'=>$qc?->id,'designation'=>'QC Officer','employee_id'=>'EMP-005','status'=>'active','joining_date'=>now()->subMonths(6)]);
        $emp->assignRole('employee');

        echo "Demo users created:\n";
        echo "  superadmin@orgpms.com / password\n";
        echo "  admin@orgpms.com / password\n";
        echo "  management@orgpms.com / password\n";
        echo "  it.head@orgpms.com / password\n";
        echo "  employee@orgpms.com / password\n";
    }
}

<?php
namespace Database\Seeders;
use App\Models\Profile;
use App\Models\User;
use App\Models\Utility;
use App\Models\Settings;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Super Admin
        $superadmin = User::create([
            'first_name' => 'Superadmin',
            'last_name' => '',
            'company_name' => 'superadmin',
            'type' => 'super admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => date("Y-m-d H:i:s"),
            'password' => Hash::make('1234'),
            'acount_type' => 0,
            'created_by' => 0,
        ]);

        // Super Admin Profile
        $superadmin_profile = Profile::create([
            'user_id' => $superadmin->id,
        ]);


        // Company
        $company = User::create([
            'first_name' => 'Company',
            'last_name' => 'Company',
            'company_name' => 'Company',
            'type' => 'company',
            'email' => 'company@example.com',
            'email_verified_at' => date("Y-m-d H:i:s"),
            'password' => Hash::make('1234'),
            'created_by' => $superadmin->id,
            'acount_type' => 1,
            'company_setting' => '{"emp_show_rotas_price":"1","emp_show_avatars_on_rota":0,"emp_only_see_own_rota":0,"emp_can_see_all_locations":0,"company_week_start":"monday","company_time_format":"12","company_date_format":"","company_currency_symbol":"$","company_currency_symbol_position":"pre","see_note":"none","employees_can_set_availability":"1"}',
            'plan' => 1,
        ]);

        // Company Profile
        $company_profile = Profile::create([
            'user_id' => $company->id,
        ]);

        // Manager
        $manager = User::create([
            'first_name' => 'Manager',
            'last_name' => 'Manager',
            'company_name' => '',
            'type' => 'employee',
            'email' => 'manager@example.com',
            'email_verified_at' => date("Y-m-d H:i:s"),
            'password' => Hash::make('1234'),
            'created_by' => $company->id,
            'issue_by' => $company->id,
            'acount_type' => '2',
            'manager_permission' => '{"manage_loaction":"","manager_add_edit_delete_rotas":"1","manager_manage_leave_and_approve_leave_requests_for_other":"1","manager_manually_add_leave_to_themselves":"1","manager_manage_leave_embargoes":"1","manager_add_employees_and_manage_basic_information":"1","manager_view_and_edit_employee_salary":"1","manager_manage_roles":"1","manager_view_reports":"1"}',
        ]);

        // Employee Profile
        $employee_profile = Profile::create([
            'user_id' => $manager->id,
        ]);

        // $datas=[
        //     'storage_setting'=>'local',
        //     'local_storage_validation'=>'jpg,jpeg,png',
        //     'local_storage_max_upload_size'=>'2000',
        // ];

        // foreach($datas as $key => $data){

        //     $storage_setting = Settings::create([
        //         'name' => $key,
        //         'value' => $data,
        //         'created_by' => 1,
        //     ]);

        // }

        $data = [
            ['name'=>'local_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'wasabi_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'s3_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'local_storage_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'wasabi_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'s3_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()]
        ];
        DB::table('settings')->insert($data);
    }
}

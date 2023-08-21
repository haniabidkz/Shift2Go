<?php
namespace Database\Seeders;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use \App\Models\RequestRule;

class RequestRuleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RequestRule::create([
            'rule_name' => 'Minimum available employees',
            'created_by' => '0'
        ]);
        RequestRule::create([
            'rule_name' => 'Minimum available employees on location',
            'created_by' => '0'
        ]);
        RequestRule::create([
            'rule_name' => 'Minimum available employees in a group',
            'created_by' => '0'
        ]);
        RequestRule::create([
            'rule_name' => 'Minimum available employees assigned a role',
            'created_by' => '0'
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // employee personal detail
            $table->integer('user_id')->nullable();
            $table->string('gender',10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->text('profile_pic')->nullable();
            $table->string('city',100)->nullable();
            $table->string('county',100)->nullable();
            $table->string('postcode',50)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('emergency_contact_name',100)->nullable();
            $table->string('relationship_to_employee',100)->nullable();
            $table->string('emergency_contact_no',100)->nullable();

            // employee detail
            $table->integer('default_role_id')->nullable();
            $table->string('group_id',100)->nullable();
            $table->integer('weekly_hour')->nullable();
            $table->text('annual_holiday',100)->nullable();
            $table->string('employee_type',100)->nullable();
            $table->date('start_date')->nullable();
            $table->date('final_working_date')->nullable();
            $table->text('note')->nullable();

            // Location Detail
            $table->text('location_id')->nullable();

            //Employee Role detail
            $table->string('role_id',100)->nullable();
            //Employee salary
            $table->text('default_salary')->nullable();
            $table->text('custome_salary')->nullable();
            //Employee Work schedule
            $table->text('work_schedule')->nullable();
            $table->text('custom_day_off')->nullable();
            //Employee Documents
            //$table->integer('documents_id')->nullable();
            //Employee Logbook
            //$table->integer('logbook_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}

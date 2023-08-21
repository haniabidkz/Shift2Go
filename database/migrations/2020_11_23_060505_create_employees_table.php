<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',50)->nullable();
            $table->string('middle_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('email',100)->nullable();
            $table->string('gender',10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('city',100)->nullable();
            $table->text('profile_pic')->nullable();
            $table->string('county',100)->nullable();
            $table->integer('postcode')->nullable();
            $table->integer('phone')->nullable();
            // company location
            $table->string('location',50)->nullable();
            $table->integer('default_role_id')->nullable();
            $table->string('role_id',50)->nullable();
            $table->integer('group_id')->default('0');
            $table->integer('weekly_hour')->nullable();
            $table->integer('annual_holiday')->nullable();
            $table->date('start_date')->nullable();
            $table->date('final_working_date')->nullable();
            $table->text('note')->nullable();
            $table->integer('is_delete')->default('0');
            $table->date('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('employees');
    }
}

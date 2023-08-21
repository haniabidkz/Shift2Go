<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name',100)->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('type');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('issue_by')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('acount_type')->default(0)->comment('1=>admin/2=>manager/3=>employee');
            $table->text('manager_permission')->nullable();
            $table->text('company_detail')->nullable();
            $table->text('company_setting')->nullable();
            $table->string('lang',10)->default('en');
            $table->string('mode',10)->default('light');
            $table->integer('is_delete')->default(0)->comment('0=>active/1=>deactive');
            $table->date('deleted_at')->nullable();
            $table->integer('deleted_by')->default(0);
            $table->integer('plan')->nullable();
            $table->date('plan_expire_date')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('waiters', 'staff');

        Schema::table('staff', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->string('employee_id', 50)->unique()->nullable()->after('user_id');
            $table->enum('gender', ['Male','Female','Other'])->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('emergency_contact_name', 100)->nullable()->after('address');
            $table->string('emergency_contact_number', 20)->nullable()->after('emergency_contact_name');
            $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-'])->nullable()->after('emergency_contact_number');

            $table->enum('employee_type', ['Full-time','Part-time','Contract'])->default('Full-time')->after('status');
            $table->enum('department', ['Kitchen','Front of House','Delivery','Management'])->nullable()->after('employee_type');
            $table->string('designation', 50)->nullable()->after('department');
            $table->string('branch', 100)->nullable()->after('designation');
            $table->enum('work_shift', ['Morning','Evening','Night'])->nullable()->after('branch');
            $table->foreignId('reporting_manager_id')->nullable()->after('work_shift')->constrained('staff')->nullOnDelete();

            $table->enum('salary_type', ['Fixed','Hourly','Commission'])->default('Fixed')->after('salary');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('salary_type');
            $table->string('bank_account_no', 50)->nullable()->after('hourly_rate');
            $table->string('bank_name', 100)->nullable()->after('bank_account_no');

            $table->enum('status', ['Active','On Leave','Terminated'])->default('Active')->change();
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['reporting_manager_id']);
            $table->dropColumn([
                'user_id', 'employee_id', 'gender', 'date_of_birth',
                'emergency_contact_name', 'emergency_contact_number', 'blood_group',
                'employee_type', 'department', 'designation', 'branch', 'work_shift',
                'reporting_manager_id', 'salary_type', 'hourly_rate',
                'bank_account_no', 'bank_name'
            ]);
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
        Schema::rename('staff', 'waiters');
    }
};
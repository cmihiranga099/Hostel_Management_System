<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Basic Information Fields
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('address');
            }

            // University Details Fields  
            if (!Schema::hasColumn('users', 'university')) {
                $table->string('university')->nullable()->after('profile_image');
            }
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->unique()->nullable()->after('university');
            }
            if (!Schema::hasColumn('users', 'faculty')) {
                $table->string('faculty')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('users', 'degree_program')) {
                $table->string('degree_program')->nullable()->after('faculty');
            }
            if (!Schema::hasColumn('users', 'academic_year')) {
                $table->integer('academic_year')->nullable()->after('degree_program');
            }
            if (!Schema::hasColumn('users', 'expected_graduation')) {
                $table->date('expected_graduation')->nullable()->after('academic_year');
            }

            // Emergency Contact Fields
            if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('expected_graduation');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_relationship');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_email')) {
                $table->string('emergency_contact_email')->nullable()->after('emergency_contact_phone');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_address')) {
                $table->text('emergency_contact_address')->nullable()->after('emergency_contact_email');
            }

            // Additional useful fields
            if (!Schema::hasColumn('users', 'nic')) {
                $table->string('nic')->nullable()->after('emergency_contact_address');
            }
            if (!Schema::hasColumn('users', 'settings')) {
                $table->json('settings')->nullable()->after('nic');
            }
            if (!Schema::hasColumn('users', 'profile_completed_at')) {
                $table->timestamp('profile_completed_at')->nullable()->after('settings');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('profile_completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToRemove = [
                'phone', 
                'date_of_birth', 
                'gender', 
                'address', 
                'profile_image',
                'university', 
                'student_id', 
                'faculty', 
                'degree_program', 
                'academic_year', 
                'expected_graduation',
                'emergency_contact_name', 
                'emergency_contact_relationship', 
                'emergency_contact_phone', 
                'emergency_contact_email', 
                'emergency_contact_address',
                'nic',
                'settings',
                'profile_completed_at',
                'last_login_at'
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
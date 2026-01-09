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
        // Get existing columns to avoid duplicates
        $existingColumns = Schema::getColumnListing('users');
        
        Schema::table('users', function (Blueprint $table) use ($existingColumns) {
            // Add columns only if they don't exist
            $columnsToAdd = [
                'role' => function($table) { 
                    $table->string('role')->default('student')->after('email_verified_at'); 
                },
                'phone' => function($table) { 
                    $table->string('phone')->nullable()->after('email'); 
                },
                'university' => function($table) { 
                    $table->string('university')->nullable()->after('phone'); 
                },
                'faculty' => function($table) { 
                    $table->string('faculty')->nullable()->after('university'); 
                },
                'student_id' => function($table) { 
                    $table->string('student_id')->nullable()->after('faculty'); 
                },
                'year_of_study' => function($table) { 
                    $table->integer('year_of_study')->nullable()->after('student_id'); 
                },
                'nic' => function($table) { 
                    $table->string('nic')->nullable()->after('year_of_study'); 
                },
                'address' => function($table) { 
                    $table->text('address')->nullable()->after('nic'); 
                },
                'date_of_birth' => function($table) { 
                    $table->date('date_of_birth')->nullable()->after('address'); 
                },
                'gender' => function($table) { 
                    $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth'); 
                },
                'emergency_contact_name' => function($table) { 
                    $table->string('emergency_contact_name')->nullable()->after('gender'); 
                },
                'emergency_contact_phone' => function($table) { 
                    $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name'); 
                },
                'profile_image' => function($table) { 
                    $table->string('profile_image')->nullable()->after('emergency_contact_phone'); 
                },
                'settings' => function($table) { 
                    $table->json('settings')->nullable()->after('profile_image'); 
                },
                'preferences' => function($table) { 
                    $table->json('preferences')->nullable()->after('settings'); 
                },
                'status' => function($table) { 
                    $table->string('status')->default('active')->after('preferences'); 
                },
                'last_login_at' => function($table) { 
                    $table->timestamp('last_login_at')->nullable()->after('status'); 
                }
            ];
            
            foreach ($columnsToAdd as $columnName => $columnDefinition) {
                if (!in_array($columnName, $existingColumns)) {
                    $columnDefinition($table);
                    echo "Added column: {$columnName}\n";
                } else {
                    echo "Column already exists: {$columnName}\n";
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToCheck = [
                'role', 'phone', 'university', 'faculty', 'student_id', 'year_of_study',
                'nic', 'address', 'date_of_birth', 'gender', 'emergency_contact_name',
                'emergency_contact_phone', 'profile_image', 'settings', 'preferences',
                'status', 'last_login_at'
            ];
            
            $existingColumns = Schema::getColumnListing('users');
            
            foreach ($columnsToCheck as $column) {
                if (in_array($column, $existingColumns)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
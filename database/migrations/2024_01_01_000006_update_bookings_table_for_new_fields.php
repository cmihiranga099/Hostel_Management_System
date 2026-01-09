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
        Schema::table('bookings', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('bookings', 'student_id')) {
                $table->string('student_id')->nullable()->after('user_id'); // Use 'user_id' or 'id' instead
            }
            
            if (!Schema::hasColumn('bookings', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'special_requirements')) {
                $table->text('special_requirements')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'arrival_time')) {
                $table->time('arrival_time')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'departure_time')) {
                $table->time('departure_time')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'booking_source')) {
                $table->enum('booking_source', ['web', 'mobile', 'admin', 'api'])->default('web');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'online'])->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            
            if (!Schema::hasColumn('bookings', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable();
            }
            
            // Add foreign key constraints if they don't exist
            if (Schema::hasColumn('bookings', 'cancelled_by') && !$this->foreignKeyExists('bookings', 'bookings_cancelled_by_foreign')) {
                $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign keys first
            if ($this->foreignKeyExists('bookings', 'bookings_cancelled_by_foreign')) {
                $table->dropForeign(['cancelled_by']);
            }
            
            // Drop columns if they exist
            $columnsToCheck = [
                'student_id',
                'emergency_contact_phone',
                'emergency_contact_name',
                'special_requirements',
                'arrival_time',
                'departure_time',
                'booking_source',
                'payment_method',
                'cancellation_reason',
                'cancelled_at',
                'cancelled_by'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
    
    /**
     * Check if foreign key exists
     */
    private function foreignKeyExists($table, $name): bool
    {
        $schema = Schema::getConnection()->getDoctrineSchemaManager();
        $foreignKeys = $schema->listTableForeignKeys($table);
        
        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->getName() === $name) {
                return true;
            }
        }
        
        return false;
    }
};
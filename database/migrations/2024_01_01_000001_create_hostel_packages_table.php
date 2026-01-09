<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hostel_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['boys', 'girls']);
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->string('duration'); // monthly, semester, yearly
            $table->integer('capacity');
            $table->integer('available_slots');
            $table->json('facilities');
            $table->json('rules');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hostel_packages');
    }
};
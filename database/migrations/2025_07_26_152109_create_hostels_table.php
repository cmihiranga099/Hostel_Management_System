<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('city');
            $table->text('address')->nullable();
            $table->enum('type', ['boys', 'girls', 'mixed'])->default('mixed');
            $table->integer('capacity')->default(0);
            $table->integer('available_slots')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->json('facilities')->nullable();
            $table->json('rules')->nullable();
            $table->string('image_url')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('latitude', 8, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hostels');
    }
};
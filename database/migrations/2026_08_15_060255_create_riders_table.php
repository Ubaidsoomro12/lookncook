<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city', 100)->nullable();
            $table->string('image')->nullable(); // path to rider photo
            $table->enum('vehicle_type', ['bike', 'car', 'van', 'bicycle'])->default('bike');
            $table->string('vehicle_number', 50)->nullable();
            $table->string('license_number', 100)->nullable();
            $table->string('cnic', 20)->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->date('joining_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};
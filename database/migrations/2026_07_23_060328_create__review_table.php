<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // ✅ Staff Fields
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('staff_name')->nullable();
            $table->text('staff_review')->nullable();
            $table->integer('staff_rating')->nullable();
            $table->enum('staff_status', ['pending', 'good', 'bad', 'excellent', 'average'])->default('pending');

            // ✅ Customer Fields (Inko nullable kiya hai taake staff form se submit hone par error na aaye)
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->text('message')->nullable();
            $table->integer('rating')->nullable();
            $table->enum('status', ['pending', 'good', 'bad', 'excellent', 'average'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
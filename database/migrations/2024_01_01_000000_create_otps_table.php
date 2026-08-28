<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp', 6);
            $table->string('type'); // 'registration', 'password_reset', 'login'
            $table->timestamp('expires_at');
            $table->integer('attempts')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['email', 'type', 'is_verified']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('otps');
    }
};
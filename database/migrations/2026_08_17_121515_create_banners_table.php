<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('banners', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable();
        $table->string('subtitle')->nullable();
        $table->text('description')->nullable();          // New
        $table->string('image');
        $table->string('link')->nullable();
        $table->string('button_text', 50)->nullable();    // New
        $table->enum('section', ['hero', 'footer', 'about', 'services', 'gallery', 'contact'])->default('hero');
        $table->boolean('status')->default(true);
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
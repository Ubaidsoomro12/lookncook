<?php
// database/migrations/2026_07_30_063933_create_gallery_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            // ===== Form 1: Hero Section =====
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            // 3 Images with Titles
            $table->string('image1')->nullable();
            $table->string('image1_title')->nullable();
            $table->string('image2')->nullable();
            $table->string('image2_title')->nullable();
            $table->string('image3')->nullable();
            $table->string('image3_title')->nullable();

            // ===== Form 2: 9 Gallery Images =====
            $table->string('gallery_img1')->nullable();
            $table->string('gallery_img1_title')->nullable();
            $table->string('gallery_img2')->nullable();
            $table->string('gallery_img2_title')->nullable();
            $table->string('gallery_img3')->nullable();
            $table->string('gallery_img3_title')->nullable();
            $table->string('gallery_img4')->nullable();
            $table->string('gallery_img4_title')->nullable();
            $table->string('gallery_img5')->nullable();
            $table->string('gallery_img5_title')->nullable();
            $table->string('gallery_img6')->nullable();
            $table->string('gallery_img6_title')->nullable();
            $table->string('gallery_img7')->nullable();
            $table->string('gallery_img7_title')->nullable();
            $table->string('gallery_img8')->nullable();
            $table->string('gallery_img8_title')->nullable();
            $table->string('gallery_img9')->nullable();
            $table->string('gallery_img9_title')->nullable();

            // ===== Status =====
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galleries');
    }
};
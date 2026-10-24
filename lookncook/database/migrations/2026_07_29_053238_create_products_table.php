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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade');
            $table->string('name');
            $table->text('description');

            // Pricing — required
            $table->decimal('price', 10, 2);       // Old / Regular price
            $table->decimal('sale_price', 10, 2);   // Sale / Discounted price

            // Weight / portion — required
            $table->string('weight');

            // Optional variants (JSON array, stored as text)
            $table->text('variation')->nullable();

            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
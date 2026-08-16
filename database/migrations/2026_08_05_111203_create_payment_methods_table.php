<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
            $table->string('icon', 20)->nullable()->change();
            $table->text('description')->nullable()->change();
            // ⭐ ADDED THE LOGO COLUMN HERE
            $table->string('logo')->nullable()->after('icon');
            $table->integer('sort_order')->nullable()->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->string('icon', 20)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            // ⭐ DROPPING THE LOGO COLUMN HERE
            $table->dropColumn('logo');
            $table->integer('sort_order')->nullable(false)->default(0)->change();
        });
    }
};
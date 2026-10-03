<?php
// FILE: database/migrations/2026_08_05_111304_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_screenshot')) {
                $table->string('payment_screenshot')->nullable()->after('transaction_reference');
            }
            if (!Schema::hasColumn('orders', 'rider_assigned')) {
                $table->unsignedBigInteger('rider_assigned')->nullable()->after('payment_screenshot');
                $table->foreign('rider_assigned')->references('id')->on('riders')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'estimated_time')) {
                $table->string('estimated_time')->nullable()->after('rider_assigned');
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', ['review', 'preparing', 'completed', 'delivered'])
                      ->default('review')
                      ->after('estimated_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'rider_assigned')) {
                $table->dropForeign(['rider_assigned']);
                $table->dropColumn('rider_assigned');
            }
            if (Schema::hasColumn('orders', 'estimated_time')) {
                $table->dropColumn('estimated_time');
            }
            if (Schema::hasColumn('orders', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('orders', 'payment_screenshot')) {
                $table->dropColumn('payment_screenshot');
            }
        });
    }
};
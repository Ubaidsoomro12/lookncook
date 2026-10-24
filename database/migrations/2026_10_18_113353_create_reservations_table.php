<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('hall_name');
            $table->text('address')->nullable();
            $table->date('event_date');
            $table->integer('total_guests')->default(1);
            
            $table->decimal('food_subtotal', 12, 2)->default(0);
            $table->decimal('hall_payment', 12, 2)->default(0);
            $table->decimal('stage_decoration', 12, 2)->default(0);
            $table->decimal('front_decoration', 12, 2)->default(0);
            $table->decimal('overall_decoration', 12, 2)->default(0);
            $table->decimal('decoration_total', 12, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('per_head_amount', 12, 2)->default(0);
            
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps(); // Automatically tracks created_at and updated_at
        });
    }
    public function down(): void { Schema::dropIfExists('reservations'); }
};
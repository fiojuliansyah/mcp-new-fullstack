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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('classroom_id')->nullable();
            $table->string('schedule_day')->nullable();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();

            $table->decimal('price', 10, 2);
            $table->foreignId('coupon_id')->nullable();
            $table->foreignId('plusian_coupon_id')->nullable();
            $table->string('payment_method');
            $table->string('payment_status')->default('unpaid');
            $table->decimal('total', 10, 2);
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->string('payment_gateway_bill_id')->nullable();
            
            $table->string('status')->default('pending')->index();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->dateTime('renewal_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('code')->unique();

            $table->enum('type', ['percentage', 'fixed']);
            $table->integer('value');

            $table->dateTime('expired_at')->nullable();
            $table->integer('limit')->nullable();
            $table->integer('times_used')->default(0);
            $table->integer('max_uses_per_user')->default(1);
            $table->integer('min_purchase_amount')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('status')->default(false);
            $table->string('transaction_id')->nullable();
            $table->string('transaction_payment_id')->nullable();
            $table->string('transaction_payment_auth')->nullable();
            $table->string('transaction_payment_ref')->nullable();
            $table->string('payment_token')->nullable()->unique();
            $table->decimal('price', 10, 3)->default(0);
            $table->decimal('delivery_fees', 10, 3)->default(0);
            $table->string('purpose')->default('order');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('KNET');
            $table->string('knet_transport_id')->nullable();
            $table->string('knet_transport_password')->nullable();
            $table->string('knet_resource_key')->nullable();
            $table->string('test_url')->nullable();
            $table->string('live_url')->nullable();
            $table->enum('mode', ['test_mode', 'live_mode'])->default('test_mode');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'preparing', 'delivered', 'failed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::table('orders')->whereIn('status', ['preparing', 'delivered'])->update(['status' => 'paid']);

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'failed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};

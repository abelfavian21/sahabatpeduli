<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $t) {
            $t->string('order_id')->nullable()->unique();
            $t->text('snap_token')->nullable();
            $t->string('payment_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $t) {
            $t->dropColumn(['order_id', 'snap_token', 'payment_type']);
        });
    }
};

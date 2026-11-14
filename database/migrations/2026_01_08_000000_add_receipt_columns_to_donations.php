<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $t) {
            $t->timestamp('paid_at')->nullable();
            $t->timestamp('receipt_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('donations', fn (Blueprint $t) => $t->dropColumn(['paid_at', 'receipt_sent_at']));
    }
};

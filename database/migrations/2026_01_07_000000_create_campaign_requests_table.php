<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaign_requests', function (Blueprint $t) {
            $t->id();
            $t->string('ticket_code')->unique();
            $t->string('name');
            $t->text('nik');                       // dienkripsi lewat cast model
            $t->string('phone');
            $t->string('email')->nullable();
            $t->string('city');
            $t->string('ktp_photo');               // disk privat
            $t->string('selfie_photo')->nullable();
            $t->json('evidence')->nullable();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('relation');
            $t->string('recipient_name')->nullable();
            $t->string('location');
            $t->string('title');
            $t->text('story');
            $t->text('fund_usage');
            $t->unsignedBigInteger('target');
            $t->string('status')->default('pending'); // pending|reviewing|need_info|approved|rejected
            $t->text('admin_note')->nullable();
            $t->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_requests');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('icon', 20)->default('heart');
            $t->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('organizer');
            $t->string('excerpt');
            $t->text('story');
            $t->string('image')->nullable();
            $t->unsignedBigInteger('target');
            $t->unsignedBigInteger('collected')->default(0);
            $t->date('deadline');
            $t->boolean('is_urgent')->default(false);
            $t->boolean('is_verified')->default(false);
            $t->string('status')->default('active'); // draft|active|closed
            $t->timestamps();
        });

        Schema::create('donations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('email')->nullable();
            $t->unsignedBigInteger('amount');
            $t->boolean('is_anonymous')->default(false);
            $t->text('message')->nullable();
            $t->string('status')->default('pending'); // pending|paid|failed
            $t->timestamps();
        });

        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('excerpt');
            $t->text('body');
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['articles', 'donations', 'campaigns', 'categories'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('provider', ['fcm', 'onesignal'])->index();
            $table->string('token', 512)->unique();
            $table->string('device_id', 128)->nullable()->index();
            $table->string('device_os', 32)->nullable();
            $table->string('device_model', 96)->nullable();
            $table->string('app_version', 32)->nullable();
            $table->string('language', 12)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamp('revoked_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_tokens');
    }
};



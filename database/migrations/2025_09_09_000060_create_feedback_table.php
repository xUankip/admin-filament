<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('rating')->index();
            $table->text('comment')->nullable();
            $table->boolean('flagged')->default(false)->index();
            $table->json('summary_tags')->nullable();
            $table->timestamps();
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->index(['event_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};




<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('organizer_id')->constrained('users');
            $table->json('co_organizers')->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('start_at')->index();
            $table->dateTime('end_at')->index();
            $table->integer('capacity')->default(0);
            $table->integer('seats_left')->default(0)->index();
            $table->string('banner_url')->nullable();
            $table->string('doc_url')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'published', 'completed', 'canceled'])->default('draft')->index();
            $table->json('approval_log')->nullable();
            $table->integer('popularity_score')->default(0)->index();
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index(['start_at', 'end_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};




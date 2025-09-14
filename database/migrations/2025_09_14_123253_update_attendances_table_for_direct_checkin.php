<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Add new columns for direct check-in
            $table->unsignedBigInteger('event_id')->after('id');
            $table->unsignedBigInteger('user_id')->after('event_id');
            $table->string('checkin_code')->after('user_id');
            $table->string('status')->default('present')->after('checked_in_at');
            
            // Add foreign key constraints
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Add indexes for better performance
            $table->index(['event_id', 'user_id']);
            $table->index('checkin_code');
            $table->index('checked_in_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['event_id']);
            $table->dropForeign(['user_id']);
            
            // Drop indexes
            $table->dropIndex(['event_id', 'user_id']);
            $table->dropIndex(['checkin_code']);
            $table->dropIndex(['checked_in_at']);
            
            // Drop columns
            $table->dropColumn(['event_id', 'user_id', 'checkin_code', 'status']);
        });
    }
};
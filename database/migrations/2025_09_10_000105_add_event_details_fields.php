<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'description')) {
                $table->longText('description')->nullable()->after('title');
            }
            if (! Schema::hasColumn('events', 'contact_info')) {
                $table->string('contact_info')->nullable()->after('venue');
            }
            if (! Schema::hasColumn('events', 'rules')) {
                $table->text('rules')->nullable()->after('contact_info');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'rules')) {
                $table->dropColumn('rules');
            }
            if (Schema::hasColumn('events', 'contact_info')) {
                $table->dropColumn('contact_info');
            }
            if (Schema::hasColumn('events', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};



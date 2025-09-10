<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'waitlist_enabled')) {
                $table->boolean('waitlist_enabled')->default(false)->after('seats_left')->index();
            }
        });

        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'on_waitlist')) {
                $table->boolean('on_waitlist')->default(false)->after('status')->index();
            }
            if (! Schema::hasColumn('registrations', 'fee_paid')) {
                $table->boolean('fee_paid')->default(false)->after('on_waitlist')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'fee_paid')) {
                $table->dropColumn('fee_paid');
            }
            if (Schema::hasColumn('registrations', 'on_waitlist')) {
                $table->dropColumn('on_waitlist');
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'waitlist_enabled')) {
                $table->dropColumn('waitlist_enabled');
            }
        });
    }
};



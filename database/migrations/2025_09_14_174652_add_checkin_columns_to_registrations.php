<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->boolean('checked_in')->default(false)->after('status');
            $table->timestamp('checked_in_at')->nullable()->after('checked_in');
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['checked_in', 'checked_in_at']);
        });
    }
};

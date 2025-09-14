<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Thay đổi certificate_id thành nullable
            $table->string('certificate_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Rollback: làm NOT NULL lại
            $table->string('certificate_id')->nullable(false)->change();
        });
    }
};

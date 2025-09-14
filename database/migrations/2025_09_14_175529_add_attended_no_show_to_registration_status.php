<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Thay đổi ENUM để bao gồm 'attended' và 'no_show'
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'attended', 'no_show') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        // Rollback về ENUM cũ (giả sử chỉ có 3 giá trị ban đầu)
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};

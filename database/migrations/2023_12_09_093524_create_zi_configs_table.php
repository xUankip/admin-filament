<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zi_configs', function (Blueprint $table) {
            $table->id();
            $table->string("group")->comment("Group of config");
            $table->string("key")->comment("Key of config");
            $table->longText("value")->nullable()->comment("Value of config: Encrypted");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zi_configs');
    }
};

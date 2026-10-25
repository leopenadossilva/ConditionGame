<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enemies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('hp');
            $table->unsignedInteger('max_hp');
            $table->unsignedInteger('level')->default(1);
            $table->unsignedInteger('xp_reward')->default(0);
            $table->unsignedInteger('gold_reward')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enemies');
    }
};

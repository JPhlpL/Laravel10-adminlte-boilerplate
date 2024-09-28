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
        Schema::dropIfExists('task');
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('task_num');
            $table->string('task_title');
            $table->string('task_desc');
            $table->string('task_status');
            $table->string('task_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};

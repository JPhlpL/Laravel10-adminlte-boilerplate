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
        Schema::dropIfExists('sub_task');
        Schema::create('sub_task', function (Blueprint $table) {
            $table->id();
            $table->string('sub_task_num');
            $table->string('sub_task_title');
            $table->string('sub_task_desc');
            $table->string('sub_task_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_task');
    }
};

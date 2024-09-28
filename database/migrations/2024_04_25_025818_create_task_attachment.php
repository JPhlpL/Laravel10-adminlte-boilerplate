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
        Schema::dropIfExists('task_attachment');
        Schema::create('task_attachment', function (Blueprint $table) {
            $table->id();
            $table->string('task_attach_num');
            $table->string('task_attach_name');
            $table->string('task_attach_filesize');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_attachment');
    }
};

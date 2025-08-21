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
        Schema::create('video_subtitles', function (Blueprint $table) {
            $table->id();
            $table->integer('subtitle_number')->default(1);
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('start');
            $table->integer('end');
            $table->string('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_subtitles');
    }
};

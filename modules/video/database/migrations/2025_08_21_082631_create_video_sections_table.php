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
        Schema::create('video_sections', function (Blueprint $table) {
            $table->id();
             $table->integer('section_number')->default(1);
            // The section_number field is used to order sections within a video
            // and can be used to identify the sequence of sections.
            // It is set to default to 1, but can be incremented for each section.
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');
            $table->integer('start');
            $table->integer('end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_sections');
    }
};
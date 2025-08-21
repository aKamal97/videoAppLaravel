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
        Schema::create('video_quizes', function (Blueprint $table) {
            $table->id();
            $table->integer('quize_number')->default(1);
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('start');
            $table->integer('end');
            $table->integer('questionType');
            $table->string('question');
            $table->string('answer1');
            $table->string('answer2');
            $table->string('answer3');
            $table->string('answer4');
            $table->string('answer5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_quizes');
    }
};
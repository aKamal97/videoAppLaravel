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
        Schema::create('video_quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id'); 
            $table->integer('quize_number');
            $table->integer('start'); 
            $table->integer('end');
            $table->string('questionType');
            $table->text('question');
            $table->string('answer1')->nullable();
            $table->string('answer2')->nullable();
            $table->string('answer3')->nullable();
            $table->string('answer4')->nullable();
            $table->string('answer5')->nullable();
            $table->timestamps();

            $table->foreign('video_id')
                  ->references('id')
                  ->on('videos')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_quizzes');
    }
};

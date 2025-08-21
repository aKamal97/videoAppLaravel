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
        Schema::create('video_url_potatoes', function (Blueprint $table) {
            $table->id();
              $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('url');
            $table->integer('start');
            $table->integer('end');
            $table->integer('url_number')->default(1);
            // The isembedcode field indicates whether the URL is an embed code or a regular URL
            $table->boolean('isembedcode')->default(false);
            // The description field is used to store any additional information related to the URL
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_url_potatoes');
    }
};
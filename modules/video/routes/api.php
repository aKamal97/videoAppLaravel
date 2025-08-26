<?php

use Illuminate\Support\Facades\Route;
use Modules\Video\App\Http\Controllers\VideoController;
use Modules\Video\App\Http\Controllers\SectionController;
use Modules\Video\App\Http\Controllers\QuizController;
use Modules\Video\App\Http\Controllers\SubtitleController;
use Modules\Video\App\Http\Controllers\VideoUrlCodeController;
// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('videos', VideoController::class)->names('video');
// });
Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('video.index');
    Route::post('/', [VideoController::class, 'store'])->name('video.store');
    Route::get('/{video}', [VideoController::class, 'show'])->name('video.show');
    Route::put('/{video}', [VideoController::class, 'update'])->name('video.update');
    Route::delete('/{video}', [VideoController::class, 'destroy'])->name('video.destroy');
    Route::get('/{video}/sections', [SectionController::class, 'getSections'])->name('video.sections');
    Route::post('/{video}/sections', [SectionController::class, 'store'])->name('video.sections.create');
    Route::put('/{video}/sections/{section}', [SectionController::class, 'update'])->name('video.sections.update');
    Route::delete('/{video}/sections/{section}', [SectionController::class, 'destroy'])->name('video.sections.delete');
    Route::get('/{video}/sections/{section}', [SectionController::class, 'show'])->name('video.sections.show');
    Route::post('/{video}/url-codes', [VideoUrlCodeController::class, 'store'])->name('video.url-codes.create');
    Route::get('/{video}/url-codes', [VideoUrlCodeController::class, 'getUrlCodesByVideoId'])->name('video.url-codes');
    Route::put('/{video}/url-codes/{urlCode}', [VideoUrlCodeController::class, 'update'])->name('video.url-codes.update');
    Route::delete('/{video}/url-codes/{urlCode}', [VideoUrlCodeController::class, 'destroy'])->name('video.url-codes.delete');
    Route::get('/{video}/url-codes/{urlCode}', [VideoUrlCodeController::class, 'show'])->name('video.url-codes.show');

    // Quizzes
    Route::get('/{video}/quizzes', [QuizController::class, 'getQuizzes'])->name('video.quizzes');
    Route::post('/{video}/quizzes', [QuizController::class, 'create'])->name('video.quizzes.create');
    Route::get('/{video}/quizzes/{quiz}', [QuizController::class, 'show'])->name('video.quizzes.show');
    Route::put('/{video}/quizzes/{quiz}', [QuizController::class, 'update'])->name('video.quizzes.update');
    Route::delete('/{video}/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('video.quizzes.delete');

    // Subtitles
    Route::get('/{video}/subtitles', [SubtitleController::class, 'getSubtitles'])->name('video.subtitles');
    Route::post('/{video}/subtitles', [SubtitleController::class, 'create'])->name('video.subtitles.create');
    Route::get('/{video}/subtitles/{subtitle}', [SubtitleController::class, 'show'])->name('video.subtitles.show');
    Route::put('/{video}/subtitles/{subtitle}', [SubtitleController::class, 'update'])->name('video.subtitles.update');
    Route::delete('/{video}/subtitles/{subtitle}', [SubtitleController::class, 'destroy'])->name('video.subtitles.delete');
});

Route::prefix('sections')->group(function () {
    Route::get('/', [SectionController::class, 'index'])->name('section.index');
});

//quizzes 
Route::prefix('quizzes')->group(function () {
    Route::get('/', [QuizController::class, 'index'])->name('quiz.index');
});

//subtitles 
Route::prefix('subtitles')->group(function () {
    Route::get('/', [SubtitleController::class, 'index'])->name('subtitle.index');
});




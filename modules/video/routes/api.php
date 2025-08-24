<?php

use Illuminate\Support\Facades\Route;
use Modules\Video\App\Http\Controllers\VideoController;
use Modules\Video\App\Http\Controllers\SectionController;
use Modules\Video\App\Http\Controllers\QuizController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('videos', VideoController::class)->names('video');
// });
Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('video.index');
    Route::post('/', [VideoController::class, 'create'])->name('video.create');
    Route::get('/{video}/sections', [SectionController::class, 'getSections'])->name('video.sections');
    Route::post('/{video}/sections', [SectionController::class, 'create'])->name('video.sections.create');

});

Route::prefix('sections')->group(function () {
    Route::get('/', [SectionController::class, 'index'])->name('section.index');
});

//quizzes 
Route::prefix('quizzes')->group(function () {
    Route::get('/', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/{id}', [QuizController::class, 'show'])->name('quiz.show');
    Route::get('/video/{videoId}', [QuizController::class, 'getQuizzes'])->name('quiz.video');
    Route::post('/create/{videoId}', [QuizController::class, 'create'])->name('quiz.create');
    Route::post('/{id}', [QuizController::class, 'update'])->name('quiz.update');
    Route::delete('/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');
});
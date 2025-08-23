<?php

use Illuminate\Support\Facades\Route;
use Modules\Video\App\Http\Controllers\VideoController;
use Modules\Video\App\Http\Controllers\SectionController;

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
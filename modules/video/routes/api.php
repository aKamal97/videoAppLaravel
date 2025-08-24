<?php

use Illuminate\Support\Facades\Route;
use Modules\Video\App\Http\Controllers\VideoController;
use Modules\Video\App\Http\Controllers\SectionController;

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

});

Route::prefix('sections')->group(function () {
    Route::get('/', [SectionController::class, 'index'])->name('section.index');

});
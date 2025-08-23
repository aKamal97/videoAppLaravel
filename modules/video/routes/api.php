<?php

use Illuminate\Support\Facades\Route;
use Modules\Video\App\Http\Controllers\VideoController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('videos', VideoController::class)->names('video');
// });
Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('video.index');
    Route::post('/', [VideoController::class, 'create'])->name('video.create');
    Route::get('/{video}/sections', [VideoController::class, 'getSections'])->name('video.sections');

});
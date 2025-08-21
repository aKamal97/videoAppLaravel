<?php

use Illuminate\Support\Facades\Route;
use Modules\Video\Http\Controllers\VideoController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('videos', VideoController::class)->names('video');
});

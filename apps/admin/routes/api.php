<?php

use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\PageConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/page-config/{slug?}', [PageConfigController::class, 'show']);
Route::get('/content/{slug?}', [ContentController::class, 'index']);

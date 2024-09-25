<?php

use App\Http\Controllers\RacingController;
use Illuminate\Support\Facades\Route;

Route::get('/racing-results', RacingController::class)->name('racing.result');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;

Route::get('/trangchu', [HomeController::class, 'index']);

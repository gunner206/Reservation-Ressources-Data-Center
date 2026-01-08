<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/reservation/create', [ReservationController::class, 'create']);
Route::post('/reservation', [ReservationController::class, 'store']);

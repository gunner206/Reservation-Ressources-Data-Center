<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RessourceController;

Route::get('/', function () {
    return view('layout');
});

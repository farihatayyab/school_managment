<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\StudentController;

Route::apiResource('class-rooms', ClassRoomController::class);
Route::apiResource('students', StudentController::class);

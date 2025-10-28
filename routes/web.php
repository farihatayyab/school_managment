<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AnnouncementController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 🟢 Admin Routes (Add/Delete)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/class-rooms', [ClassRoomController::class, 'store']);
    Route::delete('/class-rooms/{id}', [ClassRoomController::class, 'destroy']);
});

// 🟡 Admin + Teacher (Update)
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::put('/class-rooms/{id}', [ClassRoomController::class, 'update']);
});

// 🔵 All (View)
Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/class-rooms', [ClassRoomController::class, 'index'])->name('class-rooms.index');
});


// 🟢 View for All (admin, teacher, student)
Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
});

// 🟡 Add/Edit for Admin + Teacher
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
});

// 🔴 Delete for Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});



// 🟢 View (Admin + Teacher + Student)
Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
});

// 🟡 Add / Edit (Admin + Teacher)
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
});

// 🔴 Delete (Admin Only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
});



// 👁 View (Admin, Teacher, Student)
Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
});

// 🟡 Add / Edit (Admin Only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
});

// 🔴 Delete (Admin Only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});


use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';

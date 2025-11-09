<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ResultController;
//****************************************************************************************** */
//***********************************Auth Route*************************************** */
//****************************************************************************************** */
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

//****************************************************************************************** */
//***********************************Classes Route*************************************** */
//****************************************************************************************** */

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/class-rooms', [ClassRoomController::class, 'store']);
    Route::delete('/class-rooms/{id}', [ClassRoomController::class, 'destroy']);
});

Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::put('/class-rooms/{id}', [ClassRoomController::class, 'update']);
});

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/class-rooms', [ClassRoomController::class, 'index'])->name('class-rooms.index');
});

//****************************************************************************************** */
//***********************************Student Route*************************************** */
//****************************************************************************************** */

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
});


Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});

//****************************************************************************************** */
//***********************************Teacher Route*************************************** */
//****************************************************************************************** */

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
});

Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
});

//****************************************************************************************** */
//***********************************Announcement Route*************************************** */
//****************************************************************************************** */

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
});

Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
    ->name('announcements.destroy');


//****************************************************************************************** */
//***********************************Subject Route*************************************** */
//****************************************************************************************** */

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
});

Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
});

//****************************************************************************************** */
//***********************************Attendence Route*************************************** */
//****************************************************************************************** */


Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
});

Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
    Route::delete('/attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
});


// 👇 Role-based routes
Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
});

// 👑 Admin only (Add/Edit/Delete)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
});
         

// Everyone (Admin, Teacher, Student) can view results
Route::get('/results', [ResultController::class, 'index'])->name('results.index');

// ✅ Admin-only actions
Route::middleware(['role:admin'])->group(function () {
    Route::post('/results', [ResultController::class, 'store'])->name('results.store');
    Route::put('/results/{id}', [ResultController::class, 'update'])->name('results.update');
    Route::delete('/results/{id}', [ResultController::class, 'destroy'])->name('results.destroy');
});

// ✅ Teacher-only actions
Route::middleware(['role:teacher'])->group(function () {
    Route::post('/teacher/results', [ResultController::class, 'teacherStore'])->name('teacher.results.store');
    Route::put('/teacher/results/{id}', [ResultController::class, 'teacherUpdate'])->name('teacher.results.update');
});


use App\Http\Controllers\FeeController;

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/fees', [FeeController::class, 'store'])->name('fees.store');
    Route::put('/fees/{id}', [FeeController::class, 'update'])->name('fees.update');
    Route::delete('/fees/{id}', [FeeController::class, 'destroy'])->name('fees.destroy');
});


//****************************************************************************************** */
//***********************************Dashboard Route*************************************** */
//****************************************************************************************** */

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';

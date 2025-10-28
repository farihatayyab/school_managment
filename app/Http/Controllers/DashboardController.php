<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Announcement;
use App\Models\ClassRoom;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ Different counts based on user role
        if ($user->role === 'admin') {
            $totalAnnouncements = Announcement::count(); // Active + Inactive
        } else {
            $totalAnnouncements = Announcement::where('status', 'active')->count(); // Only Active
        }

        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = ClassRoom::count();

        return Inertia::render('Dashboard', [
            'auth' => ['user' => $user],
              'userRole' => auth()->user()->role, // assume role = 'admin', 'teacher', or 'student'
            'totalAnnouncements' => $totalAnnouncements,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalClasses' => $totalClasses,
        ]);
    }
}

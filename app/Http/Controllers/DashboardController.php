<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Subject;
use App\Models\Fee;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        // 📢 Announcements count
        $totalAnnouncements = ($role === 'admin')
            ? Announcement::count()
            : Announcement::where('status', 'active')->count();

        // 👩‍🎓 Basic counts
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = ClassRoom::count();
        $totalSubjects = Subject::count();
        $totalExams = Exam::count();

        // 🧮 Results count
        if ($role === 'admin') {
            $totalResults = Result::count();
        } elseif ($role === 'teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();
            $totalResults = Result::whereHas('exam.subject', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id ?? null);
            })->count();
        } else {
            $student = Student::where('user_id', $user->id)->first();
            $totalResults = Result::where('student_id', $student->id ?? null)->count();
        }

        // 🗓️ Attendance count
        if ($role === 'admin' || $role === 'teacher') {
            $totalAttendance = Attendance::count();
        } else {
            $student = Student::where('user_id', $user->id)->first();
            $totalAttendance = Attendance::where('student_id', $student->id ?? null)
                ->where('status', 'present')
                ->count();
        }

        // 💰 FEES LOGIC — FINAL FIXED VERSION
        $totalPaidFees = 0;
        $studentPaidFees = 0;

        if ($role === 'admin') {
            // 🔹 Admin → total paid fees count of all students
            $totalPaidFees = Fee::where('status', 'paid')->count();
        } 
        elseif ($role === 'teacher') {
            // 🔹 Teacher → total paid fees count of all students as well
            // (you can later filter by teacher's classes if needed)
            $totalPaidFees = Fee::where('status', 'paid')->count();
        } 
        elseif ($role === 'student') {
            // 🔹 Student → only his/her own paid fees count
            $studentPaidFees = Fee::where('student_id', $user->id)
                ->where('status', 'paid')
                ->count();
        }

        // 🚀 Return to Inertia Dashboard
        return Inertia::render('Dashboard', [
            'auth' => ['user' => $user],
            'userRole' => $role,
            'totalAnnouncements' => $totalAnnouncements,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalAttendence' => $totalAttendance,
            'totalExams' => $totalExams,
            'totalResults' => $totalResults,
            'totalPaidFees' => $totalPaidFees,
            'studentPaidFees' => $studentPaidFees,
        ]);
    }
}

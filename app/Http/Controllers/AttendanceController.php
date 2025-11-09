<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🟢 Admin → all attendances
        if ($user->role === 'admin') {
            $attendances = Attendance::with('student.user')->get();
            $students = Student::with('user')->get();
        }

        // 🟢 Teacher → all attendances (same as admin)
        elseif ($user->role === 'teacher') {
            $attendances = Attendance::with('student.user')->get();
            $students = Student::with('user')->get();
        }

        // 🟡 Student → sirf apni attendance
        else {
            $student = $user->student; // Relation in User model
            $attendances = Attendance::with('student.user')
                ->where('student_id', $student->id)
                ->get();
            $students = []; // Student ko dropdown ki zarurat nahi
        }

        return Inertia::render('Attendance', [
            'attendances' => $attendances,
            'students' => $students,
            'role' => $user->role,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        Attendance::create($request->all());
        return back()->with('success', 'Attendance Added Successfully');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        $attendance->update($request->all());
        return back()->with('success', 'Attendance Updated Successfully');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance Deleted Successfully');
    }
}

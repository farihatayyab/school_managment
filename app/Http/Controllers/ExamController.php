<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        // 🔹 Admin — All exams
        if ($role === 'admin') {
            $exams = Exam::with(['student.user', 'classRoom', 'subject'])->get();
        }

        // 🔹 Teacher — Exams related to their subjects/classes
        elseif ($role === 'teacher') {
            $exams = Exam::with(['student.user', 'classRoom', 'subject'])
                ->whereHas('subject', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher->id ?? null);
                })
                ->get();
        }

        // 🔹 Student — Only their exams
        else {
            $exams = Exam::with(['student.user', 'classRoom', 'subject'])
                ->where('student_id', $user->student->id ?? null)
                ->get();
        }

        $students = Student::with('user')->get();
        $classes = ClassRoom::all();
        $subjects = Subject::all();

        return Inertia::render('Exams', [
            'exams' => $exams,
            'students' => $students,
            'classes' => $classes,
            'subjects' => $subjects,
            'role' => $role,
        ]);
    }

   public function store(Request $request)
{
    $user = auth()->user();

    if (!in_array($user->role, ['admin', 'teacher'])) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'class_id' => 'required|exists:class_rooms,id',
        'subject_id' => 'required|exists:subjects,id',
        'exam_date' => 'required|date',
        'total_marks' => 'required|integer|min:0',
    ]);

    // Optional: restrict teachers to their own subjects
    if ($user->role === 'teacher') {
        $subject = \App\Models\Subject::find($validated['subject_id']);
        if ($subject->teacher_id !== ($user->teacher->id ?? null)) {
            abort(403, 'You can only add exams for your subjects.');
        }
    }

    Exam::create($validated);
    return back()->with('success', 'Exam added successfully!');
}

public function update(Request $request, $id)
{
    $user = auth()->user();

    if (!in_array($user->role, ['admin', 'teacher'])) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'class_id' => 'required|exists:class_rooms,id',
        'subject_id' => 'required|exists:subjects,id',
        'exam_date' => 'required|date',
        'total_marks' => 'required|integer|min:0',
    ]);

    $exam = Exam::findOrFail($id);

    // Teacher can only edit their subject’s exams
    if ($user->role === 'teacher' && $exam->subject->teacher_id !== ($user->teacher->id ?? null)) {
        abort(403, 'You can only edit your own exams.');
    }

    $exam->update($validated);
    return back()->with('success', 'Exam updated successfully!');
}

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        Exam::findOrFail($id)->delete();
        return back()->with('success', 'Exam deleted successfully!');
    }
}

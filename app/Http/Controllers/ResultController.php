<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResultController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $results = Result::with(['student.user', 'exam.subject', 'exam.classRoom'])->get();
        } elseif ($user->role === 'teacher') {
            $results = Result::with(['student.user', 'exam.subject', 'exam.classRoom'])
                ->whereHas('exam.subject', function ($q) use ($user) {
                    $q->where('teacher_id', $user->teacher->id ?? null);
                })
                ->get();
        } else { // student
    $results = Result::with(['student.user', 'exam.subject', 'exam.classRoom'])
        ->where('student_id', $user->student->id ?? null)
        ->get();
}


        $students = Student::with('user')->get();
        $exams = Exam::with(['subject', 'classRoom'])->get();

        return Inertia::render('Results', [
            'results' => $results,
            'students' => $students,
            'exams' => $exams,
            'role' => $user->role,
        ]);
    }

    // ✅ Admin can add any result
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'obtained_marks' => 'required|integer|min:0',
        ]);

        $result = Result::create($validated);
        $result->load(['student.user', 'exam.subject', 'exam.classRoom']);

        return response()->json($result);
    }

    // ✅ Admin can update any result
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'obtained_marks' => 'required|integer|min:0',
            'student_id' => 'sometimes|exists:students,id',
            'exam_id' => 'sometimes|exists:exams,id',
        ]);

        $result = Result::findOrFail($id);
        $result->update($validated);
        $result->load(['student.user', 'exam.subject', 'exam.classRoom']);

        return response()->json($result);
    }

    // ✅ Admin can delete
    public function destroy($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();

        return response()->json(['message' => 'Result deleted successfully']);
    }

    // 👩‍🏫 Teacher can only add result for exams they teach
    public function teacherStore(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'obtained_marks' => 'required|integer|min:0',
        ]);

        $exam = Exam::with('subject')->findOrFail($validated['exam_id']);

        // teacher can add result only for their own subjects
        if ($exam->subject->teacher_id !== ($user->teacher->id ?? null)) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $result = Result::create($validated);
        $result->load(['student.user', 'exam.subject', 'exam.classRoom']);

        return response()->json($result);
    }

    // 👩‍🏫 Teacher can only update result for their subject
    public function teacherUpdate(Request $request, $id)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'obtained_marks' => 'required|integer|min:0',
        ]);

        $result = Result::with('exam.subject')->findOrFail($id);

        if ($result->exam->subject->teacher_id !== ($user->teacher->id ?? null)) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $result->update($validated);
        $result->load(['student.user', 'exam.subject', 'exam.classRoom']);

        return response()->json($result);
    }
}

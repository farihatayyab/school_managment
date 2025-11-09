<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
  public function index()
{
    $user = auth()->user();

    // 🔹 Admin can see all subjects
    if ($user->role === 'admin') {
        $subjects = Subject::with(['classRoom', 'teacher.user'])->get();
    } 
    // 🔹 Teacher can see only their own subjects
    elseif ($user->role === 'teacher') {
        $teacher = $user->teacher; // Relation in User model: hasOne(Teacher::class)
        $subjects = Subject::with(['classRoom', 'teacher.user'])
            ->where('teacher_id', $teacher->id)
            ->get();
    } 
    // 🔹 Student can only view subjects of their class
    else {
        $student = $user->student; // Relation in User model: hasOne(Student::class)
        $subjects = Subject::with(['classRoom', 'teacher.user'])
            ->where('class_id', $student->class_id)
            ->get();
    }

    $classes = ClassRoom::all();
    $teachers = Teacher::with('user')->get();

    return inertia('Subjects', [
        'subjects' => $subjects,
        'classes' => $classes,
        'teachers' => $teachers,
        'role' => $user->role,
    ]);
}

public function store(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    $validated = $request->validate([
        'subject_name' => 'required|string|max:255',
        'class_id' => 'required|exists:class_rooms,id',
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    Subject::create($validated);
    return redirect()->back()->with('success', 'Subject added successfully.');
}

public function update(Request $request, $id)
{
    $subject = Subject::findOrFail($id);
    $user = auth()->user();

    // Admin or teacher (only own subjects)
    if ($user->role === 'teacher' && $subject->teacher->user_id !== $user->id) {
        abort(403, 'Unauthorized');
    }

    $validated = $request->validate([
        'subject_name' => 'required|string|max:255',
        'class_id' => 'required|exists:class_rooms,id',
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    $subject->update($validated);
    return redirect()->back()->with('success', 'Subject updated successfully.');
}

public function destroy($id)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    Subject::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Subject deleted successfully.');
}

}

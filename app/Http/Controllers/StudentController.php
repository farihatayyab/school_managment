<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'classRoom'])->get();
        $users = User::where('role', 'student')->get();
        $classes = ClassRoom::all();

        return Inertia::render('Student', [
            'students' => $students,
            'users' => $users,
            'classes' => $classes,
            'auth' => ['user' => auth()->user()],
        ]);
    }

    // ✅ Store new student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'class_id'  => 'required|exists:class_rooms,id',
            'roll_no'   => 'required|unique:students,roll_no',
            'gender'    => 'required|in:male,female,other',
            'address'   => 'nullable|string|max:255',
            'phone' => 'required|numeric|digits_between:10,15',
         
        ], [
            'user_id.required' => 'Student user is required.',
            'user_id.exists'   => 'Selected student does not exist.',
            'class_id.required'=> 'Class is required.',
            'class_id.exists'  => 'Selected class does not exist.',
            'roll_no.required' => 'Roll number is required.',
            'roll_no.unique'   => 'This roll number is already taken.',
            'gender.required'  => 'Gender is required.',
            'gender.in'        => 'Gender must be male, female, or other.',
              'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
        ]);

        $student = Student::create($validated);
        $student = Student::with(['user', 'classRoom'])->find($student->id);

        return response()->json($student);
    }

    // ✅ Update existing student
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'class_id'  => 'required|exists:class_rooms,id',
            'roll_no'   => "required|unique:students,roll_no,{$id}",
            'gender'    => 'required|in:male,female,other',
            'address'   => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
        ], [
            'class_id.required' => 'Class is required.',
            'class_id.exists'   => 'Selected class does not exist.',
            'roll_no.required'  => 'Roll number is required.',
            'roll_no.unique'    => 'This roll number is already taken.',
            'gender.required'   => 'Gender is required.',
            'gender.in'         => 'Gender must be male, female, or other.',
        ]);

        $student = Student::findOrFail($id);
        $student->update($validated);
        $student->load(['user', 'classRoom']); // Load relations

        return response()->json($student);
    }

    // ✅ Delete student
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        $users = User::where('role', 'teacher')->get();

        return Inertia::render('Teacher', [
            'teachers' => $teachers,
            'users' => $users,
            'auth' => ['user' => auth()->user()],
        ]);
    }

    // ✅ Store new teacher
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|numeric|digits_between:10,15',
            'subject_specialization' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
        ], [
            'user_id.required' => 'User is required.',
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.digits_between' => 'Phone must be 10 to 15 digits.',
            'subject_specialization.required' => 'Subject specialization is required.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be numeric.',
        ]);

        $teacher = Teacher::create($validated);
        $teacher = Teacher::with('user')->find($teacher->id);

        return response()->json($teacher);
    }

    // ✅ Update existing teacher
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'phone' => 'required|numeric|digits_between:10,15',
            'subject_specialization' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($validated);
        $teacher->load('user');

        return response()->json($teacher);
    }

    // ✅ Delete teacher
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

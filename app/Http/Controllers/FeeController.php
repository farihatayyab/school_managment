<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'teacher') {
            // Admin & Teacher → see all students' fees
            $fees = Fee::with('student')->orderByDesc('id')->get();
        } else {
            // Student → see only own fees
            $fees = Fee::where('student_id', $user->id)->orderByDesc('id')->get();
        }

        return Inertia::render('Fees', [
            'fees' => $fees,
            'auth' => ['user' => $user],
            'students' => $user->role === 'admin' ? User::where('role', 'student')->get() : [],
        ]);
    }

  public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:users,id',
        'month' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'status' => 'required|in:paid,unpaid',
    ]);

    $fee = Fee::create($request->all());

    // 👇 student relation load karo
    $fee->load('student');

    return response()->json($fee);
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'month' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid',
        ]);

        $fee = Fee::findOrFail($id);
        $fee->update($request->all());
        $fee->load('student');
        return response()->json($fee);
    }

    public function destroy($id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

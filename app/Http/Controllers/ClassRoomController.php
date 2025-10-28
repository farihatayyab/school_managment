<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
   public function index()
    {
        $classRooms = ClassRoom::all();
        return Inertia::render('ClassRoom', [
            'classRooms' => $classRooms,
            'auth' => [
                'user' => auth()->user() // 👈 User data frontend me bhejna
            ]
        ]);
    }

    // Store new class
  public function store(Request $request)
{
    $class = new ClassRoom();
    $class->class_name = $request->class_name;
    $class->description = $request->description;
    $class->save();

    return response()->json($class);
}

    // Show specific class
    public function show($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return response()->json($classRoom);
    }

    // Update class
    public function update(Request $request, $id)
{
    // ✅ Allow only admin or teacher
    if (!in_array(auth()->user()->role, ['admin', 'teacher'])) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'class_name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $classRoom = ClassRoom::findOrFail($id);
    $classRoom->update($validated);

    return response()->json($classRoom);
}



    // Delete class
    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->delete();

        return response()->json(['message' => 'Class deleted successfully']);
    }
}

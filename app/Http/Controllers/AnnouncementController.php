<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $announcements = Announcement::orderByDesc('id')->get();
        } else {
            $announcements = Announcement::where('status', 'active')
                ->orderByDesc('id')
                ->get();
        }

        return Inertia::render('Announcements', [
            'announcements' => $announcements,
            'auth' => ['user' => $user],
        ]);
        
   
    }

  public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'date_posted' => 'required|date',
        'status' => 'required|in:active,inactive',
    ]);

    $announcement = Announcement::create($request->all());
    return response()->json($announcement);
}


    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'date_posted' => 'required|date',
        'status' => 'required|in:active,inactive',
    ]);

    $announcement = Announcement::findOrFail($id);
    $announcement->update($request->all());
    return response()->json($announcement);
}


    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of students and registered users.
     */
    public function index(Request $request)
    {
        $query = User::with('studentProfile');

        // Filter by Auth Type
        if ($request->filled('filter')) {
            if ($request->filter === 'google') {
                $query->whereNotNull('google_id');
            } elseif ($request->filter === 'standard') {
                $query->whereNull('google_id');
            }
        }

        // Filter by Membership Status
        if ($request->filled('membership_status')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('membership_status', $request->membership_status);
            });
        }

        // Search by Name, Email, Phone, or NRC
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('studentProfile', function ($profileQuery) use ($search) {
                      $profileQuery->where('nrc_number', 'like', "%{$search}%")
                                   ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.students', compact('users'));
    }

    /**
     * Display the specified student's details.
     */
    public function show($id)
    {
        // Finds student by StudentProfile ID or User ID
        $user = User::with('studentProfile')->find($id);

        if (!$user) {
            $profile = StudentProfile::with('user')->findOrFail($id);
            $user = $profile->user;
        }

        return view('admin.students.show', compact('user'));
    }

    /**
     * Show the form for editing the student record.
     */
    public function edit($id)
    {
        $user = User::with('studentProfile')->findOrFail($id);
        return view('admin.students.edit', compact('user'));
    }

    /**
     * Update the student record in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student profile updated successfully.');
    }

    /**
     * Remove the specified student and associated photo from storage.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            // First: assume the ID belongs to a StudentProfile
            $profile = StudentProfile::with('user')->find($id);

            if ($profile) {

                // Delete passport photo
                if ($profile->passport_photo) {
                    Storage::disk('public')->delete($profile->passport_photo);
                }

                // Get related user
                $user = $profile->user;

                // Delete student profile
                $profile->delete();

                // Delete related user
                if ($user) {
                    $user->delete();
                }

                return;
            }

            // If there is no StudentProfile,
            // check whether this ID belongs directly to a User.
            $user = User::find($id);

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student account deleted successfully.');
    }
}
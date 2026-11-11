<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Show the enrollment page for a specific course.
     */
    public function index($id)
    {
        $course = Course::with('category')->findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'You need an account first to enroll in a course. Please register or sign in.');
        }

        // If authenticated user already has a profile, render simple membership prompt view
        if ($user && $user->studentProfile) {
            return view('course.enroll.membership_confirm', compact('course', 'user'));
        }

        return view('course.enroll.index', compact('course'));
    }

    /**
     * Store student profile and proceed to payment.
     */
    public function store(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();

        // CASE 1: Logged-in user already has a profile -> Only update membership details
        if ($user && $user->studentProfile) {
            $validated = $request->validate([
                'membership_status' => 'required|in:member,non-member',
                'member_code'       => 'nullable|required_if:membership_status,member|string|max:100',
            ]);

            $user->studentProfile->update([
                'membership_status' => $validated['membership_status'],
                'member_code'       => $validated['membership_status'] === 'member' ? $validated['member_code'] : null,
            ]);

            return redirect()->route('payment.qr', $course->id)
                ->with('success', 'Membership updated. Please complete payment for ' . $course->title . '.');
        }

        // Pre-process phone inputs to ensure '09' prefix consistency
        if ($request->has('phone') && $request->phone) {
            $request->merge([
                'phone' => str_starts_with($request->phone, '09') ? $request->phone : '09' . ltrim($request->phone, '0'),
            ]);
        }

        if ($request->has('viber_phone') && $request->viber_phone) {
            $request->merge([
                'viber_phone' => str_starts_with($request->viber_phone, '09') ? $request->viber_phone : '09' . ltrim($request->viber_phone, '0'),
            ]);
        }

        // CASE 2: Full validation for new student profile
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'required|string|regex:/^09\d{7,9}$/',
            'viber_phone'       => 'required|string|regex:/^09\d{7,9}$/',
            'gender'            => 'required|in:male,female,other',
            'membership_status' => 'required|in:member,non-member',
            'member_code'       => 'nullable|required_if:membership_status,member|string|max:100',
            'nrc_state'         => 'required|integer|between:1,14',
            'nrc_district'      => 'required|string|max:50',
            'nrc_type'          => 'required|in:(N),(P),(E),(NRA)',
            'nrc_number'        => 'required|digits:6',
            'company'           => 'required|string|max:255',
            'job_title'         => 'required|string|max:255',
            'passport_photo'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fullNrc = $validated['nrc_state'] . '/' . $validated['nrc_district'] . $validated['nrc_type'] . $validated['nrc_number'];

        return DB::transaction(function () use ($request, $validated, $fullNrc, $course, $user) {
            $photoPath = $request->file('passport_photo')->store('passports', 'public');

            if ($user && empty($user->name)) {
                $user->update(['name' => $validated['name']]);
            }

            StudentProfile::create([
                'user_id'           => $user ? $user->id : null,
                'phone'             => $validated['phone'],
                'viber_phone'       => $validated['viber_phone'],
                'gender'            => $validated['gender'],
                'membership_status' => $validated['membership_status'],
                'member_code'       => $validated['membership_status'] === 'member' ? $validated['member_code'] : null,
                'nrc_number'        => $fullNrc,
                'company'           => $validated['company'],
                'job_title'         => $validated['job_title'],
                'passport_photo'    => $photoPath,
            ]);

            return redirect()->route('payment.qr', $course->id)
                ->with('success', 'Profile saved successfully! Please complete your payment for ' . $course->title . '.');
        });
    }
}
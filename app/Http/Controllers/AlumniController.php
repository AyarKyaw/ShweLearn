<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Mail\VerifyAlumniPendingEmail;

class AlumniController extends Controller
{
    public function showJoinForm()
    {
        $courses = Course::all();
        return view('alumni.join', compact('courses'));
    }

    /**
     * Store registration payload in session and send signed email link.
     * NO record is created in the database at this step.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name'          => 'required|string|max:255',
                'email'         => 'required|email:rfc,dns|max:255|unique:alumnis,email',
                'course_id'     => 'required|exists:courses,id',
                'password'      => 'required|string|min:8',
                'cropped_image' => 'required|string',
            ],
            [
                'email.unique' => 'This email is already registered. Please log in instead.',
            ]
        );

        $imagePath = $this->saveCroppedImage(
            $validated['cropped_image']
        );

        $alumniData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'course_id' => $validated['course_id'],
            'password'  => Hash::make($validated['password']),
            'image'     => $imagePath,
        ];

        session([
            'pending_alumni_registration' => $alumniData,
        ]);

        $this->sendConfirmationEmail($alumniData);

        return view('alumni.auth.verify-email', [
            'email' => $validated['email'],
        ]);
    }

    /**
     * Resend confirmation email using session payload.
     */
    public function resendVerificationEmail(Request $request)
    {
        $alumniData = session('pending_alumni_registration');

        if (!$alumniData) {
            return redirect()->route('alumni.join')
                ->withErrors(['email' => 'Session expired or not found. Please fill out the form again.']);
        }

        $this->sendConfirmationEmail($alumniData);

        return back()->with('message', 'A new confirmation link has been sent to ' . $alumniData['email']);
    }

    /**
     * Handle the signed link clicked from email.
     * Creates the Alumni record in DB ONLY when verified.
     */
    public function verifyPendingEmail(Request $request, $payload)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired verification link.');
        }

        try {
            $alumniData = decrypt($payload);
        } catch (\Exception $e) {
            return redirect()->route('alumni.join')
                ->withErrors(['email' => 'Invalid or expired confirmation link.']);
        }

        $existingAlumni = Alumni::where('email', $alumniData['email'])->first();

        if ($existingAlumni) {
            // If they already exist but haven't paid, send them to payment instead of dashboard
            if ($existingAlumni->status === 'pending_payment') {
                Auth::guard('alumni')->login($existingAlumni, true);
                return redirect()->route('alumni.payment.checkout')
                    ->with('message', 'Please complete your membership payment to activate your account.');
            }

            Auth::guard('alumni')->login($existingAlumni, true);
            return redirect()->route('alumni.dashboard')
                ->with('success', 'Account already verified! Welcome back.');
        }

        // 3. Create Alumni in Database with pending payment status
        $alumni = Alumni::create([
            'name'              => $alumniData['name'],
            'email'             => $alumniData['email'],
            'course_id'         => $alumniData['course_id'],
            'password'          => $alumniData['password'],
            'image'             => $alumniData['image'],
            'status'            => 'inactive', // <--- Changed from 'active'
            'email_verified_at' => now(),
        ]);

        // 4. Authenticate alumni temporarily so they can complete payment
        Auth::guard('alumni')->login($alumni, true);
        $request->session()->regenerate();
        session()->forget('pending_alumni_registration');

        // 5. Redirect to payment checkout page instead of dashboard
        return redirect()->route('alumni.payment.checkout')
            ->with('success', 'Email verified! Please complete your payment to unlock your alumni account.');
    }

    /**
     * Optional Polling check if the verification notice page is kept open on tab 1.
     */
    public function checkVerificationStatus(Request $request)
    {
        $pendingEmail = session('pending_alumni_registration.email');

        if (Auth::guard('alumni')->check()) {
            session()->forget('pending_alumni_registration');
            return response()->json([
                'confirmed' => true,
                'redirect'  => route('alumni.dashboard'),
            ]);
        }

        if ($pendingEmail) {
            $cacheKey = 'confirmed_alumni_email_' . md5($pendingEmail);
            $cachedAlumniId = Cache::get($cacheKey);

            $alumni = $cachedAlumniId 
                ? Alumni::find($cachedAlumniId) 
                : Alumni::where('email', $pendingEmail)->first();

            if ($alumni) {
                Auth::guard('alumni')->login($alumni, true);
                $request->session()->regenerate();
                session()->forget('pending_alumni_registration');
                Cache::forget($cacheKey);

                return response()->json([
                    'confirmed' => true,
                    'redirect'  => route('alumni.dashboard'),
                ]);
            }
        }

        return response()->json(['confirmed' => false]);
    }

    public function showLoginForm()
    {
        return view('alumni.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $alumni = Alumni::where('email', $credentials['email'])->first();

        if ($alumni && Hash::check($credentials['password'], $alumni->password)) {
            $remember = $request->has('remember');
            Auth::guard('alumni')->login($alumni, $remember);
            $request->session()->regenerate();

            // If they haven't paid yet, redirect them to checkout instead of the dashboard
            if ($alumni->status !== 'active') {
                return redirect()->route('alumni.payment.checkout')
                    ->with('message', 'Please complete your membership payment to activate your account.');
            }

            return redirect()->intended(route('alumni.dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our alumni records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('alumni')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('alumni.login')->with('success', 'You have been logged out.');
    }

    /**
     * Helper to send email with encrypted payload in signed link.
     */
    private function sendConfirmationEmail(array $alumniData)
    {
        $confirmationUrl = URL::temporarySignedRoute(
            'alumni.verify.pending',
            now()->addMinutes(60),
            ['payload' => encrypt($alumniData)]
        );

        Mail::to($alumniData['email'])->send(new VerifyAlumniPendingEmail($confirmationUrl));
    }

    private function saveCroppedImage(string $base64Data): string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $extension = strtolower($type[1]);
        } else {
            $extension = 'jpg';
        }

        $base64Data = str_replace(' ', '+', $base64Data);
        $imageData = base64_decode($base64Data);

        if ($imageData === false) {
            throw new \InvalidArgumentException('Invalid base64 image encoding.');
        }

        $fileName = 'alumni_' . time() . '_' . Str::random(10) . '.' . $extension;
        $imagePath = 'alumni_avatars/' . $fileName;

        Storage::disk('public')->put($imagePath, $imageData);

        return $imagePath;
    }

    public function dashboard()
    {
        $alumni = Auth::guard('alumni')->user();

        if (!$alumni) {
            return redirect()->route('alumni.login');
        }

        // If they haven't paid yet (status is inactive), force them to payment checkout
        if ($alumni->status !== 'active') {
            return redirect()->route('alumni.payment.checkout')
                ->with('message', 'Please complete your membership payment to access your dashboard.');
        }

        // Eager load course relation
        $alumni->load('course');

        return view('alumni.dashboard', compact('alumni'));
    }

    /**
     * Public Verification Route for QR Scans
     */
    public function verify($register_no)
    {
        $alumni = Alumni::with('course')->where('register_no', $register_no)->first();

        // Fallback search by standard ID if register_no hasn't migrated for an older record
        if (!$alumni && Str::startsWith($register_no, 'MEA-')) {
            $id = (int) Str::after($register_no, 'MEA-');
            $alumni = Alumni::with('course')->find($id);
        }

        if (!$alumni) {
            return view('alumni.verify', [
                'status' => 'not_found',
                'message' => 'Invalid or unverified credential ID.'
            ]);
        }

        return view('alumni.verify', [
            'alumni' => $alumni,
            'isValid' => ($alumni->status ?? 'active') === 'active',
            'status' => $alumni->status ?? 'active'
        ]);
    }

    public function status(Request $request)
    {
        $email = $request->query('email');

        \Log::info('ALUMNI VERIFICATION STATUS CHECK', [
            'email_from_request' => $email,
            'session_email' => session('pending_registration.email'),
        ]);

        if (!$email) {
            \Log::warning('ALUMNI VERIFICATION: NO EMAIL PROVIDED');

            return response()->json([
                'verified' => false,
                'message' => 'Email not provided',
            ]);
        }

        $alumni = \App\Models\Alumni::where('email', $email)->first();

        \Log::info('ALUMNI RECORD FOUND', [
            'alumni' => $alumni ? $alumni->toArray() : null,
            'email' => $email,
            'email_verified_at' => $alumni?->email_verified_at,
        ]);

        if (!$alumni) {
            \Log::warning('ALUMNI VERIFICATION: ALUMNI NOT FOUND', [
                'email' => $email,
            ]);

            return response()->json([
                'verified' => false,
                'message' => 'Alumni record not found',
            ]);
        }

        $verified = !is_null($alumni->email_verified_at);

        \Log::info('ALUMNI VERIFICATION RESULT', [
            'alumni_id' => $alumni->id,
            'email' => $alumni->email,
            'email_verified_at' => $alumni->email_verified_at,
            'verified' => $verified,
        ]);

        return response()->json([
            'verified' => $verified,
            'redirect' => route('alumni.dashboard'),
        ]);

    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Mail\ConfirmAccountMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Handle user registration via Form
    public function register(Request $request)
    {
        // 1. Validate fields (email & password only)
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Prepare payload (hashed password)
        $userData = [
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ];

        // 3. Store payload in session for resend capability
        session(['pending_registration' => $userData]);

        // 4. Send initial confirmation email
        $this->sendConfirmationEmail($userData);

        // 5. Render waiting page (NO USER CREATED IN DB YET)
        return view('auth.verify-email', ['email' => $request->email]);
    }

    // Resend confirmation link from waiting page
    public function resendConfirmation(Request $request)
    {
        $userData = session('pending_registration');

        if (!$userData) {
            return redirect()->route('register')->withErrors(['email' => 'Session expired. Please register again.']);
        }

        $this->sendConfirmationEmail($userData);

        return back()->with('success', 'A new confirmation link has been sent to ' . $userData['email']);
    }

    // Helper method to send email with signed link
    private function sendConfirmationEmail(array $userData)
    {
        $confirmationUrl = URL::temporarySignedRoute(
            'account.confirm',
            now()->addMinutes(60),
            ['payload' => encrypt($userData)]
        );

        Mail::to($userData['email'])->send(new ConfirmAccountMail($confirmationUrl));
    }

    public function handleGoogleOneTap(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'google_id' => 'required',
                'name' => 'nullable|string',
            ]);

            // Check if user already exists by email or google_id
            $user = User::where('email', $request->email)
                        ->orWhere('google_id', $request->google_id)
                        ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $request->name ?? explode('@', $request->email)[0],
                    'email' => $request->email,
                    'google_id' => $request->google_id,
                    'password' => Hash::make(Str::random(24)), // Sets dummy password if column is NOT NULL
                ]);
            } else {
                // Attach google_id if account existed without it
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $request->google_id]);
                }
            }

            Auth::login($user);

            return response()->json([
                'success' => true,
                'redirect' => route('home'), // Change 'dashboard' to your post-login route name
            ]);

        } catch (\Throwable $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Authentication failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function handleGoogleAndroid(Request $request)
    {
        try {
            $request->validate([
                'id_token' => ['required', 'string'],
            ]);

            /*
            * =====================================================
            * VERIFY GOOGLE ID TOKEN
            * =====================================================
            */

            $client = new \Google_Client([
                'client_id' => '681316627623-lb6qo0j0qd42esdp26v492rsen7rth02.apps.googleusercontent.com',
            ]);

            $payload = $client->verifyIdToken(
                $request->id_token
            );

            if (!$payload) {
                Log::warning('Android Google ID token verification failed.');

                return response()->json([
                    'success' => false,
                    'error' => 'Invalid Google ID token.',
                ], 401);
            }

            /*
            * =====================================================
            * GET VERIFIED GOOGLE DATA
            * =====================================================
            */

            $googleId = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? null;

            if (!$googleId || !$email) {
                Log::warning(
                    'Android Google token missing required data.',
                    ['payload' => $payload]
                );

                return response()->json([
                    'success' => false,
                    'error' => 'Google account information is incomplete.',
                ], 422);
            }

            /*
            * =====================================================
            * FIND USER
            * =====================================================
            */

            $user = User::where('email', $email)
                ->orWhere('google_id', $googleId)
                ->first();

            /*
            * =====================================================
            * CREATE USER
            * =====================================================
            */

            if (!$user) {

                $user = User::create([
                    'name' => $name ?: explode('@', $email)[0],
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]);

            } else {

                /*
                * Existing account without Google ID
                */

                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleId,
                    ]);
                }

                /*
                * Existing account without name
                */

                if (empty($user->name) && $name) {
                    $user->update([
                        'name' => $name,
                    ]);
                }

                /*
                * Google verified the email
                */

                if (!$user->email_verified_at) {
                    $user->update([
                        'email_verified_at' => now(),
                    ]);
                }
            }

            /*
            * =====================================================
            * LOGIN USER
            * =====================================================
            */

            Auth::login($user, true);

            $request->session()->regenerate();

            Log::info('Android Google authentication successful.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'google_id' => $googleId,
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('home'),
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Android Google Auth Error: ' . $e->getMessage(),
                ['exception' => $e]
            );

            return response()->json([
                'success' => false,
                'error' => 'Authentication failed.',
            ], 500);
        }
    }

    public function checkVerificationStatus(Request $request)
    {
        $pendingEmail = session('pending_registration.email');

        // 1. Already Logged In
        if (Auth::check()) {
            Log::info('POLL STATUS: User is already logged in on this tab.', ['user_id' => Auth::id()]);
            session()->forget('pending_registration');
            return response()->json([
                'confirmed' => true,
                'redirect'  => url('/')
            ]);
        }

        // 2. Pending Email Check
        if ($pendingEmail) {
            $cacheKey = 'confirmed_email_' . md5($pendingEmail);
            $cachedUserId = Cache::get($cacheKey);

            // Check Cache first, then DB
            $user = $cachedUserId ? User::find($cachedUserId) : User::where('email', $pendingEmail)->first();

            Log::info('POLL STATUS: Checking for confirmed user...', [
                'pending_email'  => $pendingEmail,
                'cache_key'      => $cacheKey,
                'cached_user_id' => $cachedUserId,
                'user_found'     => (bool) $user,
            ]);

            if ($user) {
                Auth::login($user, true);
                $request->session()->regenerate();
                session()->forget('pending_registration');
                Cache::forget($cacheKey);

                Log::info('POLL STATUS: SUCCESS! User found, logged in, and redirecting.', ['user_id' => $user->id]);

                return response()->json([
                    'confirmed' => true,
                    'redirect'  => url('/')
                ]);
            }
        } else {
            Log::warning('POLL STATUS: No pending_registration.email found in session!');
        }

        return response()->json(['confirmed' => false]);
    }

    // Handle standard user login attempt
    public function login(Request $request)
    {
        // 1. Validate form fields
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Check "Remember Me" checkbox
        $remember = $request->has('remember');

        // 3. Attempt to log the user in via default guard
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect to intended page
            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        // 4. Return back with error if credentials fail
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle standard user logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show Admin Login Form
     */
    public function showAdminLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle Admin Login Request
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        // Authenticate against the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard/admins')->with('success', 'Welcome back to Admin Dashboard!');
        }

        return back()->withErrors([
            'email' => 'The provided admin credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle Admin Logout
     */
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function confirmAccount(Request $request, $payload)
    {
        // 1. Decrypt payload safely
        try {
            $userData = decrypt($payload);
        } catch (\Exception $e) {
            return redirect()->route('register')->withErrors(['email' => 'Invalid or expired confirmation link.']);
        }

        // 2. Prevent duplicate user creation
        $existingUser = User::where('email', $userData['email'])->first();

        if ($existingUser) {
            Auth::login($existingUser, true);
            return redirect('/')->with('success', 'Account already confirmed. Welcome back!');
        }

        // 3. Create User in Database
        $user = User::create([
            'email'             => $userData['email'],
            'password'          => $userData['password'], // Pre-hashed in register action
            'email_verified_at' => now(),
        ]);

        // 4. Cache confirmation for polling tab check
        $cacheKey = 'confirmed_email_' . md5($userData['email']);
        Cache::put($cacheKey, $user->id, now()->addMinutes(10));

        // 5. Log user in and clean up pending registration session
        Auth::login($user, true);
        session()->forget('pending_registration');

        return redirect('/')->with('success', 'Account confirmed successfully! Welcome to MIFFA.');
    }

    public function TeachershowLogin()
    {
        return view('instructors.login');
    }

    public function Teacherlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('teacher')->attempt($credentials, $remember)) {

            $request->session()->regenerate();

            return redirect()->intended(
                route('teacher.dashboard')
            );
        }

        return back()
            ->withErrors([
                'email' => 'The email or password is incorrect.',
            ])
            ->withInput($request->only('email'));
    }

    public function Teacherlogout(Request $request)
    {
        Auth::guard('teacher')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login');
    }
}
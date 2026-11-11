<?php

use App\Models\User;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\DashboardController;
// Course Category Controllers
use App\Http\Controllers\Admin\CourseCategoryController as AdminCourseCategoryController;
use App\Http\Controllers\CourseCategoryController as FrontendCourseCategoryController;

// Course Controllers
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\CourseController as FrontendCourseController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SectionController;

// Instructor Controllers (Admin vs Frontend)
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\InstructorController as FrontendInstructorController;

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AlumniPaymentController;

use App\Http\Controllers\Admin\AlumniController as AdminAlumniController;
use App\Http\Controllers\Admin\AlumniPaymentController as AdminAlumniPaymentController;


use App\Http\Controllers\Developer\LogController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public / Frontend Routes
Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return view('about-us');
});

Route::get(
    '/alumni/verification/status',
    [AlumniController::class, 'status']
)->name('alumni.verification.status');

Route::prefix('alumni')->name('alumni.')->group(function () {

    // Public QR Verification Route
    Route::get('/verify/{register_no}', [AlumniController::class, 'verify'])
        ->name('verify');

    // Signed verification route for pending registration (MUST be outside guest middleware)
    Route::get('/verify-pending/{payload}', [AlumniController::class, 'verifyPendingEmail'])
        ->name('verify.pending');

    Route::get('/join', [AlumniController::class, 'showJoinForm'])->name('join');
    Route::post('/join', [AlumniController::class, 'store'])->name('store');

    Route::get('/login', [AlumniController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AlumniController::class, 'login'])->name('login.submit');

    Route::get('/check-verification-status', [AlumniController::class, 'checkVerificationStatus'])
        ->name('check.status');

    Route::post('/resend-verification', [AlumniController::class, 'resendVerificationEmail'])
        ->name('verification.send');

    // Authenticated Alumni Routes (Payment & Dashboard)
    Route::middleware('auth:alumni')->group(function () {
        Route::get('/payment/checkout', [AlumniPaymentController::class, 'checkout'])->name('payment.checkout');
        Route::post('/payment/process', [AlumniPaymentController::class, 'process'])->name('payment.process');
        Route::get('/dashboard', [AlumniController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AlumniController::class, 'logout'])->name('logout');
    });
});

Route::prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/login', [AuthController::class, 'TeachershowLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'Teacherlogin'])
        ->name('login.perform');

    Route::post('/logout', [AuthController::class, 'Teacherlogout'])
        ->name('logout');

    Route::get('/dashboard', [FrontendInstructorController::class, 'dashboard'])
            ->name('dashboard');

    Route::get('/homework', [FrontendInstructorController::class, 'homework'])
        ->name('homework');

    Route::get('/homework/{submission}', [FrontendInstructorController::class, 'review'])
        ->name('homework.review');
});

// Frontend Courses & Categories (Public browsing)
Route::get('/courses', [FrontendCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [FrontendCourseController::class, 'show'])->name('courses.show');
Route::get('/course/categories', [FrontendCourseCategoryController::class, 'index'])->name('course-categories.index');

// Frontend Instructors (Public browsing)
Route::get('/teachers', [FrontendInstructorController::class, 'index'])->name('instructors.index');
Route::get('/teachers/{id}', [FrontendInstructorController::class, 'show'])->name('instructors.show');

Route::post('/google-one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('google.onetap');
Route::post('/google-android', [AuthController::class, 'handleGoogleAndroid'])
    ->name('google.android');

// Guest / Authentication Routes (Students / Frontend Users)
Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');
    
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// Admin Authentication Guest Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.perform');
});

// Auth Routes (Public/Shared endpoints)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/auth/google/one-tap', [AuthController::class, 'handleGoogleOneTap'])->name('auth.google.onetap');
Route::get('/account/confirm/{payload}', [AuthController::class, 'confirmAccount'])
    ->name('account.confirm')
    ->middleware('signed');

Route::post('/account/resend', [AuthController::class, 'resendConfirmation'])
    ->name('account.resend')
    ->middleware('throttle:3,1');

Route::get('/account/check-status', [AuthController::class, 'checkVerificationStatus'])
    ->name('account.check-status');

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Notice page shown to unverified users
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Email link callback
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Your email address has been verified!');
    })->middleware('signed')->name('verification.verify');

    // Resend verification link
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Public Enrollment Routes
Route::get('/enroll/{course}', [EnrollmentController::class, 'index'])->name('enroll.index');
Route::post('/enroll/{id}', [EnrollmentController::class, 'store'])->name('enroll.store');

/*
|--------------------------------------------------------------------------
| Student / Classroom Routes (Requires Auth & Email Verification)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Payment Routes
    Route::get('/payment/qr/{course}', [PaymentController::class, 'showQr'])->name('payment.qr');
    Route::post('/payment/confirm/{course}', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/student-dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student-dashboard/courses', [DashboardController::class, 'courses'])->name('student.dashboard.courses');
    Route::get('/student-dashboard/homework', [DashboardController::class, 'homework'])->name('student.dashboard.homework');
    
    // Learning & Classroom Routes
    Route::get('/my-courses', [FrontendCourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/courses/{course}/learn/{lesson?}', [FrontendCourseController::class, 'classroom'])->name('courses.learn');
    Route::post('/courses/{course}/lessons/{lesson}/submit', [FrontendCourseController::class, 'submitQuiz'])->name('courses.lessons.submit');
    Route::get('/courses/{course}/units', [FrontendCourseController::class, 'units'])->name('courses.units');

    Route::post('/courses/{course}/lessons/{lesson}/homework', [FrontendCourseController::class, 'submitHomework'])->name('courses.homework.submit');

    // Lesson Progress / Completion Route
    Route::post('/lessons/{lesson}/complete', [FrontendCourseController::class, 'markComplete'])->name('lessons.complete');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes (Requires Admin Verification, Prefixed with /dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix('dashboard')->group(function () {

    // Admin Dashboard Home (/dashboard)
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Admin Logout (/dashboard/logout)
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

    // Admin Resource Management (/dashboard/students, /dashboard/instructors, etc.)
    Route::resource('students', StudentController::class)->names('admin.students');
    Route::resource('instructors', AdminInstructorController::class)->names('admin.instructors');
    Route::resource('admins', AdminManagementController::class)->names('admin.admins');
    Route::resource('alumni', AdminAlumniController::class)->names('admin.alumni')->parameters([
        'alumni' => 'alumni'
    ]);

    Route::resource('alumni-payments', AdminAlumniPaymentController::class)->only(['index', 'show'])->names([
    'index' => 'admin.alumni-payments.index',
    'show' => 'admin.alumni-payments.show',
]);
    
    // Admin Courses & Categories
    Route::resource('course-categories', AdminCourseCategoryController::class)->names('admin.course-categories');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('courses', AdminCourseController::class)->names('admin.courses');
    
    Route::get('courses/{course}/students', [AdminCourseController::class, 'students'])->name('admin.courses.students');
    Route::delete('courses/{course}/students/{student}', [AdminCourseController::class, 'removeStudent'])->name('admin.courses.students.remove');

    // Curriculum Management (Lessons, Units, Sections)
    Route::resource('lessons', LessonController::class)->names('admin.lessons');
    Route::get('lessons/{lesson}/questions', [LessonController::class, 'getQuestions'])->name('lessons.questions');
    Route::resource('units', UnitController::class)->names('admin.units');
    Route::resource('sections', SectionController::class)->names('admin.sections');
    Route::get('/lessons/{lesson}/submissions', [LessonController::class, 'submissions'])->name('admin.lessons.submissions');
    Route::put('/lesson-user/{pivotId}/update', [LessonController::class, 'updateSubmission'])->name('admin.lesson-user.update');
});

if (app()->environment('local')) {

    Route::get('/test-error/500', function () {
        throw new \Exception('Testing 500 error');
    });

    Route::get('/test-error/403', function () {
        abort(403);
    });

    Route::get('/test-error/404', function () {
        abort(404);
    });

    Route::get('/test-error/419', function () {
        abort(419);
    });

    Route::get('/test-error/429', function () {
        abort(429);
    });

    Route::get('/test-error/503', function () {
        abort(503);
    });

    Route::get('/test-error/type', function () {

        throw new \TypeError(
            'Testing PHP TypeError'
        );
    });
    Route::get('/test-error/default', function () {
        abort(405);
    });
    Route::get('/test-error/runtime', function () {

        throw new \RuntimeException(
            'Testing RuntimeException'
        );
    });
}

Route::prefix('developer') ->name('developer.') ->group(function () { Route::get('/logs', [LogController::class, 'index']) ->name('logs.index'); });
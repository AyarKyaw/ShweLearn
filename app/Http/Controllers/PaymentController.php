<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function showQr($courseId)
    {
        $course = Course::findOrFail($courseId);
        $orderRef = 'ORD-' . strtoupper(uniqid());

        return view('course.payment.qr', compact('course', 'orderRef'));
    }

    public function confirmPayment(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            // Safely retrieve student profile id
            $studentId = optional($user->studentProfile)->id 
                ?? optional($user->student)->id 
                ?? $user->student_id 
                ?? null;

            DB::transaction(function () use ($user, $studentId, $course, $request) {
                CoursePayment::create([
                    'user_id' => $user->id,
                    'student_id' => $studentId,
                    'course_id' => $course->id,
                    'amount' => $course->price ?? 0.00,
                    'currency' => 'MMK',
                    'payment_method' => $request->input('payment_method', 'KBZPay'),
                    'transaction_id' => 'CPAY-' . strtoupper(uniqid()),
                    'status' => 'success',
                    'gateway_response' => $request->all(),
                ]);

                // Ensure the relation exists on User model before syncing
                if (method_exists($user, 'courses')) {
                    $user->courses()->syncWithoutDetaching([$course->id]);
                }
            });

            return response()->json([
                'status' => 'success',
                'redirect' => route('courses.my')
            ]);

        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
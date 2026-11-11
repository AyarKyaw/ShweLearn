<?php

namespace App\Http\Controllers;

use App\Models\AlumniPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlumniPaymentController extends Controller
{
    public function checkout()
    {
        $alumni = Auth::guard('alumni')->user();

        if ($alumni && $alumni->status === 'active') {
            return redirect()->route('alumni.dashboard');
        }

        return view('alumni.payment.checkout');
    }

    public function process(Request $request)
    {
        $alumni = Auth::guard('alumni')->user();

        if (!$alumni) {
            return redirect()->route('alumni.login');
        }

        DB::transaction(function () use ($alumni, $request) {
            // 1. Record the payment log
            AlumniPayment::create([
                'alumni_id' => $alumni->id,
                'amount' => 100000.00, // Adjust to your membership fee
                'currency' => 'MMK',
                'payment_method' => $request->input('payment_method', 'KBZPay'),
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'success',
                'gateway_response' => $request->all(),
            ]);

            // 2. Activate the alumni account
            $alumni->update([
                'status' => 'active',
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'redirect' => route('alumni.dashboard')
            ]);
        }

        return redirect()->route('alumni.dashboard')
            ->with('success', 'Payment successful! Your account is now active.');
    }
}
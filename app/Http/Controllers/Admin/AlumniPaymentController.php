<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniPayment;
use Illuminate\Http\Request;

class AlumniPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = AlumniPayment::with('alumni')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }

        $payments = $query->paginate(10)->appends($request->query());

        return view('dashboard.alumni.payment', compact('payments'));
    }

    public function show($id)
    {
        $payment = AlumniPayment::with('alumni')->findOrFail($id);

        return view('dashboard.alumni.show', compact('payment'));
    }
}
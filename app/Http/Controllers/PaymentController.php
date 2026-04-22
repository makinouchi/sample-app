<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        return Payment::create([
            'from_user' => $request->from_user,
            'to_user' => $request->to_user,
            'amount' => $request->amount,
            'registered_at' => $request->registered_at,
            'description' => $request->description,
            'is_processed' => $request->is_processed,
        ]);
    }

    public function index()
    {
        return Payment::orderBy('id', 'desc')->get();
    }
}

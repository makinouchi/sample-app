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

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'unprocessed');

        $query = Payment::orderBy('id', 'desc');

        // フィルタリングのロジック
        if ($filter === 'processed') {
            // 処理済みの支払いのみを取得
            $query->where('is_processed', true);
        } elseif ($filter === 'unprocessed') {
            // 未処理の支払いのみを取得
            $query->where('is_processed', false);
        }

        return $query->get();
    }

    public function markProcessed(Request $request)
    {
        $ids = $request->ids;
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No ids provided'], 400);
        }
        $count = Payment::whereIn('id', $ids)->update(['is_processed' => true]);
        return response()->json(['message' => 'Updated', 'count' => $count]);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        if ($request->has('is_processed')) {
            $payment->is_processed = $request->is_processed;
            $payment->save();
        }
        return $payment;
    }
}

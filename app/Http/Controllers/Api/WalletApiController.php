<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class WalletApiController extends Controller
{
    /**
     * Adjust user balance (admin only)
     * 
     * POST /api/v1/wallet/adjust
     * {
     *   "user_id": 1,
     *   "amount": 100000,
     *   "type": "deposit|withdraw|admin_adjust",
     *   "note": "Nạp tiền qua chuyển khoản"
     * }
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:deposit,withdraw,admin_adjust'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::findOrFail($request->user_id);
        $amount = (float) $request->amount;
        $type = $request->type;
        $note = $request->note ?? '';

        // For withdraw, make amount negative
        if ($type === 'withdraw') {
            $amount = -abs($amount);
            
            // Check if user has enough balance
            if ($user->balance < abs($amount)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Số dư không đủ',
                ], 400);
            }
        }

        $description = match ($type) {
            'deposit' => 'Nạp tiền' . ($note ? ": {$note}" : ''),
            'withdraw' => 'Rút tiền' . ($note ? ": {$note}" : ''),
            'admin_adjust' => 'Điều chỉnh bởi Admin' . ($note ? ": {$note}" : ''),
            default => $note ?: 'Giao dịch',
        };

        $transaction = $user->addBalance(
            $amount,
            $type,
            $description,
            null,
            $note,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'new_balance' => $user->balance,
            'formatted_balance' => $user->formatted_balance,
        ]);
    }

    /**
     * Get current user balance
     */
    public function balance()
    {
        $user = auth()->user();

        return response()->json([
            'balance' => $user->balance,
            'formatted_balance' => $user->formatted_balance,
        ]);
    }
}

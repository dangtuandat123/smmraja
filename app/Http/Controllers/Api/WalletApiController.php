<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\Setting;
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

    /**
     * Adjust user balance with API Key authentication
     * This is for webhook/automation - no login required
     * 
     * POST /api/v2/wallet/adjust
     * Headers: X-API-Key: your-api-key
     * Body: {
     *   "user_id": 1,           // or "email": "user@example.com"
     *   "amount": 100000,
     *   "type": "deposit|withdraw|admin_adjust",
     *   "note": "Nạp tiền qua chuyển khoản"
     * }
     */
    public function adjustWithApiKey(Request $request)
    {
        // Validate API Key - read from database first, fallback to env
        $apiKey = $request->header('X-API-Key') ?? $request->get('api_key');
        $validApiKey = Setting::get('wallet_api_key') ?: config('services.wallet_api.key', env('WALLET_API_KEY'));

        if (empty($validApiKey) || $apiKey !== $validApiKey) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API Key',
            ], 401);
        }

        // Validate request
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'type' => ['required', 'in:deposit,withdraw,admin_adjust'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        // Find user by ID or email
        $user = null;
        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
        } elseif ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'User not found. Provide user_id or email.',
            ], 404);
        }

        $amount = (float) $request->amount;
        $type = $request->type;
        $note = $request->note ?? '';

        // For withdraw, make amount negative and check balance
        if ($type === 'withdraw') {
            $amount = -abs($amount);
            
            if ($user->balance < abs($amount)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Insufficient balance',
                    'current_balance' => $user->balance,
                ], 400);
            }
        }

        $description = match ($type) {
            'deposit' => 'Nạp tiền' . ($note ? ": {$note}" : ''),
            'withdraw' => 'Rút tiền' . ($note ? ": {$note}" : ''),
            'admin_adjust' => 'Điều chỉnh' . ($note ? ": {$note}" : ''),
            default => $note ?: 'Giao dịch',
        };

        $transaction = $user->addBalance(
            $amount,
            $type,
            $description,
            null,
            $note,
            null
        );

        // Send notification for deposits
        if ($type === 'deposit') {
            Notification::depositSuccess($user->id, abs($amount));
        }

        return response()->json([
            'success' => true,
            'message' => 'Balance updated successfully',
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'amount' => $amount,
            'type' => $type,
            'old_balance' => $transaction->balance_before,
            'new_balance' => $transaction->balance_after,
            'formatted_balance' => number_format($transaction->balance_after, 0, ',', '.') . ' VND',
        ]);
    }
}

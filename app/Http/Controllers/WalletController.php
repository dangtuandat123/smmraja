<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display wallet/deposit page with QR code
     */
    public function index()
    {
        $user = auth()->user();
        
        // Build VietQR URL
        $bankId = config('services.vietqr.bank_id');
        $accountNumber = config('services.vietqr.account_number');
        $accountName = config('services.vietqr.account_name');
        $template = config('services.vietqr.template');
        
        $transferContent = "TTGR {$user->id} NAP";
        
        $qrUrl = sprintf(
            'https://api.vietqr.io/image/%s-%s-%s.jpg?accountName=%s&addInfo=%s',
            $bankId,
            $accountNumber,
            $template,
            urlencode($accountName),
            urlencode($transferContent)
        );

        $recentTransactions = Transaction::forUser($user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('wallet.index', compact('user', 'qrUrl', 'transferContent', 'recentTransactions', 'accountNumber', 'accountName'));
    }

    /**
     * Display transaction history
     */
    public function history(Request $request)
    {
        $type = $request->get('type');
        
        $query = Transaction::forUser(auth()->id())
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        $transactions = $query->paginate(20);

        $typeCounts = [
            'all' => Transaction::forUser(auth()->id())->count(),
            'deposit' => Transaction::forUser(auth()->id())->where('type', 'deposit')->count(),
            'order' => Transaction::forUser(auth()->id())->where('type', 'order')->count(),
            'refund' => Transaction::forUser(auth()->id())->where('type', 'refund')->count(),
        ];

        return view('wallet.history', compact('transactions', 'type', 'typeCounts'));
    }
}

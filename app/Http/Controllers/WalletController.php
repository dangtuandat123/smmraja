<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display wallet/deposit page with QR code
     */
    public function index()
    {
        $user = auth()->user();
        
        // Build VietQR URL - read from database first, fallback to config
        $bankId = Setting::get('vietqr_bank_id') ?: config('services.vietqr.bank_id');
        $accountNumber = Setting::get('vietqr_account_number') ?: config('services.vietqr.account_number');
        $accountName = Setting::get('vietqr_account_name') ?: config('services.vietqr.account_name');
        $template = Setting::get('vietqr_template') ?: config('services.vietqr.template', 'rdXzPHV');
        
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

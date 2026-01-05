<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display users list
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');

        $query = User::withCount('orders')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        $user->loadCount('orders');
        
        $recentOrders = $user->orders()
            ->with('service')
            ->latest()
            ->limit(10)
            ->get();

        $recentTransactions = $user->transactions()
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->sum('total_price'),
            'total_deposited' => $user->transactions()->where('type', 'deposit')->sum('amount'),
        ];

        return view('admin.users.show', compact('user', 'recentOrders', 'recentTransactions', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:user,admin'],
            'is_active' => ['boolean'],
            'password' => ['nullable', 'min:6'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được cập nhật!');
    }

    /**
     * Adjust user balance
     */
    public function adjustBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:deposit,withdraw,admin_adjust'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $amount = (float) $request->amount;
        $type = $request->type;
        $note = $request->note ?? '';

        if ($type === 'withdraw') {
            $amount = -abs($amount);
            
            if ($user->balance < abs($amount)) {
                return back()->withErrors(['amount' => 'Số dư không đủ để trừ.']);
            }
        }

        $description = match ($type) {
            'deposit' => 'Admin nạp tiền' . ($note ? ": {$note}" : ''),
            'withdraw' => 'Admin trừ tiền' . ($note ? ": {$note}" : ''),
            'admin_adjust' => 'Điều chỉnh' . ($note ? ": {$note}" : ''),
            default => $note ?: 'Giao dịch',
        };

        $user->addBalance($amount, $type, $description, null, $note, auth()->id());

        return back()->with('success', 'Số dư đã được cập nhật!');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Không thể xóa tài khoản của chính bạn.']);
        }

        if ($user->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Không thể xóa người dùng có đơn hàng.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa!');
    }
}

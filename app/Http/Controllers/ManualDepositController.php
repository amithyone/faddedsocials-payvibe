<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualDepositController extends Controller
{
    public function showLoginForm()
    {
        return view('manual-deposit.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $expected = env('MANUAL_DEPOSIT_PANEL_PASSWORD');

        if (!$expected || !hash_equals($expected, $request->input('password'))) {
            return back()
                ->withErrors(['password' => 'Invalid password'])
                ->withInput();
        }

        $request->session()->put('manual_deposit_panel_authenticated', true);

        return redirect()->route('deposit.pending.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('manual_deposit_panel_authenticated');

        return redirect()->route('deposit.pending.login.form');
    }

    public function index(Request $request)
    {
        $pageTitle = 'Pending Deposits (Manual Approval)';

        $query = Deposit::with('user', 'gateway')
            ->where('status', Status::PAYMENT_PENDING);

        $search = trim((string) $request->input('search'));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('trx', 'LIKE', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhere('account_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $deposits = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('manual-deposit.index', compact('pageTitle', 'deposits', 'search'));
    }

    public function approve(Request $request, $id)
    {
        $deposit = Deposit::with('user')
            ->where('id', $id)
            ->where('status', Status::PAYMENT_PENDING)
            ->firstOrFail();

        DB::transaction(function () use ($deposit) {
            $user = User::lockForUpdate()->find($deposit->user_id);

            if (!$user) {
                abort(404);
            }

            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user->balance = $user->balance + $deposit->amount;
            $user->save();
        });

        return redirect()
            ->route('deposit.pending.index', ['search' => $request->input('search')])
            ->with('notify', [['success', 'Deposit approved and user balance updated']]);
    }
}


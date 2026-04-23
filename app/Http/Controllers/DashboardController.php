<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $now = Carbon::now();

        $query = Transaction::query();

        if ($period === 'today') {
            $query->whereDate('created_at', $now->today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
        } else {
            $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $totalIncome = Transaction::where('type', 'income')->sum('base_amount_uzs');
        $totalExpense = Transaction::where('type', 'expense')->sum('base_amount_uzs');
        $totalBalance = $totalIncome - $totalExpense;

        $stats = [
            'income' => $transactions->where('type', 'income'),
            'expense' => $transactions->where('type', 'expense'),
            'debt' => $transactions->where('type', 'debt'),
        ];

        return view('dashboard', compact('transactions', 'totalBalance', 'stats', 'period'));
    }
}

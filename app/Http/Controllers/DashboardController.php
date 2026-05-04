<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user         = $request->user();
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Single query for all monthly summary figures
        $monthlySummary = $user->transactions()
            ->whereMonth('occurred_at', $currentMonth)
            ->whereYear('occurred_at', $currentYear)
            ->selectRaw("
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expenses,
                SUM(amount) as net
            ")
            ->first();

        $monthlyIncome   = $monthlySummary->income   ?? 0;
        $monthlyExpenses = $monthlySummary->expenses ?? 0;
        $netBalance      = $monthlySummary->net      ?? 0;

        // Total all-time transaction count
        $totalTransactions = $user->transactions()->count();

        // 5 most recent — eager load category to avoid N+1
        $recentTransactions = $user->transactions()
            ->with('category')
            ->latest('occurred_at')
            ->take(5)
            ->get();

        // Top 5 categories by activity this month
        $categoryBreakdown = $user->categories()
            ->withSum(['transactions as monthly_total' => fn($q) =>
                $q->whereMonth('occurred_at', $currentMonth)
                  ->whereYear('occurred_at', $currentYear)
            ], 'amount')
            ->having('monthly_total', '!=', 0)
            ->orderByRaw('ABS(monthly_total) DESC')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'monthlyIncome',
            'monthlyExpenses',
            'netBalance',
            'totalTransactions',
            'recentTransactions',
            'categoryBreakdown',
        ));
    }
}
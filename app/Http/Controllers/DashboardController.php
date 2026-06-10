<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\TenantProfile;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::user()->load([
            'tenantProfile.room',
            'payments' => fn ($q) => $q->orderByDesc('due_date'),
            'maintenanceRequests' => fn ($q) => $q->orderByDesc('submitted_at'),
            'notifications' => fn ($q) => $q->orderByDesc('created_at'),
            'activityLogs' => fn ($q) => $q->orderByDesc('activity_date'),
        ]);

        $currentBill = $user->payments->firstWhere('status', 'pending');
        $paymentHistory = $user->payments->where('status', 'paid');
        $pendingMaintenanceCount = $user->maintenanceRequests->where('status', 'pending')->count();
        $recentNotifications = $user->notifications->take(3);

        return view('dashboard.user.user', compact(
            'user',
            'currentBill',
            'paymentHistory',
            'pendingMaintenanceCount',
            'recentNotifications'
        ));
    }

    public function adminDashboard()
    {
        $rooms = Room::with('tenantProfiles.user')
            ->orderBy('number')
            ->get();

        $totalRooms = $rooms->count();
        $occupiedRooms = $rooms->where('status', 'occupied')->count();

        $roomStats = [
            'total' => $totalRooms,
            'occupied' => $occupiedRooms,
            'available' => $rooms->where('status', 'available')->count(),
            'maintenance' => $rooms->where('status', 'maintenance')->count(),
            'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0,
        ];

        $tenants = TenantProfile::with([
            'user.payments' => fn ($q) => $q->orderByDesc('due_date'),
            'room',
        ])
            ->whereHas('user', fn ($q) => $q->where('role', 'user'))
            ->latest()
            ->get();

        $overdueTenantCount = $tenants->filter(function (TenantProfile $tenant) {
            return $tenant->user?->payments
                ->where('status', 'pending')
                ->where('due_date', '<', now()->startOfDay())
                ->isNotEmpty();
        })->count();

        $tenantStats = [
            'total' => $tenants->count(),
            'active' => $tenants->count() - $overdueTenantCount,
            'overdue' => $overdueTenantCount,
            'new_this_month' => $tenants->filter(function (TenantProfile $tenant) {
                $date = $tenant->lease_start ?? $tenant->created_at;

                return $date && $date->isSameMonth(now());
            })->count(),
        ];

        $availableRooms = Room::where('status', 'available')
            ->orderBy('number')
            ->get();

        $payments = Payment::with('user.tenantProfile.room')
            ->orderByDesc('due_date')
            ->get();

        $paidPayments = $payments->where('status', 'paid');
        $pendingPayments = $payments->where('status', 'pending');
        $totalIncome = (float) $paidPayments->sum('amount');
        $totalExpense = 0.0;

        $paymentMonths = $payments
            ->map(fn (Payment $payment) => $payment->due_date?->copy()->startOfMonth())
            ->filter()
            ->unique(fn ($date) => $date->format('Y-m'))
            ->sort()
            ->take(-6)
            ->values();

        if ($paymentMonths->isEmpty()) {
            $paymentMonths = collect(range(5, 0))
                ->map(fn ($offset) => now()->copy()->subMonths($offset)->startOfMonth());
        }

        $monthlyFinance = $paymentMonths->map(function ($month) use ($payments) {
            $monthPayments = $payments->filter(fn (Payment $payment) => $payment->due_date?->isSameMonth($month));

            return [
                'label' => $month->format('M Y'),
                'income' => (float) $monthPayments->where('status', 'paid')->sum('amount'),
                'pending' => (float) $monthPayments->where('status', 'pending')->sum('amount'),
            ];
        });

        $maxMonthlyAmount = max(1, (float) $monthlyFinance->max(fn ($month) => max($month['income'], $month['pending'])));

        $financeStats = [
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'net' => $totalIncome - $totalExpense,
            'pending' => (float) $pendingPayments->sum('amount'),
            'paid_count' => $paidPayments->count(),
        ];

        $recentTransactions = $payments->take(10);

        return view('dashboard.admin.admin', compact(
            'rooms',
            'roomStats',
            'tenants',
            'tenantStats',
            'availableRooms',
            'financeStats',
            'monthlyFinance',
            'maxMonthlyAmount',
            'recentTransactions'
        ));
    }
}

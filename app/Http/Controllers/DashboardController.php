<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Room;
use App\Models\TenantProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::user()->load([
            'tenantProfile.room',
            'maintenanceRequests' => fn ($q) => $q->orderByDesc('submitted_at'),
            'notifications' => fn ($q) => $q->orderByDesc('created_at'),
            'activityLogs' => fn ($q) => $q->orderByDesc('activity_date'),
        ]);

        // Payments: load all, lalu pisahkan di memori
        $allPayments = Payment::where('user_id', $user->id)
            ->orderByDesc('due_date')
            ->get();

        // Hanya tagihan yang BELUM lunas (pending/overdue)
        $currentBill = $allPayments->firstWhere('status', 'pending');
        $pendingBills = $allPayments->where('status', 'pending');

        // Riwayat tagihan yang sudah LUNAS
        $paymentHistory = $allPayments->where('status', 'paid')->values();

        $pendingMaintenanceCount = $user->maintenanceRequests->where('status', 'pending')->count();
        $recentNotifications = $user->notifications->take(3);

        // Booking stats
        $bookings = Booking::where('user_id', $user->id)
            ->with('room')
            ->orderByDesc('created_at')
            ->get();
        $recentBookings = $bookings->take(5);

        // Bookings untuk halaman Pembayaran (riwayat dari Booking + Midtrans)
        $userBookingsForPayment = $bookings;

        // Tenant info (penyewaan aktif)
        $tenant = $user->tenantProfile;
        $isTenantActive = $tenant && $tenant->status === 'Aktif';
        $tenantRoom = $tenant?->room;

        return view('dashboard.user.user', compact(
            'user',
            'currentBill',
            'pendingBills',
            'paymentHistory',
            'allPayments',
            'pendingMaintenanceCount',
            'recentNotifications',
            'bookings',
            'recentBookings',
            'userBookingsForPayment',
            'tenant',
            'isTenantActive',
            'tenantRoom'
        ));
    }

    /**
     * User bookings list page.
     */
    public function userBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['room', 'tenantProfile'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.user.bookings', compact('bookings'));
    }

    /**
     * User booking detail page.
     */
    public function userBookingDetail($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['room', 'tenantProfile'])
            ->findOrFail($id);

        return view('dashboard.user.booking-detail', compact('booking'));
    }

    public function adminDashboard(Request $request)
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

        $availableUsers = User::where('role', 'user')
            ->whereDoesntHave('tenantProfile')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Keep Payment-based data for backward compatibility with tenant/activity sections
        $payments = Payment::with('user.tenantProfile.room')
            ->orderByDesc('due_date')
            ->get();

        $paidPayments = $payments->where('status', 'paid');
        $pendingPayments = $payments->where('status', 'pending');
        $totalIncome = (float) $paidPayments->sum('amount');
        $totalExpense = 0.0;

        $financeStats = [
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'net' => $totalIncome - $totalExpense,
            'pending' => (float) $pendingPayments->sum('amount'),
            'paid_count' => $paidPayments->count(),
        ];

        $recentTransactions = $payments->take(10);

        // ========================
        // ALL BOOKINGS (Chart + Stats + Table + Aktivitas)
        // ========================
        $allBookings = Booking::with(['user', 'room'])
            ->orderByDesc('created_at')
            ->get();

        // ========================
        // BOOKING REVIEW — booking yang perlu persetujuan Admin
        // ========================
        // 1. TenantProfile dengan status Menunggu Persetujuan (dari Midtrans settlement atau cash login)
        $tenantsForReview = TenantProfile::with(['user', 'room', 'booking'])
            ->where('status', 'Menunggu Persetujuan')
            ->orderByDesc('created_at')
            ->get();

        // 2. Booking cash yang Dibayar tapi belum punya TenantProfile (guest/no login)
        $cashBookingsForReview = Booking::with(['user', 'room'])
            ->where('status', 'Dibayar')
            ->where('payment_method', 'cash')
            ->whereDoesntHave('tenantProfile')
            ->orderByDesc('created_at')
            ->get();

        $pendingReviewCount = $tenantsForReview->count() + $cashBookingsForReview->count();

        // ========================
        // AKTIVITAS TERKINI — dari Booking + Tenant (realtime)
        // ========================
        $recentActivities = $this->buildRecentActivities($allBookings);

        $upcomingPayments = $payments
            ->where('status', 'pending')
            ->sortBy('due_date')
            ->take(5)
            ->values();

        // ========================
        // MONTHLY REVENUE CHART (dari Midtrans Bookings)
        // ========================
        $months = collect(range(5, 0))
            ->map(fn ($offset) => now()->copy()->subMonths($offset)->startOfMonth());

        $monthlyFinance = $months->map(function ($month) use ($allBookings) {
            $monthBookings = $allBookings->filter(fn (Booking $b) => $b->created_at->isSameMonth($month));
            $paidInMonth = $monthBookings->where('status', 'Dibayar');
            $pendingInMonth = $monthBookings->whereIn('status', ['Pending', 'Menunggu Pembayaran']);

            return [
                'label' => $month->format('M Y'),
                'income' => (float) ($paidInMonth->sum('gross_amount') ?: $paidInMonth->sum('room_price')),
                'pending' => (float) ($pendingInMonth->sum('gross_amount') ?: $pendingInMonth->sum('room_price')),
            ];
        });

        $maxMonthlyAmount = max(1, (float) $monthlyFinance->max(fn ($m) => max($m['income'], $m['pending'])));

        $messages = Message::orderByDesc('created_at')
            ->take(5)
            ->get();

        // ========================
        // BOOKING STATS (dari $allBookings)
        // ========================

        $bookingStats = [
            'total' => $allBookings->count(),
            'menunggu_pembayaran' => $allBookings->where('status', 'Menunggu Pembayaran')->count(),
            'pending' => $allBookings->where('status', 'Pending')->count(),
            'dibayar' => $allBookings->where('status', 'Dibayar')->count(),
            'gagal' => $allBookings->whereIn('status', ['Gagal', 'Ditolak', 'Kadaluarsa', 'Refund'])->count(),
            'dibatalkan' => $allBookings->where('status', 'Dibatalkan')->count(),
        ];

        // Revenue stats from bookings (settlement/capture only)
        $paidBookings = $allBookings->where('status', 'Dibayar');
        $revenueStats = [
            'total' => $paidBookings->sum('gross_amount') ?: $paidBookings->sum('room_price'),
            'today' => $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isToday())->sum('gross_amount') ?: $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isToday())->sum('room_price'),
            'this_month' => $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isSameMonth(now()))->sum('gross_amount') ?: $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isSameMonth(now()))->sum('room_price'),
            'this_year' => $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isSameYear(now()))->sum('gross_amount') ?: $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isSameYear(now()))->sum('room_price'),
        ];

        // ========================
        // FILTERED BOOKINGS (untuk tab Laporan Keuangan)
        // ========================
        $filter = $request->get('filter');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $filteredQuery = Booking::with('room')->orderByDesc('created_at');

        if ($startDate && $endDate) {
            $filteredQuery->whereDate('created_at', '>=', $startDate)
                          ->whereDate('created_at', '<=', $endDate);
        } elseif ($filter) {
            switch ($filter) {
                case 'today':
                    $filteredQuery->whereDate('created_at', today());
                    break;
                case 'week':
                    $filteredQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $filteredQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $filteredQuery->whereYear('created_at', now()->year);
                    break;
            }
        }

        $filteredBookings = $filteredQuery->get();

        // Filtered stats
        $filteredPaid = $filteredBookings->where('status', 'Dibayar');
        $filteredBookingStats = [
            'total' => $filteredBookings->count(),
            'dibayar' => $filteredBookings->where('status', 'Dibayar')->count(),
            'pending' => $filteredBookings->where('status', 'Pending')->count(),
            'dibatalkan' => $filteredBookings->where('status', 'Dibatalkan')->count(),
        ];
        $filteredRevenueStats = [
            'total' => $filteredPaid->sum('gross_amount') ?: $filteredPaid->sum('room_price'),
        ];

        // Recent payment notifications for admin
        $paymentNotifications = Notification::where('type', 'payment_success')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard.admin.admin', compact(
            'rooms',
            'roomStats',
            'tenants',
            'tenantStats',
            'availableRooms',
            'availableUsers',
            'financeStats',
            'monthlyFinance',
            'maxMonthlyAmount',
            'recentTransactions',
            'recentActivities',
            'upcomingPayments',
            'messages',
            'bookingStats',
            'revenueStats',
            'allBookings',
            'paymentNotifications',
            'filteredBookings',
            'filteredBookingStats',
            'filteredRevenueStats',
            'filter',
            'startDate',
            'endDate',
            'tenantsForReview',
            'cashBookingsForReview',
            'pendingReviewCount'
        ));
    }

    /**
     * Bangun aktivitas terkini dari data Booking (realtime).
     */
    private function buildRecentActivities($allBookings): array
    {
        $activities = [];

        foreach ($allBookings as $booking) {
            $bookingNumber = 'BK-' . $booking->created_at->format('Ymd') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);

            // Booking baru dibuat
            $activities[] = [
                'type' => 'booking_created',
                'icon' => 'fa-shopping-cart',
                'icon_bg' => '#DBEAFE',
                'icon_color' => '#1D4ED8',
                'title' => 'Booking Baru',
                'description' => "{$booking->customer_name} membuat booking {$booking->room_name} ({$bookingNumber})",
                'status' => $booking->status,
                'timestamp' => $booking->created_at,
            ];

            // Pembayaran berhasil
            if ($booking->status === 'Dibayar' && $booking->paid_at) {
                $formattedAmount = 'Rp ' . number_format($booking->gross_amount ?: $booking->room_price, 0, ',', '.');
                $activities[] = [
                    'type' => 'payment_success',
                    'icon' => 'fa-check-circle',
                    'icon_bg' => '#D1FAE5',
                    'icon_color' => '#065F46',
                    'title' => 'Pembayaran Berhasil',
                    'description' => "{$booking->customer_name} membayar {$formattedAmount} untuk {$booking->room_name}",
                    'status' => $booking->status,
                    'timestamp' => $booking->paid_at,
                ];
            }

            // Pembayaran pending
            if ($booking->status === 'Pending') {
                $activities[] = [
                    'type' => 'payment_pending',
                    'icon' => 'fa-clock',
                    'icon_bg' => '#FEF3C7',
                    'icon_color' => '#92400E',
                    'title' => 'Menunggu Pembayaran',
                    'description' => "Pembayaran {$booking->customer_name} untuk {$booking->room_name} masih pending",
                    'status' => $booking->status,
                    'timestamp' => $booking->updated_at ?? $booking->created_at,
                ];
            }

            // Booking dibatalkan / gagal
            if (in_array($booking->status, ['Dibatalkan', 'Gagal', 'Ditolak', 'Kadaluarsa', 'Refund'])) {
                $activities[] = [
                    'type' => 'booking_failed',
                    'icon' => 'fa-times-circle',
                    'icon_bg' => '#FEE2E2',
                    'icon_color' => '#991B1B',
                    'title' => 'Booking ' . $booking->status,
                    'description' => "Booking {$booking->customer_name} untuk {$booking->room_name} {$booking->status}",
                    'status' => $booking->status,
                    'timestamp' => $booking->updated_at ?? $booking->created_at,
                ];
            }
        }

        // Urutkan berdasarkan timestamp terbaru
        usort($activities, function ($a, $b) {
            return $b['timestamp']->timestamp <=> $a['timestamp']->timestamp;
        });

        // Ambil 8 aktivitas terbaru
        return array_slice($activities, 0, 8);
    }

    /**
     * Admin transactions page.
     */
    public function adminTransactions(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Booking::with(['user', 'room'])->orderByDesc('created_at');

        // Apply date filter
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } else {
            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $transactions = $query->get();

        // Summary
        $totalTransactions = $transactions->count();
        $totalRevenue = $transactions->where('status', 'Dibayar')->sum('gross_amount') ?: $transactions->where('status', 'Dibayar')->sum('room_price');

        return view('dashboard.admin.transactions', compact(
            'transactions',
            'filter',
            'startDate',
            'endDate',
            'totalTransactions',
            'totalRevenue'
        ));
    }

    /**
     * Admin reports page with charts data.
     */
    public function adminReports()
    {
        $allBookings = Booking::with('room')->orderByDesc('created_at')->get();
        $paidBookings = $allBookings->where('status', 'Dibayar');

        // Revenue per month
        $monthlyRevenue = collect(range(0, 11))
            ->map(function ($i) use ($paidBookings) {
                $month = now()->copy()->subMonths($i);
                $monthRevenue = $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isSameMonth($month))->sum('gross_amount') ?: $paidBookings->filter(fn ($b) => $b->paid_at && $b->paid_at->isSameMonth($month))->sum('room_price');

                return [
                    'month' => $month->format('M Y'),
                    'revenue' => (float) $monthRevenue,
                ];
            })
            ->reverse()
            ->values();

        // Bookings per month
        $monthlyBookings = collect(range(0, 11))
            ->map(function ($i) use ($allBookings) {
                $month = now()->copy()->subMonths($i);
                $count = $allBookings->filter(fn ($b) => $b->created_at->isSameMonth($month))->count();

                return [
                    'month' => $month->format('M Y'),
                    'count' => $count,
                ];
            })
            ->reverse()
            ->values();

        // Status distribution
        $statusDistribution = [
            'Dibayar' => $allBookings->where('status', 'Dibayar')->count(),
            'Pending' => $allBookings->where('status', 'Pending')->count(),
            'Menunggu Pembayaran' => $allBookings->where('status', 'Menunggu Pembayaran')->count(),
            'Gagal' => $allBookings->whereIn('status', ['Gagal', 'Ditolak', 'Kadaluarsa'])->count(),
            'Dibatalkan' => $allBookings->where('status', 'Dibatalkan')->count(),
        ];

        // Revenue totals
        $totalRevenue = $paidBookings->sum('gross_amount') ?: $paidBookings->sum('room_price');
        $totalTransactions = $allBookings->count();

        return view('dashboard.admin.reports', compact(
            'monthlyRevenue',
            'monthlyBookings',
            'statusDistribution',
            'totalRevenue',
            'totalTransactions'
        ));
    }
}
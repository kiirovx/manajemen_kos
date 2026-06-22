<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export data to PDF.
     */
    public function exportPdf($type, Request $request)
    {
        $data = $this->getDataForExport($type, $request);
        $title = $this->getExportTitle($type);
        $view = match($type) {
            'payments' => 'dashboard.admin.export.payments-pdf',
            'bookings' => 'dashboard.admin.export.bookings-pdf',
            'revenue' => 'dashboard.admin.export.revenue-pdf',
            default => abort(404),
        };

        return view($view, [
            'data' => $data,
            'title' => $title,
            'date' => now()->format('d M Y H:i'),
        ]);
    }

    /**
     * Export data to Excel (HTML table with .xls extension).
     */
    public function exportExcel($type, Request $request)
    {
        $data = $this->getDataForExport($type, $request);
        $title = $this->getExportTitle($type);

        $view = match($type) {
            'payments' => 'dashboard.admin.export.payments-excel',
            'bookings' => 'dashboard.admin.export.bookings-excel',
            'revenue' => 'dashboard.admin.export.revenue-excel',
            default => abort(404),
        };

        return response(view($view, [
            'data' => $data,
            'title' => $title,
            'date' => now()->format('d M Y H:i'),
        ]))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $title . '_' . now()->format('Ymd_His') . '.xls"');
    }

    /**
     * Get export title.
     */
    private function getExportTitle($type): string
    {
        return match($type) {
            'payments' => 'Laporan_Pembayaran',
            'bookings' => 'Laporan_Booking',
            'revenue' => 'Laporan_Pendapatan',
            default => 'Laporan',
        };
    }

    /**
     * Get data for export.
     */
    private function getDataForExport($type, Request $request): array
    {
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Booking::with(['user', 'room'])->orderByDesc('created_at');

        // Apply date filter
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
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

        if ($type === 'payments' || $type === 'revenue') {
            $query->whereIn('status', ['Dibayar']);
        }

        $bookings = $query->get();

        // Hitung summary untuk revenue
        $totalPaid = $bookings->sum('gross_amount') ?: $bookings->sum('room_price');
        $totalCount = $bookings->count();

        return [
            'bookings' => $bookings,
            'total_paid' => $totalPaid,
            'total_count' => $totalCount,
        ];
    }
}
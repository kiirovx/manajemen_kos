<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - #{{ $booking->id }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #F5F5F7;
            padding: 40px 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-header .icon {
            font-size: 48px;
            color: #10B981;
            margin-bottom: 15px;
        }
        .invoice-header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        .invoice-header .status {
            display: inline-block;
            background: #D1FAE5;
            color: #065F46;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .invoice-detail {
            background: #F0F0F7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .invoice-detail .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #E0E0E0;
            font-size: 14px;
        }
        .invoice-detail .row:last-child {
            border-bottom: none;
        }
        .invoice-detail .label {
            color: #666;
        }
        .invoice-detail .value {
            color: #333;
            font-weight: 500;
        }
        .invoice-total {
            background: #5B5EFF;
            color: white;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .btn-group {
            display: flex;
            gap: 12px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn-print {
            background: #5B5EFF;
            color: white;
        }
        .btn-print:hover { background: #4A4DE6; }
        .btn-back {
            background: #E0E0E0;
            color: #333;
        }
        .btn-back:hover { background: #D0D0D0; }

        @media print {
            body { background: white; padding: 0; }
            .invoice-container { box-shadow: none; border-radius: 0; }
            .btn-group { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <h1>Bukti Pembayaran</h1>
            <span class="status">{{ $booking->status }}</span>
        </div>

        <div class="invoice-detail">
            <div class="row">
                <span class="label">Nomor Booking</span>
                <span class="value">#{{ $booking->id }}</span>
            </div>
            <div class="row">
                <span class="label">Order ID Midtrans</span>
                <span class="value">{{ $booking->midtrans_order_id ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Transaction ID</span>
                <span class="value">{{ $booking->midtrans_transaction_id ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Nama Penyewa</span>
                <span class="value">{{ $booking->customer_name }}</span>
            </div>
            <div class="row">
                <span class="label">Email</span>
                <span class="value">{{ $booking->customer_email }}</span>
            </div>
            <div class="row">
                <span class="label">Telepon</span>
                <span class="value">{{ $booking->customer_phone }}</span>
            </div>
            <div class="row">
                <span class="label">Nama Kamar</span>
                <span class="value">{{ $booking->room_name }}</span>
            </div>
            <div class="row">
                <span class="label">Metode Pembayaran</span>
                <span class="value">{{ $booking->payment_method ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Tanggal Pembayaran</span>
                <span class="value">{{ $booking->paid_at ? $booking->paid_at->format('d M Y, H:i') : '-' }}</span>
            </div>
        </div>

        <div class="invoice-total">
            <span>Total Pembayaran</span>
            <span>Rp {{ number_format($booking->gross_amount ?? $booking->room_price, 0, ',', '.') }}</span>
        </div>

        <div class="btn-group">
            <a href="javascript:window.print()" class="btn btn-print">
                <i class="fas fa-print" style="margin-right: 8px;"></i> Cetak Bukti
            </a>
            <a href="{{ route('home') }}" class="btn btn-back">
                <i class="fas fa-home" style="margin-right: 8px;"></i> Kembali
            </a>
        </div>
    </div>
</body>
</html>
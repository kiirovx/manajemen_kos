<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; padding: 20px; font-size: 11px; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 5px; }
        .meta { text-align: center; color: #666; margin-bottom: 15px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #CCC; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background: #667eea; color: white; font-weight: 600; }
        .summary { font-weight: bold; font-size: 12px; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p class="meta">Tanggal Export: {{ $date }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Kamar</th>
                <th>Metode</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['bookings'] as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $b->created_at->format('d/m/Y') }}</td>
                <td>{{ $b->customer_name }}</td>
                <td>{{ $b->room_name }}</td>
                <td>{{ $b->payment_method ?? '-' }}</td>
                <td>Rp {{ number_format($b->gross_amount ?? $b->room_price, 0, ',', '.') }}</td>
                <td>{{ $b->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="summary">Total Transaksi: {{ $data['total_count'] }} | Total: Rp {{ number_format($data['total_paid'], 0, ',', '.') }}</p>
    <script>window.print();</script>
</body>
</html>
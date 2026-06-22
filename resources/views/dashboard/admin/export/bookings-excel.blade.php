<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>{{ $title }}</title></head>
<body>
<h2>{{ $title }}</h2><p>Tanggal Export: {{ $date }}</p>
<table border="1">
<thead><tr><th>#</th><th>Tanggal</th><th>Nama</th><th>Kamar</th><th>Harga</th><th>Metode</th><th>Status</th></tr></thead>
<tbody>
@foreach($data['bookings'] as $i=>$b)
<tr><td>{{$i+1}}</td><td>{{$b->created_at->format('d/m/Y')}}</td><td>{{$b->customer_name}}</td><td>{{$b->room_name}}</td><td>{{number_format($b->room_price,0,',','.')}}</td><td>{{$b->payment_method??'-'}}</td><td>{{$b->status}}</td></tr>
@endforeach
</tbody>
</table>
<p><strong>Total Booking: {{$data['total_count']}}</strong></p>
</body>
</html>
<h2>Halo {{ $reservation->user->name }},</h2>

<p>Reservasi Anda di {{ $reservation->room->hotel->name }} telah dikonfirmasi.</p>

<ul>
    <li>Kode: <strong>{{ $reservation->kode_reservasi }}</strong></li>
    <li>Tanggal Check-In: {{ $reservation->checkin_date }}</li>
    <li>Tanggal Check-Out: {{ $reservation->checkout_date }}</li>
    <li>Kamar: {{ $reservation->room->roomClass->name }}</li>
    <li>Jumlah Tamu: {{ $reservation->guest_count }} orang</li>
</ul>

<p>Silakan tunjukkan kode ini saat check-in.</p>

<p>Terima kasih,<br>StayEazy Team</p>

<h2>Terima kasih {{ $reservation->user->name }},</h2>

<p>Anda telah berhasil melakukan check-out dari:</p>

<ul>
    <li>Hotel: {{ $reservation->room->hotel->name }}</li>
    <li>Kamar: {{ $reservation->room->roomClass->name }}</li>
    <li>Check-in: {{ $reservation->checkin_date }}</li>
    <li>Check-out: {{ $reservation->checkout_date }}</li>
</ul>

<p>Kami harap pengalaman menginap Anda menyenangkan 😊</p>

<p>Sampai jumpa lagi,<br>StayEazy Team</p>

<h2>Halo {{ $reservation->user->name }},</h2>

<p>Berikut kode OTP untuk konfirmasi check-in:</p>

<h1 style="font-size: 2rem; letter-spacing: 3px;">{{ $reservation->otp_code }}</h1>

<p>Kode ini hanya berlaku sampai {{ $reservation->otp_expired_at->format('H:i') }}.</p>

<p>Silakan tunjukkan ke staff hotel.</p>

<p>Terima kasih,<br>StayEazy Team</p>

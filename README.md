# StayEazy 🏨

StayEazy adalah sistem backend REST API untuk manajemen pemesanan vila dan hotel yang dibangun menggunakan Laravel 11. Sistem ini dirancang untuk kebutuhan operasional internal hotel seperti pengelolaan kamar, proses reservasi, integrasi pembayaran, serta check-in dan check-out tamu.

## ✨ Fitur Utama

- 🔐 **Autentikasi Multi-Role**
  - Admin, staff hotel, dan tamu memiliki hak akses berbeda.
  - Fitur login & register dengan validasi yang aman.

- 🏨 **Manajemen Hotel & Kamar**
  - Admin dapat menambahkan hotel, kota, kelas kamar, dan kamar.
  - Staff hanya dapat mengelola kamar dari hotel tempat ia bekerja.

- 📅 **Reservasi & Jadwal**
  - Booking kamar dengan pengecekan ketersediaan.
  - Fitur check-in dan check-out yang mudah dikelola.

- 💳 **Integrasi Midtrans**
  - Pembayaran menggunakan Midtrans Snap API.
  - Status transaksi di-handle otomatis (pending, paid, expired).

- 🔔 **Notifikasi Otomatis (On Going)**
  - Notifikasi untuk admin dan staff ketika reservasi baru dibuat atau dibatalkan.

## ⚙️ Teknologi

- **Framework**: Laravel 11
- **Database**: Supabase
- **Auth**: Laravel Sanctum
- **Pembayaran**: Midtrans API
- **Testing**: Laravel HTTP Tests
- **Tools**: Postman untuk API testing

## 📂 Struktur Fitur

```plaintext
- Auth
  - Register / Login / Logout
- Users
  - Role: Admin, Staff, Customer
- Hotel
  - Kota, Kamar, Kelas Kamar
- Booking
  - Reservasi, Pembayaran, Check-in/out
- Midtrans
  - Snap Token, Callback Handler
- Staff
  - Akses terbatas hanya pada hotel terkait

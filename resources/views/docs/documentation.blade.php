@extends('docs.layouts')

@section('content')
    <h1 class="text-4xl font-bold text-gray-800 mb-5">📘 Project Overview</h1>
    {{-- <p class="text-gray-600 text-lg mb-8">
        Dokumentasi API untuk sistem reservasi hotel berbasis Laravel, dirancang sebagai proyek <strong>portfolio pribadi</strong>. Sistem ini mensimulasikan fitur-fitur utama yang sering digunakan dalam aplikasi reservasi nyata, termasuk autentikasi pengguna, manajemen kamar, alur reservasi, dan integrasi payment gateway Midtrans.
    </p> --}}
    <div class="bg-white border-1 border-gray-100 p-6 rounded-xl shadow mb-10">
        <h2 class="text-2xl font-bold text-blue-800 mb-2">📌 Tentang Proyek Ini</h2>
        <p class="text-gray-700 text-sm leading-relaxed mb-2">
            <strong>StayEazy API</strong> adalah proyek backend untuk sistem reservasi hotel yang dirancang sebagai bagian dari portfolio pengembang. Tujuan utamanya adalah untuk mendemonstrasikan praktik pengembangan API yang modern dan terstruktur dengan baik, menggunakan Laravel sebagai kerangka kerja utama.
        </p>
        <ul class="list-disc list-inside text-gray-700 text-sm mb-2">
            <li>Autentikasi dengan Laravel Sanctum</li>
            <li>Alur pemesanan kamar secara lengkap (availability, reservasi, checkin, checkout)</li>
            <li>Integrasi dengan Midtrans untuk simulasi pembayaran</li>
            <li>Role management: Admin, Staff, dan User</li>
            <li>Webhook dan notifikasi dasar</li>
        </ul>
        <div class="mt-4">
            🔗 <span class="text-sm text-gray-800">Lihat dan uji API menggunakan Postman:</span><br>
            <a href="https://documenter.getpostman.com/view/32145189/2sB34cqiBV" target="_blank" class="text-blue-600 underline text-sm hover:text-blue-800">
                https://documenter.getpostman.com/view/32145189/2sB34cqiBV
            </a>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="bg-blue-50 p-6 rounded-xl shadow">
            <h2 class="text-xl font-semibold text-blue-800 mb-2">🎯 Tujuan Sistem</h2>
            <ul class="list-disc list-inside text-gray-700 text-sm space-y-1">
                <li>Mensimulasikan proses reservasi hotel online dari sisi publik, pengguna, staf, dan admin.</li>
                <li>Mengimplementasikan autentikasi dan otorisasi berbasis role.</li>
                <li>Mendemonstrasikan pemrosesan pembayaran dengan Midtrans dan pemicu webhook.</li>
                <li>Memperlihatkan praktik dokumentasi dan struktur API yang rapi untuk kebutuhan showcase.</li>
            </ul>
        </div>
        <div class="bg-green-50 p-6 rounded-xl shadow">
            <h2 class="text-xl font-semibold text-green-800 mb-2">🧱 Teknologi</h2>
            <ul class="list-disc list-inside text-gray-700 text-sm space-y-1">
                <li>Laravel 12 + Sanctum</li>
                <li>PostgreSQL</li>
                <li>Tailwind CSS</li>
                <li>Midtrans Snap & Webhook</li>
                <li>Next.js (untuk frontend — tidak dibahas di sini)</li>
            </ul>
        </div>
    </div>

    <div class="bg-red-50 p-6 rounded-xl mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">🔐 Role & Hak Akses</h2>
        <p class="text-sm text-gray-700 mb-4">Setiap peran memiliki akses terbatas terhadap resource tertentu.</p>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Role</th>
                    <th class="p-2 border">Deskripsi</th>
                    <th class="p-2 border">Contoh Endpoint</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border">User</td>
                    <td class="p-2 border">Melakukan booking, melihat reservasi, dan memantau status pembayaran.</td>
                    <td class="p-2 border">/api/user/reservations</td>
                </tr>
                <tr>
                    <td class="p-2 border">Staff</td>
                    <td class="p-2 border">Verifikasi kode reservasi, proses check-in/out, dan log aktivitas.</td>
                    <td class="p-2 border">/api/staff/checkin</td>
                </tr>
                <tr>
                    <td class="p-2 border">Admin</td>
                    <td class="p-2 border">Mengelola hotel, kelas kamar, unit, kota, dan user.</td>
                    <td class="p-2 border">/api/admin/hotels</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bg-yellow-50 p-6 rounded-xl shadow mb-12">
        <h2 class="text-2xl font-bold text-yellow-800 mb-2">⚙️ Response Format</h2>
        <p class="text-sm text-gray-700 mb-2">Semua response mengikuti format standar JSON seperti berikut:</p>
<pre class="bg-white border p-4 rounded text-sm overflow-x-auto">{
  "success": true,
  "message": "Deskripsi sukses atau gagal",
  "data": {...},
  "errors": null
}</pre>
        <p class="text-xs text-gray-500 mt-2">Gunakan header Authorization: Bearer {token} untuk endpoint privat.</p>
    </div>

    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📚 Navigasi Dokumentasi</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <a href="{{ route('docs.auth') }}" class="bg-white hover:bg-blue-50 border rounded-xl p-4 shadow transition">
                <strong class="text-blue-700">🔐 Auth API</strong>
                <p class="text-gray-600 mt-1">Registrasi, login, logout</p>
            </a>
            <a href="{{ route('docs.public') }}" class="bg-white hover:bg-blue-50 border rounded-xl p-4 shadow transition">
                <strong class="text-blue-700">🌐 Public API</strong>
                <p class="text-gray-600 mt-1">Hotel & kamar tanpa autentikasi</p>
            </a>
            <a href="{{ route('docs.user') }}" class="bg-white hover:bg-blue-50 border rounded-xl p-4 shadow transition">
                <strong class="text-blue-700">👤 User API</strong>
                <p class="text-gray-600 mt-1">Reservasi dan riwayat user</p>
            </a>
            <a href="{{ route('docs.staff') }}" class="bg-white hover:bg-blue-50 border rounded-xl p-4 shadow transition">
                <strong class="text-blue-700">👨‍💻 Staff API</strong>
                <p class="text-gray-600 mt-1">Check-in dan check-out</p>
            </a>
            <a href="{{ route('docs.admin') }}" class="bg-white hover:bg-blue-50 border rounded-xl p-4 shadow transition">
                <strong class="text-blue-700">🛠️ Admin API</strong>
                <p class="text-gray-600 mt-1">Kelola hotel dan kamar</p>
            </a>
        </div>
    </div>

    <p class="text-center text-xs text-gray-400">Versi API: v1.0.0 • Terakhir diperbarui: 10 Juli 2025</p>
@endsection

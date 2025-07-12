@extends('docs.layouts')

@section('content')
<div class="bg-gradient-to-br from-blue-50 to-white min-h-screen flex items-center justify-center p-8">
    <div class="text-center max-w-3xl">
        <h1 class="text-4xl md:text-5xl font-bold text-blue-700 mb-6 leading-tight">
            Selamat Datang di <span class="text-blue-900">Stay Eazy</span> 🎉
        </h1>
        <p class="text-gray-700 text-lg md:text-xl mb-8">
            Sistem reservasi hotel modern berbasis Laravel yang dirancang sebagai <strong>portfolio API</strong> lengkap,
            dilengkapi autentikasi, multi-role, Midtrans payment gateway, dan dokumentasi interaktif.
        </p>

        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <a href="{{ route('docs.overview') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                📚 Lihat Dokumentasi
            </a>
            <a href="https://www.postman.com/" target="_blank"
               class="bg-white border border-blue-500 text-blue-600 font-medium px-6 py-3 rounded-lg hover:bg-blue-50 transition">
                🔗 Lihat di Postman
            </a>
        </div>
    </div>
</div>
@endsection

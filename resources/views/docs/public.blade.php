@extends('docs.layouts')

@section('content')
        <h1 class="text-4xl font-bold text-gray-800 mb-5">🌐 Public API</h1>
        <p class="text-gray-600 mb-8 text-lg">
            Gunakan endpoint di bawah ini untuk mengambil data publik seperti daftar hotel, detail kamar, dan ketersediaan
            unit. Tidak memerlukan autentikasi.
        </p>
    @include('docs.card',['file' => 'docs/json/public.json'])
@endsection

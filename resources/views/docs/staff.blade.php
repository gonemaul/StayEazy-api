@extends('docs.layouts')

@section('content')
    <h1 class="text-4xl font-bold text-gray-800 mb-2">👨‍💻 Staff API</h1>
    <p class="text-gray-600 mb-8 text-lg">
        Endpoint yang digunakan oleh staf hotel untuk memverifikasi dan memproses check-in dan check-out tamu berdasarkan reservasi.
    </p>
    @include('docs.card',['file' => 'docs/json/staff.json'])
@endsection

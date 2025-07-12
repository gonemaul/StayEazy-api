@extends('docs.layouts')

@section('content')
    <h1 class="text-4xl font-bold text-gray-800 mb-2">🛠️ Admin API</h1>
    <p class="text-gray-600 mb-8 text-lg">
        Endpoint yang hanya dapat digunakan oleh admin untuk mengelola data hotel, kamar, unit, dan pengguna sistem.
    </p>
    @include('docs.card',['file' => 'docs/json/admin.json'])
@endsection

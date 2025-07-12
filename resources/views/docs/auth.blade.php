@extends('docs.layouts')

@section('content')
    <h1 class="text-4xl font-bold text-gray-800 mb-2">🔐 Auth API</h1>
    <p class="text-gray-600 mb-8 text-lg">
        Endpoint untuk registrasi, login, dan logout pengguna sistem. Token autentikasi diperlukan untuk mengakses sebagian besar endpoint yang bersifat privat.
    </p>

    @include('docs.card',['file' => 'docs/json/auth.json'])
@endsection

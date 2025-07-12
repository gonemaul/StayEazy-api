@extends('docs.layouts')

@section('content')
    <h1 class="text-4xl font-bold text-gray-800 mb-2">👤 User API</h1>
    <p class="text-gray-600 mb-8 text-lg">
        Endpoint yang dapat digunakan oleh pengguna yang sudah terautentikasi. Gunakan token Bearer untuk mengakses data dan mengelola reservasi pribadi.
    </p>
    @include('docs.card',['file' => 'docs/json/user.json'])
@endsection

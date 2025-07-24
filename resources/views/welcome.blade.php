@extends('docs.layouts')

@section('content')
    <div class="bg-gradient-to-br from-blue-50 to-white min-h-screen flex items-center justify-center p-8">
        <div class="text-center max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold text-blue-700 mb-6 leading-tight">
                Selamat Datang di <span class="text-blue-900">Stay Eazy</span> 🎉
            </h1>
            <p class="text-gray-700 text-lg md:text-xl mb-8">
                Sistem reservasi hotel modern berbasis Laravel yang dirancang sebagai <strong>portfolio API</strong>
                lengkap,
                dilengkapi autentikasi, multi-role, Midtrans payment gateway, dan dokumentasi interaktif.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-w-4xl mx-auto mt-6">
                {{-- Dokumentasi --}}
                <a href="{{ route('docs.overview') }}"
                    class="flex items-center gap-3 p-4 border rounded-lg shadow hover:bg-blue-50 transition">
                    <div class="bg-blue-600 text-white p-2 rounded">
                        📚
                    </div>
                    <div>
                        <div class="text-blue-700 font-semibold">Lihat Dokumentasi</div>
                        <div class="text-sm text-gray-500">Panduan penggunaan API & sistem</div>
                    </div>
                </a>

                {{-- Postman --}}
                <a href="https://documenter.getpostman.com/view/32145189/2sB34cqiBV" target="_blank"
                    class="flex items-center gap-3 p-4 border rounded-lg shadow hover:bg-orange-50 transition">
                    <div class="bg-orange-500 p-2 rounded">
                        {{-- SVG Postman logo --}}
                        <svg viewBox="0 0 512 512" fill="white" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M336.4 119.6c-21.6-21.6-52.5-32.3-83.3-28.8l-25.2 3c-18 2.1-35.1 9.9-48.3 22.1-21.4 20.1-31.2 50-25.7 79.1 1.3 6.8 5.3 12.9 11 16.6l90.1 59.7c7.2 4.8 16.4 4.9 23.7 0.2l93.1-58.9c5.9-3.7 9.8-9.6 11.1-16.3 4.3-21.5-1.4-44.2-15.3-61.5-2.5-3.2-5.2-6.1-8.2-8.8zM206.1 324.7l-12.1 70.7c-1.7 10.1 9.1 17.3 17.8 11.8l61.4-39.6c5.5-3.6 7.7-10.5 5.3-16.6l-17.3-43.1c-3.3-8.2-13.5-10.7-20.1-4.8l-34.6 30.2zM0 256C0 114.6 114.6 0 256 0s256 114.6 256 256-114.6 256-256 256S0 397.4 0 256z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-orange-600 font-semibold">Lihat di Postman</div>
                        <div class="text-sm text-gray-500">Cek dokumentasi interaktif API</div>
                    </div>
                </a>

                {{-- GitHub --}}
                <a href="https://github.com/gonemaul/StayEazy-api" target="_blank"
                    class="flex items-center gap-3 p-4 border rounded-lg shadow hover:bg-gray-100 transition">
                    <div class="bg-gray-800 p-2 rounded">
                        {{-- GitHub Icon --}}
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M12 0C5.37 0 0 5.373 0 12c0 5.302 3.438 9.8 8.205 11.387.6.113.82-.26.82-.577v-2.03c-3.338.724-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.757-1.333-1.757-1.09-.745.082-.729.082-.729 1.205.086 1.84 1.236 1.84 1.236 1.07 1.834 2.807 1.304 3.492.997.108-.776.42-1.305.763-1.605-2.665-.304-5.466-1.332-5.466-5.931 0-1.31.467-2.381 1.235-3.221-.124-.303-.535-1.523.117-3.176 0 0 1.008-.322 3.3 1.23a11.47 11.47 0 0 1 3.003-.404c1.02.004 2.047.138 3.003.404 2.29-1.552 3.296-1.23 3.296-1.23.655 1.653.244 2.873.12 3.176.77.84 1.232 1.911 1.232 3.221 0 4.61-2.805 5.624-5.475 5.921.431.371.815 1.102.815 2.222v3.293c0 .32.217.694.825.576C20.565 21.796 24 17.297 24 12c0-6.627-5.373-12-12-12z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-800 font-semibold">Lihat di GitHub</div>
                        <div class="text-sm text-gray-500">Kode sumber proyek tersedia publik</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

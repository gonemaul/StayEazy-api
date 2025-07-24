<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stay Eazy Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            scroll-behavior: smooth;
        }

        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    @if (!request()->routeIs('home'))
        <div class="flex">
            <div
                class="md:hidden fixed top-0 left-0 right-0 bg-white shadow z-40 flex justify-between items-center px-4 h-16">
                <h1 class="text-lg font-semibold"><a href="/" class="decoration-0">📘 StayEazy Docs</a></h1>
                <button id="toggleSidebar" class="text-gray-700 focus:outline-none text-2xl">
                    ☰
                </button>
            </div>
            <!-- Sidebar -->
            <aside id="sidebar"
                class="sidebar-fixed bg-white shadow-md p-6 w-64 z-50 fixed top-0 left-0 h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
                <h2 class="text-2xl font-bold mb-6 text-center text-blue-600"><a href="/"
                        class="decoration-0">StayEazy Docs</a></h2>
                <nav class="space-y-5 text-gray-800 font-semibold text-md">
                    <a href="{{ route('docs.overview') }}"
                        class="block px-6 py-3 transition rounded {{ request()->routeIs('docs.overview') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        🧭 Overview
                    </a>
                    <a href="{{ route('docs.auth') }}"
                        class="block px-6 py-3 transition rounded {{ request()->routeIs('docs.auth') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        🔐 Auth
                    </a>
                    <a href="{{ route('docs.public') }}"
                        class="block px-6 py-3 transition rounded {{ request()->routeIs('docs.public') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        🌐 Public
                    </a>
                    <a href="{{ route('docs.user') }}"
                        class="block px-6 py-3 transition rounded {{ request()->routeIs('docs.user') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        👤 User
                    </a>
                    <a href="{{ route('docs.staff') }}"
                        class="block px-6 py-3 transition rounded {{ request()->routeIs('docs.staff') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        👨‍💻 Staff
                    </a>
                    <a href="{{ route('docs.admin') }}"
                        class="block px-6 py-3 transition rounded {{ request()->routeIs('docs.admin') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                        🛠️ Admin
                    </a>
                </nav>
            </aside>

            <div
                class="md:ml-64 md:pt-10 pt-20 px-10 pb-10 w-full min-h-screen {{ request()->routeIs('docs.overview') ? 'bg-gray-50' : 'bg-blue-50' }}">
                @yield('content')
            </div>
            <script>
                document.getElementById('toggleSidebar').addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.toggle('-translate-x-full');
                });
            </script>
        </div>
    @else
        @yield('content')
    @endif
    {{-- Toggle Script --}}
</body>

</html>

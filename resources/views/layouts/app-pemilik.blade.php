<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel Motor - Pemilik</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

            <div class="flex">

                <!-- Sidebar -->
                    <div class="w-64 h-screen bg-gray-900 text-white fixed">

                        <div class="p-5 border-b border-gray-700">

                <h1 class="text-2xl font-bold">
                    Bengkel App
                </h1>

                <p class="text-sm text-gray-300 mt-2">
                    {{ auth()->user()->name }}
                </p>

            </div>

            <ul class="p-4 space-y-2">

                <li>
                    <a href="/pemilik/dashboard"
                       class="block p-2 rounded hover:bg-gray-700">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/pemilik/laporan-keuangan"
                       class="block p-2 rounded hover:bg-gray-700">
                        Laporan Keuangan
                    </a>
                </li>

                <li>
                    <a href="/pemilik/laporan-servis"
                       class="block p-2 rounded hover:bg-gray-700">
                        Laporan Servis
                    </a>
                </li>

                <li>
                    <a href="/pemilik/monitor-stok"
                       class="block p-2 rounded hover:bg-gray-700">
                        Monitor Stok
                    </a>
                </li>

                <li>
                    <a href="/pemilik/monitor-order"
                       class="block p-2 rounded hover:bg-gray-700">
                        Monitor Order
                    </a>
                </li>

                <li>
                    <a href="/pemilik/manajemen-akun"
                       class="block p-2 rounded hover:bg-gray-700">
                        Manajemen Akun
                    </a>
                </li>

                <li>
                    <a href="/pemilik/pengaturan"
                       class="block p-2 rounded hover:bg-gray-700">
                        Pengaturan Bengkel
                    </a>
                </li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                    <button
                        type="submit"
                        class="w-full text-left p-2 rounded bg-red-500 hover:bg-red-600 text-white font-semibold">
                        Logout
                    </button>
                    </form>
                </li>

            </ul>

        </div>

        <!-- Content -->
        <div class="ml-64 w-full p-6">

            @if(session('success'))

            <div class="bg-green-500 text-white p-4 rounded mb-4">

                {{ session('success') }}

            </div>

            @endif

            @if(session('error'))

            <div class="bg-red-500 text-white p-4 rounded mb-4">

                {{ session('error') }}

            </div>

            @endif

            @yield('content')

        </div>

    </div>

</body>
</html>

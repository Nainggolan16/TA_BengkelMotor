<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <style>
    .ts-wrapper{
        width:100% !important;
    }

    .ts-control{
        min-height:42px;
    }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel Motor</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

            <div class="flex">

                <!-- Sidebar -->
                    <div class="w-64 h-screen bg-gray-900 text-white fixed">
                        
                        <div class="p-5 border-b border-gray-700">

                <h1 class="text-2xl font-bold">
                    Berkah Jaya App
                </h1>

                <p class="text-sm text-gray-300 mt-2">
                    {{ auth()->user()->name }}
                </p>

            </div>

            <ul class="p-4 space-y-2">

                <li>
                    <a href="/dashboard-admin"
                       class="block p-2 rounded hover:bg-gray-700">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/pelanggan"
                       class="block p-2 rounded hover:bg-gray-700">
                        Pelanggan
                    </a>
                </li>

                <li>
                    <a href="/kendaraan"
                       class="block p-2 rounded hover:bg-gray-700">
                        Kendaraan
                    </a>
                </li>

                <li>
                    <a href="/kategori-servis"
                       class="block p-2 rounded hover:bg-gray-700">
                        Kategori Servis
                    </a>
                </li>

                <li>
                    <a href="/jenis-servis"
                       class="block p-2 rounded hover:bg-gray-700">
                        Jenis Servis
                    </a>
                </li>

                <li>
                    <a href="/suku-cadang"
                       class="block p-2 rounded hover:bg-gray-700">
                        Suku Cadang
                    </a>
                </li>

                <li>
                    <a href="/stok-masuk"
                       class="block p-2 rounded hover:bg-gray-700">
                        Stok Masuk
                    </a>
                </li>

                <li>
                    <a href="/transaksi-servis"
                    class="block p-2 rounded hover:bg-gray-700">
                        Transaksi Servis
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
        <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    </body>
</html>
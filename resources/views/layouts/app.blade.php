<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Bengkel Motor</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-gray-100">

<div class="flex">

    <!-- SIDEBAR -->
    <div class="w-64 h-screen bg-gray-900 text-white p-5">

        <h1 class="text-2xl font-bold mb-8">
            Bengkel App
        </h1>

        <ul class="space-y-3">
            <li>

                <form method="POST"
                    action="/logout">

                    @csrf

                    <button type="submit"
                            class="hover:text-red-400">

                        Logout

                    </button>

                </form>

            </li>
            
            <li>
                <a href="/dashboard-admin"
                   class="block hover:text-yellow-400">

                    Dashboard

                </a>
            </li>

            <li>
                <a href="/pelanggan"
                   class="block hover:text-yellow-400">

                    Pelanggan

                </a>
            </li>

            <li>
                <a href="/kendaraan"
                   class="block hover:text-yellow-400">

                    Kendaraan

                </a>
            </li>

            <li>
                <a href="/jenis-servis"
                   class="block hover:text-yellow-400">

                    Jenis Servis

                </a>
            </li>

            <li>
                <a href="/suku-cadang"
                   class="block hover:text-yellow-400">

                    Suku Cadang

                </a>
            </li>

        </ul>

    </div>

    <!-- CONTENT -->
    <div class="flex-1 p-8">

        @yield('content')

    </div>

</div>

</body>
</html>
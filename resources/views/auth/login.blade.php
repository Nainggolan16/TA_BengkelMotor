<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bengkel Motor</title>

    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <!-- Header -->
        <div class="text-center mb-8">

            <h1 class="text-4xl font-bold text-slate-800">
                Bengkel Motor
            </h1>

            <p class="text-gray-500 mt-2">
                Sistem Informasi Manajemen Bengkel
            </p>

        </div>

        <!-- Card Login -->
        <div class="bg-white rounded-2xl shadow-xl p-8">

            <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">
                Login
            </h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Remember -->
                <div class="flex items-center mb-6">

                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300">

                    <span class="ml-2 text-sm text-gray-600">
                        Ingat Saya
                    </span>

                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">

                    Masuk Sistem

                </button>

            </form>

        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-500">

            © {{ date('Y') }} Bengkel Motor

        </div>

    </div>

</div>

</body>
</html>
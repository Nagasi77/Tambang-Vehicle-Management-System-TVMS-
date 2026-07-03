<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — TVMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">TVMS</h1>
            <p class="text-gray-500 mt-1">Tambang Vehicle Management System</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-md px-8 py-10">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Masuk ke Akun Anda</h2>

            {{-- Global error (account locked, wrong credentials) --}}
            @if ($errors->has('email'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        placeholder="contoh@email.com"
                    >
                    @error('email')
                        {{-- Only show field-level validation errors (not the auth error already shown above) --}}
                        @if (in_array($message, ['Email wajib diisi.', 'Format email tidak valid.']))
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                               {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        placeholder="Minimal 8 karakter"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center mb-6">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400">&copy; {{ date('Y') }} TVMS. Hak cipta dilindungi.</p>
    </div>

</body>
</html>

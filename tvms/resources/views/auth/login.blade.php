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
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            class="w-full rounded-lg border px-4 py-2.5 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                            placeholder="Minimal 8 karakter"
                        >
                        <button 
                            type="button" 
                            id="togglePassword" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none"
                        >
                            {{-- Icon Mata Terbuka --}}
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{-- Icon Mata Tercoret (Sembunyi secara default) --}}
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
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

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', function () {
            // Ubah tipe input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ubah tampilan icon
            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        });
    </script>
</body>
</html>

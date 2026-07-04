<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TVMS') — TVMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Brand --}}
                <div class="flex items-center space-x-8">
                    <a href="{{ auth()->user()?->role === 'admin' ? route('dashboard') : route('approvals.index') }}"
                       class="text-xl font-bold tracking-tight">TVMS</a>

                    {{-- Admin links --}}
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <div class="hidden md:flex items-center space-x-4 text-sm font-medium">
                                <a href="{{ route('dashboard') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('dashboard*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('vehicles.index') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('vehicles*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Kendaraan
                                </a>
                                <a href="{{ route('drivers.index') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('drivers*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Pengemudi
                                </a>
                                <a href="{{ route('bookings.index') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('bookings*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Pemesanan
                                </a>
                                {{-- Jadwal Servis — dropdown per kendaraan, atau halaman ringkasan --}}
                                <a href="{{ route('services.index') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('services*') || request()->routeIs('vehicles.services*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Jadwal Servis
                                </a>
                                <a href="{{ route('reports.index') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('reports*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Laporan
                                </a>
                            </div>
                        @elseif (auth()->user()->role === 'approver')
                            <div class="hidden md:flex items-center space-x-4 text-sm font-medium">
                                <a href="{{ route('approvals.index') }}"
                                   class="hover:text-indigo-200 transition-colors {{ request()->routeIs('approvals*') ? 'text-white underline underline-offset-4' : 'text-indigo-100' }}">
                                    Pengajuan
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                {{-- Right side: user info + logout --}}
                @auth
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-indigo-200 hidden sm:inline">
                            {{ auth()->user()->name }}
                            <span class="ml-1 text-xs bg-indigo-900 px-2 py-0.5 rounded-full capitalize">
                                {{ auth()->user()->role }}
                            </span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="bg-indigo-800 hover:bg-indigo-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 012 0v4a1 1 0 01-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- SweetAlert2 global delete handler --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-confirm-delete]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const name  = form.dataset.confirmDelete || 'data ini';
                    const self  = form;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html:  'Yakin ingin menghapus <strong>' + name + '</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>',
                        icon:  'warning',
                        showCancelButton:  true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor:  '#6b7280',
                        confirmButtonText:  'Ya, Hapus',
                        cancelButtonText:   'Batal',
                    }).then(function (result) {
                        if (result.isConfirmed) { self.submit(); }
                    });
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

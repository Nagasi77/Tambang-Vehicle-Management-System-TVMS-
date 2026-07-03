@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Page header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Ringkasan operasional kendaraan tambang</p>
    </div>

    {{-- ------------------------------------------------------------------ --}}
    {{-- Statistics cards                                                     --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        {{-- Booking Aktif --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Booking Aktif</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $activeBookings }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending &amp; disetujui level 1</p>
            </div>
        </div>

        {{-- Total Kendaraan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 17h.01M16 17h.01M5 10h14M5 10l1-4h12l1 4M5 10H3m16 0h2M4 17h16a1 1 0 001-1v-6H3v6a1 1 0 001 1z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Kendaraan</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalVehicles }}</p>
                <p class="text-xs text-gray-400 mt-0.5">terdaftar di sistem</p>
            </div>
        </div>

        {{-- Pengemudi Tersedia --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Pengemudi Tersedia</p>
                <p class="text-3xl font-bold text-green-600">{{ $availableDrivers }}</p>
                <p class="text-xs text-gray-400 mt-0.5">status tersedia</p>
            </div>
        </div>

    </div>

    {{-- ------------------------------------------------------------------ --}}
    {{-- Bar chart — vehicle usage frequency                                 --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Frekuensi Pemakaian Kendaraan</h2>

        <canvas id="vehicleChart" height="100"></canvas>

        <p id="noChartData"
           class="hidden text-center text-gray-400 text-sm py-10 italic">
            Belum ada data pemakaian kendaraan
        </p>
    </div>

    {{-- ------------------------------------------------------------------ --}}
    {{-- Recent activity log                                                  --}}
    {{-- ------------------------------------------------------------------ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>

        @if ($recentLogs->isEmpty())
            <p class="text-center text-gray-400 text-sm py-6 italic">Belum ada aktivitas.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs uppercase text-gray-500 tracking-wide">
                            <th class="pb-3 pr-6 font-semibold whitespace-nowrap">Waktu</th>
                            <th class="pb-3 pr-6 font-semibold whitespace-nowrap">Aksi</th>
                            <th class="pb-3 pr-6 font-semibold">Deskripsi</th>
                            <th class="pb-3 font-semibold whitespace-nowrap">Pengguna</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recentLogs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 pr-6 text-gray-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 pr-6 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ $log->aksi }}
                                    </span>
                                </td>
                                <td class="py-3 pr-6 text-gray-700">
                                    {{ $log->deskripsi }}
                                </td>
                                <td class="py-3 text-gray-600 whitespace-nowrap">
                                    {{ $log->user?->name ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- -------------------------------------------------------------------- --}}
{{-- Chart.js                                                               --}}
{{-- -------------------------------------------------------------------- --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('{{ route("dashboard.chart-data") }}')
    .then(r => r.json())
    .then(data => {
        if (!data.labels || data.labels.length === 0) {
            document.getElementById('vehicleChart').style.display = 'none';
            document.getElementById('noChartData').style.display = 'block';
            return;
        }
        new Chart(document.getElementById('vehicleChart'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Jumlah Booking',
                    data: data.data,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    });
</script>
@endsection

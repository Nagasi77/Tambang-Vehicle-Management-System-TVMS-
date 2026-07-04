@extends('layouts.app')

@section('title', 'Jadwal Servis Kendaraan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Servis Kendaraan</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan seluruh riwayat & jadwal servis semua kendaraan</p>
    </div>
    <a href="{{ route('vehicles.index') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375A1.125 1.125 0 012.25 17.625V14.25"/>
        </svg>
        Kelola Kendaraan
    </a>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4 mb-4">
    <form method="GET" action="{{ route('services.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari plat nomor atau deskripsi servis..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <select name="plat_nomor"
                class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
            <option value="">Semua Kendaraan</option>
            @foreach($vehicles as $plat)
                <option value="{{ $plat }}" {{ request('plat_nomor') === $plat ? 'selected' : '' }}>
                    {{ $plat }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search','plat_nomor']))
            <a href="{{ route('services.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    @if ($services->total() === 0)
        <div class="px-6 py-16 text-center">
            <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
            </svg>
            <p class="text-gray-500 text-sm font-medium">
                @if(request()->hasAny(['search','plat_nomor']))
                    Tidak ada data servis yang cocok dengan filter.
                @else
                    Belum ada riwayat servis. Tambahkan dari halaman detail kendaraan.
                @endif
            </p>
        </div>
    @else
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kendaraan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Servis</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($services as $service)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-400">
                            {{ ($services->currentPage() - 1) * $services->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('vehicles.services.index', $service->vehicle) }}"
                               class="font-medium text-indigo-600 hover:underline">
                                {{ $service->vehicle->plat_nomor }}
                            </a>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ ucfirst(str_replace('_', ' ', $service->vehicle->jenis)) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                            {{ $service->tanggal_service->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-sm">
                            <span class="block truncate" title="{{ $service->deskripsi }}">
                                {{ $service->deskripsi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('vehicles.services.edit', [$service->vehicle, $service]) }}"
                               class="inline-flex items-center gap-1 rounded-lg bg-yellow-50 border border-yellow-200 px-3 py-1.5 text-xs font-medium text-yellow-700 hover:bg-yellow-100 transition-colors">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($services->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $services->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

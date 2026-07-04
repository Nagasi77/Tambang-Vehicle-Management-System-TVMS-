@extends('layouts.app')

@section('title', 'Riwayat Servis: ' . $vehicle->plat_nomor)

@section('content')
<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Riwayat Servis: {{ $vehicle->plat_nomor }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ ucfirst(str_replace('_', ' ', $vehicle->jenis)) }} &bull;
                {{ ucfirst(str_replace('_', ' ', $vehicle->status_kepemilikan)) }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('vehicles.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Kendaraan
            </a>
            <a href="{{ route('vehicles.services.create', $vehicle) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Servis
            </a>
        </div>
    </div>

    {{-- Table card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if ($services->total() === 0)
            <div class="px-6 py-16 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-500 text-sm">Belum ada riwayat servis untuk kendaraan ini.</p>
                <a href="{{ route('vehicles.services.create', $vehicle) }}"
                   class="mt-4 inline-block text-indigo-600 text-sm font-medium hover:underline">
                    Tambahkan riwayat servis pertama
                </a>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Tanggal Servis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($services as $service)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                {{ $service->tanggal_service->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xl">
                                {{ $service->deskripsi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('vehicles.services.edit', [$vehicle, $service]) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-yellow-50 border border-yellow-200 px-3 py-1.5 text-xs font-medium text-yellow-700 hover:bg-yellow-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('vehicles.services.destroy', [$vehicle, $service]) }}"
                                          data-confirm-delete="servis tanggal {{ $service->tanggal_service->format('d-m-Y') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-red-50 border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-10 0h14"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($services->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $services->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection

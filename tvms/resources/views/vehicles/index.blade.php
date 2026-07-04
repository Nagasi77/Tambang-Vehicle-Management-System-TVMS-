@extends('layouts.app')

@section('title', 'Daftar Kendaraan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Kendaraan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data kendaraan operasional tambang</p>
    </div>
    <a href="{{ route('vehicles.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition-colors">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Kendaraan
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4 mb-4">
    <form method="GET" action="{{ route('vehicles.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari plat nomor..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <select name="jenis"
                class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
            <option value="">Semua Jenis</option>
            <option value="angkutan_orang"  {{ request('jenis') === 'angkutan_orang'  ? 'selected' : '' }}>Angkutan Orang</option>
            <option value="angkutan_barang" {{ request('jenis') === 'angkutan_barang' ? 'selected' : '' }}>Angkutan Barang</option>
        </select>
        <select name="status_kepemilikan"
                class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
            <option value="">Semua Kepemilikan</option>
            <option value="milik_sendiri" {{ request('status_kepemilikan') === 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
            <option value="sewa"          {{ request('status_kepemilikan') === 'sewa'          ? 'selected' : '' }}>Sewa</option>
        </select>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search','jenis','status_kepemilikan']))
            <a href="{{ route('vehicles.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden" id="services">
    @if ($vehicles->isEmpty())
        <div class="px-6 py-16 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>
            <p class="font-medium">Tidak ada data kendaraan ditemukan.</p>
            @if(request()->hasAny(['search','jenis','status_kepemilikan']))
                <p class="text-sm mt-1 text-gray-400">Coba ubah filter pencarian Anda.</p>
            @else
                <p class="text-sm mt-1">Klik tombol "Tambah Kendaraan" untuk menambahkan data.</p>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plat Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kepemilikan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ ($vehicles->currentPage() - 1) * $vehicles->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $vehicle->plat_nomor }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if ($vehicle->jenis === 'angkutan_orang')
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                        Angkutan Orang
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                                        Angkutan Barang
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if ($vehicle->status_kepemilikan === 'milik_sendiri')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                        Milik Sendiri
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                        Sewa
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    {{-- Servis --}}
                                    <a href="{{ route('vehicles.services.index', $vehicle) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-teal-50 px-3 py-1.5 text-xs font-medium text-teal-700 hover:bg-teal-100 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                                        </svg>
                                        Servis
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                        </svg>
                                        Edit
                                    </a>
                                    {{-- Hapus dengan SweetAlert --}}
                                    <form method="POST"
                                          action="{{ route('vehicles.destroy', $vehicle->id) }}"
                                          class="inline"
                                          data-confirm-delete="{{ $vehicle->plat_nomor }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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
        </div>

        {{-- Pagination (pertahankan query string filter) --}}
        @if ($vehicles->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $vehicles->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

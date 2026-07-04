@extends('layouts.app')

@section('title', 'Daftar Pemesanan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pemesanan Kendaraan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola semua pengajuan pemesanan kendaraan operasional</p>
    </div>
    <a href="{{ route('bookings.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition-colors">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Buat Booking
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4 mb-4">
    <form method="GET" action="{{ route('bookings.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari plat nomor, pengemudi, keperluan..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <select name="status"
                class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
            <option value="">Semua Status</option>
            <option value="pending"           {{ request('status') === 'pending'           ? 'selected' : '' }}>Pending</option>
            <option value="disetujui_level_1" {{ request('status') === 'disetujui_level_1' ? 'selected' : '' }}>Disetujui L1</option>
            <option value="disetujui_final"   {{ request('status') === 'disetujui_final'   ? 'selected' : '' }}>Disetujui Final</option>
            <option value="ditolak"           {{ request('status') === 'ditolak'           ? 'selected' : '' }}>Ditolak</option>
            <option value="dibatalkan"        {{ request('status') === 'dibatalkan'        ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('bookings.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    @if ($bookings->isEmpty())
        <div class="px-6 py-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-gray-500 text-sm font-medium">Tidak ada data pemesanan ditemukan.</p>
            @if(request()->hasAny(['search','status']))
                <p class="text-sm mt-1 text-gray-400">Coba ubah filter pencarian Anda.</p>
            @else
                <a href="{{ route('bookings.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                    Buat pemesanan pertama
                </a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plat Nomor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengemudi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Mulai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Selesai</th>
                        {{-- Kolom keperluan dibatasi lebar agar tidak meluber --}}
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider" style="max-width:200px">Keperluan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-sm text-gray-400">
                                {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                {{ $booking->vehicle?->plat_nomor ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                {{ $booking->driver?->nama_driver ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                {{ $booking->tanggal_mulai?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                {{ $booking->tanggal_selesai?->format('d/m/Y') ?? '—' }}
                            </td>
                            {{-- Keperluan: potong teks panjang, tooltip saat hover --}}
                            <td class="px-4 py-4 text-sm text-gray-700" style="max-width:200px">
                                <span class="block truncate" title="{{ $booking->keperluan }}">
                                    {{ $booking->keperluan }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusMap = [
                                        'pending'           => ['label' => 'Pending',          'class' => 'bg-yellow-100 text-yellow-800'],
                                        'disetujui_level_1' => ['label' => 'Disetujui L1',     'class' => 'bg-blue-100 text-blue-800'],
                                        'disetujui_final'   => ['label' => 'Disetujui Final',  'class' => 'bg-green-100 text-green-800'],
                                        'ditolak'           => ['label' => 'Ditolak',           'class' => 'bg-red-100 text-red-800'],
                                        'dibatalkan'        => ['label' => 'Dibatalkan',        'class' => 'bg-gray-100 text-gray-600'],
                                    ];
                                    $s = $statusMap[$booking->status_pembokingan] ?? ['label' => $booking->status_pembokingan, 'class' => 'bg-gray-100 text-gray-600'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('bookings.show', $booking) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                                        Detail
                                    </a>

                                    @if ($booking->status_pembokingan === 'pending')
                                        <a href="{{ route('bookings.edit', $booking) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition-colors">
                                            Edit
                                        </a>
                                    @endif

                                    @if (in_array($booking->status_pembokingan, ['pending', 'disetujui_level_1']))
                                        <form method="POST"
                                              action="{{ route('bookings.cancel', $booking) }}"
                                              data-confirm-delete="Booking #{{ $booking->id }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors">
                                                Batal
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

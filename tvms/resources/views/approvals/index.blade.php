@extends('layouts.app')

@section('title', 'Pengajuan Saya')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengajuan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar pemesanan yang perlu Anda proses</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    @if ($approvals->isEmpty())
        <div class="px-6 py-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-500 text-sm font-medium">Tidak ada pengajuan yang perlu diproses.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plat Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengemudi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Level Approval</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Booking</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($approvals as $approval)
                        @php
                            $booking = $approval->booking;
                            $statusMap = [
                                'pending'           => ['label' => 'Pending',           'class' => 'bg-yellow-100 text-yellow-800'],
                                'disetujui_level_1' => ['label' => 'Disetujui Level 1', 'class' => 'bg-blue-100 text-blue-800'],
                                'disetujui_final'   => ['label' => 'Disetujui Final',   'class' => 'bg-green-100 text-green-800'],
                                'ditolak'           => ['label' => 'Ditolak',            'class' => 'bg-red-100 text-red-800'],
                                'dibatalkan'        => ['label' => 'Dibatalkan',         'class' => 'bg-gray-100 text-gray-600'],
                            ];
                            $s = $statusMap[$booking->status_pembokingan] ?? ['label' => $booking->status_pembokingan, 'class' => 'bg-gray-100 text-gray-600'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $booking->vehicle?->plat_nomor ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $booking->driver?->nama_driver ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $booking->tanggal_mulai?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $booking->tanggal_selesai?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                                {{ $booking->keperluan }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $approval->level === 1 ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    Level {{ $approval->level }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('approvals.show', $booking) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

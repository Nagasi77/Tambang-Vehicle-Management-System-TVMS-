@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . $booking->id)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('bookings.index') }}" class="hover:text-indigo-600 transition-colors">Pemesanan</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-medium">Detail #{{ $booking->id }}</span>
    </div>

    {{-- Header + actions --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pemesanan #{{ $booking->id }}</h1>

        <div class="flex items-center gap-2">
            @if ($booking->status_pembokingan === 'pending')
                <a href="{{ route('bookings.edit', $booking) }}"
                   class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 text-sm font-medium rounded-lg hover:bg-amber-200 transition-colors">
                    Edit
                </a>
            @endif

            @if (in_array($booking->status_pembokingan, ['pending', 'disetujui_level_1']))
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                      data-confirm-delete="Booking #{{ $booking->id }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                        Batalkan
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="bg-white rounded-2xl shadow divide-y divide-gray-100 mb-6">

        {{-- Status badge --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Status Pemesanan</span>
            @php
                $statusMap = [
                    'pending'           => ['label' => 'Pending',           'class' => 'bg-yellow-100 text-yellow-800'],
                    'disetujui_level_1' => ['label' => 'Disetujui Level 1', 'class' => 'bg-blue-100 text-blue-800'],
                    'disetujui_final'   => ['label' => 'Disetujui Final',   'class' => 'bg-green-100 text-green-800'],
                    'ditolak'           => ['label' => 'Ditolak',            'class' => 'bg-red-100 text-red-800'],
                    'dibatalkan'        => ['label' => 'Dibatalkan',         'class' => 'bg-gray-100 text-gray-600'],
                ];
                $s = $statusMap[$booking->status_pembokingan] ?? ['label' => $booking->status_pembokingan, 'class' => 'bg-gray-100 text-gray-600'];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $s['class'] }}">
                {{ $s['label'] }}
            </span>
        </div>

        {{-- Kendaraan --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Kendaraan</span>
            <span class="text-sm text-gray-800 font-semibold">
                {{ $booking->vehicle?->plat_nomor ?? '—' }}
            </span>
        </div>

        {{-- Pengemudi --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Pengemudi</span>
            <span class="text-sm text-gray-800">
                {{ $booking->driver?->nama_driver ?? '—' }}
            </span>
        </div>

        {{-- Tanggal Mulai --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Tanggal Mulai</span>
            <span class="text-sm text-gray-800">
                {{ $booking->tanggal_mulai?->format('d F Y') ?? '—' }}
            </span>
        </div>

        {{-- Tanggal Selesai --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Tanggal Selesai</span>
            <span class="text-sm text-gray-800">
                {{ $booking->tanggal_selesai?->format('d F Y') ?? '—' }}
            </span>
        </div>

        {{-- Keperluan --}}
        <div class="px-6 py-4 flex items-start justify-between gap-8">
            <span class="text-sm font-medium text-gray-500 shrink-0">Keperluan</span>
            <span class="text-sm text-gray-800 text-right">
                {{ $booking->keperluan }}
            </span>
        </div>

        {{-- Konsumsi BBM --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Konsumsi BBM</span>
            <span class="text-sm text-gray-800">
                {{ $booking->konsumsi_bbm !== null ? number_format($booking->konsumsi_bbm, 2) . ' liter' : '—' }}
            </span>
        </div>

        {{-- Approver Level 1 --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Approver Level 1</span>
            <span class="text-sm text-gray-800">
                {{ $booking->approverLevel1?->name ?? '—' }}
            </span>
        </div>

        {{-- Approver Level 2 --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Approver Level 2</span>
            <span class="text-sm text-gray-800">
                {{ $booking->approverLevel2?->name ?? '—' }}
            </span>
        </div>

        {{-- Dibuat pada --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500">Dibuat Pada</span>
            <span class="text-sm text-gray-500">
                {{ $booking->created_at?->format('d F Y, H:i') ?? '—' }}
            </span>
        </div>

    </div>

    {{-- Approval Timeline --}}
    <div class="bg-white rounded-2xl shadow px-6 py-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Timeline Persetujuan</h2>

        <div class="space-y-4">

            {{-- Level 1 --}}
            @php
                $approval1 = $booking->approvals->firstWhere('level', 1);
            @endphp
            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @if ($approval1?->status === 'disetujui')
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                    @elseif ($approval1?->status === 'ditolak')
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </span>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800">
                            Approval Level 1 — {{ $booking->approverLevel1?->name ?? '—' }}
                        </p>
                        @if ($approval1)
                            @php
                                $badgeMap = [
                                    'pending'   => 'bg-yellow-100 text-yellow-700',
                                    'disetujui' => 'bg-green-100 text-green-700',
                                    'ditolak'   => 'bg-red-100 text-red-700',
                                ];
                                $bc = $badgeMap[$approval1->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bc }} capitalize">
                                {{ ucfirst($approval1->status) }}
                            </span>
                        @endif
                    </div>
                    @if ($approval1 && $approval1->status !== 'pending')
                        <p class="mt-1 text-xs text-gray-400">
                            Diproses pada: {{ $approval1->updated_at?->format('d F Y, H:i') ?? '—' }}
                        </p>
                    @endif
                    @if ($approval1?->status === 'ditolak' && $approval1->catatan)
                        <p class="mt-1.5 text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2">
                            <span class="font-medium">Catatan:</span> {{ $approval1->catatan }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Connector line --}}
            <div class="ml-4 pl-0.5 border-l-2 border-gray-200 h-4"></div>

            {{-- Level 2 --}}
            @php
                $approval2 = $booking->approvals->firstWhere('level', 2);
            @endphp
            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @if ($approval2?->status === 'disetujui')
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                    @elseif ($approval2?->status === 'ditolak')
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </span>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800">
                            Approval Level 2 — {{ $booking->approverLevel2?->name ?? '—' }}
                        </p>
                        @if ($approval2)
                            @php
                                $badgeMap = [
                                    'pending'   => 'bg-yellow-100 text-yellow-700',
                                    'disetujui' => 'bg-green-100 text-green-700',
                                    'ditolak'   => 'bg-red-100 text-red-700',
                                ];
                                $bc2 = $badgeMap[$approval2->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bc2 }} capitalize">
                                {{ ucfirst($approval2->status) }}
                            </span>
                        @endif
                    </div>
                    @if ($approval2 && $approval2->status !== 'pending')
                        <p class="mt-1 text-xs text-gray-400">
                            Diproses pada: {{ $approval2->updated_at?->format('d F Y, H:i') ?? '—' }}
                        </p>
                    @endif
                    @if ($approval2?->status === 'ditolak' && $approval2->catatan)
                        <p class="mt-1.5 text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2">
                            <span class="font-medium">Catatan:</span> {{ $approval2->catatan }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Back link --}}
    <div class="mt-6">
        <a href="{{ route('bookings.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke daftar pemesanan
        </a>
    </div>

</div>
@endsection

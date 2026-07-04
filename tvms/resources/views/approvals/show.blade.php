@extends('layouts.app')

@section('title', 'Detail Pengajuan #' . $booking->id)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('approvals.index') }}" class="hover:text-indigo-600 transition-colors">Pengajuan Saya</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-medium">Detail #{{ $booking->id }}</span>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Detail Pengajuan #{{ $booking->id }}</h1>

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
    <div class="bg-white rounded-2xl shadow px-6 py-6 mb-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Timeline Persetujuan</h2>

        <div class="space-y-4">

            {{-- Level 1 --}}
            @php $approval1 = $booking->approvals->firstWhere('level', 1); @endphp
            <div class="flex items-start gap-4">
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
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800">
                            Approval Level 1 — {{ $booking->approverLevel1?->name ?? '—' }}
                        </p>
                        @if ($approval1)
                            @php
                                $badgeMap = ['pending' => 'bg-yellow-100 text-yellow-700', 'disetujui' => 'bg-green-100 text-green-700', 'ditolak' => 'bg-red-100 text-red-700'];
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

            <div class="ml-4 pl-0.5 border-l-2 border-gray-200 h-4"></div>

            {{-- Level 2 --}}
            @php $approval2 = $booking->approvals->firstWhere('level', 2); @endphp
            <div class="flex items-start gap-4">
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
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800">
                            Approval Level 2 — {{ $booking->approverLevel2?->name ?? '—' }}
                        </p>
                        @if ($approval2)
                            @php
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

    {{-- Action Section --}}
    @php
        // Optimization 3: L2 approver cannot act until L1 is approved
        $level1Approved = $approval1?->status === 'disetujui';
        $blockedByLevel1 = $myApproval && $myApproval->level === 2 && ! $level1Approved;
    @endphp

    @if ($myApproval && ! $blockedByLevel1)
        <div class="bg-white rounded-2xl shadow px-6 py-6 mb-6">
            <h2 class="text-base font-bold text-gray-800 mb-1">Tindakan Anda</h2>
            <p class="text-sm text-gray-500 mb-5">
                Anda ditugaskan sebagai Approver
                <span class="font-semibold text-gray-700">Level {{ $myApproval->level }}</span>
                untuk pemesanan ini.
            </p>

            <div class="flex flex-col gap-6">

                {{-- Approve --}}
                <form method="POST" action="{{ route('approvals.approve', $booking) }}"
                      onsubmit="return confirm('Yakin ingin menyetujui pemesanan ini?')">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Setujui Pemesanan
                    </button>
                </form>

                <div class="border-t border-gray-200"></div>

                {{-- Reject — tombol disabled jika textarea masih kosong (Optimization 1) --}}
                <form method="POST" action="{{ route('approvals.reject', $booking) }}"
                      onsubmit="return confirm('Yakin ingin menolak pemesanan ini?')">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="catatan" class="text-sm font-medium text-gray-700">
                            Catatan Penolakan <span class="text-red-500">(Wajib)</span>
                        </label>
                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="3"
                            maxlength="500"
                            placeholder="Tuliskan alasan penolakan (maks 500 karakter)"
                            oninput="toggleRejectBtn(this)"
                            class="block w-full rounded-lg border {{ $errors->has('catatan') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition"
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        <button
                            id="btn-tolak"
                            type="submit"
                            disabled
                            class="w-full mt-2 inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold rounded-lg shadow transition-colors
                                   bg-red-600 hover:bg-red-700 text-white
                                   disabled:opacity-40 disabled:cursor-not-allowed disabled:pointer-events-none"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Tolak Pemesanan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    @elseif ($myApproval && $blockedByLevel1)
        {{-- L2 approver: L1 belum selesai --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl px-6 py-5 mb-6 flex items-start gap-3">
            <svg class="h-5 w-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-blue-800 font-medium">
                Menunggu persetujuan Approver Level 1 terlebih dahulu sebelum Anda dapat mengambil tindakan.
            </p>
        </div>

    @else
        {{-- Bukan approver, atau approval sudah diproses --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-5 mb-6 flex items-start gap-3">
            <svg class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm text-amber-800 font-medium">
                Pengajuan ini sudah diproses atau Anda tidak berwenang.
            </p>
        </div>
    @endif

    {{-- Back link --}}
    <div class="mt-2">
        <a href="{{ route('approvals.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke daftar pengajuan
        </a>
    </div>

</div>

{{-- Optimization 1: disable/enable reject button based on textarea content --}}
<script>
function toggleRejectBtn(textarea) {
    const btn = document.getElementById('btn-tolak');
    if (btn) {
        btn.disabled = textarea.value.trim().length === 0;
    }
}

// On page load: enable button if textarea already has content (e.g. after validation error)
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('catatan');
    const btn = document.getElementById('btn-tolak');
    if (textarea && btn) {
        btn.disabled = textarea.value.trim().length === 0;
    }
});
</script>
@endsection

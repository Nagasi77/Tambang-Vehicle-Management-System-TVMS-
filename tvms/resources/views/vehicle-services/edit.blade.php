@extends('layouts.app')

@section('title', 'Edit Servis: ' . $vehicle->plat_nomor)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Page header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('vehicles.services.index', $vehicle) }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Riwayat Servis</h1>
            <p class="mt-0.5 text-sm text-gray-500">Kendaraan: <span class="font-medium text-gray-700">{{ $vehicle->plat_nomor }}</span></p>
        </div>
    </div>

    {{-- Form card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-8">
        <form method="POST" action="{{ route('vehicles.services.update', [$vehicle, $service]) }}" novalidate>
            @csrf
            @method('PUT')

            {{-- Tanggal Servis --}}
            <div class="mb-6">
                <label for="tanggal_service" class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Servis <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    id="tanggal_service"
                    name="tanggal_service"
                    value="{{ old('tanggal_service', $service->tanggal_service?->format('Y-m-d')) }}"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('tanggal_service') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('tanggal_service')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-8">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi Servis <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    maxlength="1000"
                    placeholder="Tuliskan pekerjaan servis yang dilakukan..."
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('deskripsi') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >{{ old('deskripsi', $service->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">Maksimal 1000 karakter.</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('vehicles.services.index', $vehicle) }}"
                   class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                    Perbarui
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

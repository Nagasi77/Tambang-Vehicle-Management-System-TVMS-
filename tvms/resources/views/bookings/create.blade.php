@extends('layouts.app')

@section('title', 'Buat Pemesanan')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('bookings.index') }}" class="hover:text-indigo-600 transition-colors">Pemesanan</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-medium">Buat Pemesanan</span>
    </div>

    <div class="bg-white rounded-2xl shadow px-8 py-8">
        <h1 class="text-xl font-bold text-gray-800 mb-6">Buat Pemesanan Kendaraan</h1>

        <form method="POST" action="{{ route('bookings.store') }}" novalidate>
            @csrf

            {{-- Kendaraan --}}
            <div class="mb-5">
                <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Kendaraan <span class="text-red-500">*</span>
                </label>
                <select
                    id="vehicle_id"
                    name="vehicle_id"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('vehicle_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled {{ old('vehicle_id') ? '' : 'selected' }}>— Pilih Kendaraan —</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->plat_nomor }}
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pengemudi --}}
            <div class="mb-5">
                <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Pengemudi <span class="text-red-500">*</span>
                </label>
                <select
                    id="driver_id"
                    name="driver_id"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('driver_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled {{ old('driver_id') ? '' : 'selected' }}>— Pilih Pengemudi —</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->nama_driver }}
                        </option>
                    @endforeach
                </select>
                @error('driver_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Approver Level 1 --}}
            <div class="mb-5">
                <label for="approver_level_1_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Approver Level 1 <span class="text-red-500">*</span>
                </label>
                <select
                    id="approver_level_1_id"
                    name="approver_level_1_id"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('approver_level_1_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled {{ old('approver_level_1_id') ? '' : 'selected' }}>— Pilih Approver L1 —</option>
                    @foreach ($approvers as $approver)
                        <option value="{{ $approver->id }}" {{ old('approver_level_1_id') == $approver->id ? 'selected' : '' }}>
                            {{ $approver->name }}
                        </option>
                    @endforeach
                </select>
                @error('approver_level_1_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Approver Level 2 --}}
            <div class="mb-5">
                <label for="approver_level_2_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Approver Level 2 <span class="text-red-500">*</span>
                </label>
                <select
                    id="approver_level_2_id"
                    name="approver_level_2_id"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('approver_level_2_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled {{ old('approver_level_2_id') ? '' : 'selected' }}>— Pilih Approver L2 —</option>
                    @foreach ($approvers as $approver)
                        <option value="{{ $approver->id }}" {{ old('approver_level_2_id') == $approver->id ? 'selected' : '' }}>
                            {{ $approver->name }}
                        </option>
                    @endforeach
                </select>
                @error('approver_level_2_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Mulai --}}
            <div class="mb-5">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    id="tanggal_mulai"
                    name="tanggal_mulai"
                    value="{{ old('tanggal_mulai') }}"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('tanggal_mulai') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('tanggal_mulai')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Selesai --}}
            <div class="mb-5">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Selesai <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    id="tanggal_selesai"
                    name="tanggal_selesai"
                    value="{{ old('tanggal_selesai') }}"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('tanggal_selesai') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('tanggal_selesai')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keperluan --}}
            <div class="mb-5">
                <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-1">
                    Keperluan <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="keperluan"
                    name="keperluan"
                    value="{{ old('keperluan') }}"
                    maxlength="255"
                    placeholder="Contoh: Pengiriman material ke site A"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('keperluan') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('keperluan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konsumsi BBM (opsional) --}}
            <div class="mb-8">
                <label for="konsumsi_bbm" class="block text-sm font-medium text-gray-700 mb-1">
                    Konsumsi BBM (liter)
                    <span class="text-gray-400 text-xs font-normal ml-1">opsional</span>
                </label>
                <input
                    type="number"
                    id="konsumsi_bbm"
                    name="konsumsi_bbm"
                    value="{{ old('konsumsi_bbm') }}"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('konsumsi_bbm') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('konsumsi_bbm')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Simpan
                </button>
                <a href="{{ route('bookings.index') }}"
                   class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

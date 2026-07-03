@extends('layouts.app')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb / Back --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('vehicles.index') }}" class="hover:text-indigo-600 transition-colors">Kendaraan</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-medium">Tambah Kendaraan</span>
    </div>

    <div class="bg-white rounded-2xl shadow px-8 py-8">
        <h1 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Kendaraan</h1>

        <form method="POST" action="{{ route('vehicles.store') }}" novalidate>
            @csrf

            {{-- Plat Nomor --}}
            <div class="mb-5">
                <label for="plat_nomor" class="block text-sm font-medium text-gray-700 mb-1">
                    Plat Nomor <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="plat_nomor"
                    name="plat_nomor"
                    value="{{ old('plat_nomor') }}"
                    maxlength="20"
                    placeholder="Contoh: B 1234 ABC"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('plat_nomor') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('plat_nomor')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis --}}
            <div class="mb-5">
                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">
                    Jenis Kendaraan <span class="text-red-500">*</span>
                </label>
                <select
                    id="jenis"
                    name="jenis"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('jenis') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled {{ old('jenis') ? '' : 'selected' }}>— Pilih Jenis —</option>
                    <option value="angkutan_orang" {{ old('jenis') === 'angkutan_orang' ? 'selected' : '' }}>Angkutan Orang</option>
                    <option value="angkutan_barang" {{ old('jenis') === 'angkutan_barang' ? 'selected' : '' }}>Angkutan Barang</option>
                </select>
                @error('jenis')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Kepemilikan --}}
            <div class="mb-8">
                <label for="status_kepemilikan" class="block text-sm font-medium text-gray-700 mb-1">
                    Status Kepemilikan <span class="text-red-500">*</span>
                </label>
                <select
                    id="status_kepemilikan"
                    name="status_kepemilikan"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('status_kepemilikan') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="" disabled {{ old('status_kepemilikan') ? '' : 'selected' }}>— Pilih Status —</option>
                    <option value="milik_sendiri" {{ old('status_kepemilikan') === 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                    <option value="sewa" {{ old('status_kepemilikan') === 'sewa' ? 'selected' : '' }}>Sewa</option>
                </select>
                @error('status_kepemilikan')
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
                <a href="{{ route('vehicles.index') }}"
                   class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

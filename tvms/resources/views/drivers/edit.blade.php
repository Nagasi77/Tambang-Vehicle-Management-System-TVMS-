@extends('layouts.app')

@section('title', 'Edit Pengemudi')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb / Back --}}
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('drivers.index') }}" class="hover:text-indigo-600 transition-colors">Pengemudi</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-700 font-medium">Edit Pengemudi</span>
    </div>

    <div class="bg-white rounded-2xl shadow px-8 py-8">
        <h1 class="text-xl font-bold text-gray-800 mb-1">Edit Data Pengemudi</h1>
        <p class="text-sm text-gray-500 mb-6">Memperbarui data untuk pengemudi <span class="font-medium text-gray-700">{{ $driver->nama_driver }}</span></p>

        <form method="POST" action="{{ route('drivers.update', $driver) }}" novalidate>
            @csrf
            @method('PUT')

            {{-- Nama Driver --}}
            <div class="mb-5">
                <label for="nama_driver" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Pengemudi <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama_driver"
                    name="nama_driver"
                    value="{{ old('nama_driver', $driver->nama_driver) }}"
                    maxlength="100"
                    placeholder="Masukkan nama lengkap pengemudi"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('nama_driver') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('nama_driver')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-8">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Status <span class="text-red-500">*</span>
                </label>
                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                           {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="tersedia" {{ old('status', $driver->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="bertugas" {{ old('status', $driver->status) === 'bertugas' ? 'selected' : '' }}>Bertugas</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                >
                    Perbarui
                </button>
                <a href="{{ route('drivers.index') }}"
                   class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

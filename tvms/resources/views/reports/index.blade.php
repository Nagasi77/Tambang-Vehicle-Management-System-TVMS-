@extends('layouts.app')

@section('title', 'Laporan Pemesanan')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Pemesanan</h1>
        <p class="mt-1 text-sm text-gray-500">Ekspor data pemesanan kendaraan ke format Excel berdasarkan rentang tanggal.</p>
    </div>

    {{-- Export form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('reports.export') }}">
            @csrf

            <div class="space-y-5">

                {{-- Tanggal Mulai --}}
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        value="{{ old('tanggal_mulai') }}"
                        class="block w-full rounded-lg border @error('tanggal_mulai') border-red-400 bg-red-50 @else border-gray-300 @enderror px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    @error('tanggal_mulai')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        value="{{ old('tanggal_selesai') }}"
                        class="block w-full rounded-lg border @error('tanggal_selesai') border-red-400 bg-red-50 @else border-gray-300 @enderror px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    @error('tanggal_selesai')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info note --}}
                <div class="rounded-lg bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-700 flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span>Rentang tanggal maksimal 366 hari. Filter berdasarkan tanggal mulai booking.</span>
                </div>

                {{-- Submit button --}}
                <div class="pt-1">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export Excel
                    </button>
                </div>

            </div>
        </form>
    </div>

</div>
@endsection

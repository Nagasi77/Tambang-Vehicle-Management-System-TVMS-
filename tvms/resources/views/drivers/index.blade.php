@extends('layouts.app')

@section('title', 'Daftar Pengemudi')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengemudi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data pengemudi operasional tambang</p>
    </div>
    <a href="{{ route('drivers.create') }}"
       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition-colors">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengemudi
    </a>
</div>

{{-- Search & Filter Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4 mb-4">
    <form method="GET" action="{{ route('drivers.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama pengemudi..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <select name="status"
                class="py-2 px-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="bertugas" {{ request('status') === 'bertugas' ? 'selected' : '' }}>Bertugas</option>
        </select>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('drivers.index') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    @if ($drivers->isEmpty())
        <div class="px-6 py-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-gray-500 text-sm font-medium">Tidak ada data pengemudi ditemukan.</p>
            @if(request()->hasAny(['search','status']))
                <p class="text-sm mt-1 text-gray-400">Coba ubah filter pencarian Anda.</p>
            @else
                <a href="{{ route('drivers.create') }}" class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                    Tambah pengemudi pertama
                </a>
            @endif
        </div>
    @else
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pengemudi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($drivers as $driver)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400">
                            {{ ($drivers->currentPage() - 1) * $drivers->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $driver->nama_driver }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($driver->status === 'tersedia')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Bertugas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('drivers.edit', $driver) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-800 text-xs font-medium rounded-lg hover:bg-amber-200 transition-colors">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('drivers.destroy', $driver) }}"
                                      data-confirm-delete="{{ $driver->nama_driver }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors"
                                            @if($driver->status === 'bertugas') disabled title="Pengemudi sedang bertugas" @endif>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($drivers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $drivers->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

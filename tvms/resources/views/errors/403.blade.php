@extends('layouts.app')
@section('title', '403 — Akses Ditolak')
@section('content')
<div class="min-h-[50vh] flex flex-col items-center justify-center text-center px-4">
    <div class="text-6xl font-bold text-red-500 mb-4">403</div>
    <h1 class="text-2xl font-semibold text-gray-800 mb-2">Akses Ditolak</h1>
    <p class="text-gray-500 mb-6">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('approvals.index')) : route('login') }}"
       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
        Kembali ke Halaman Utama
    </a>
</div>
@endsection

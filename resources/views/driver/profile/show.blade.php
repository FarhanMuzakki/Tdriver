@extends('layouts.driver')

@section('title', 'Profil Driver')
@section('page-label', 'Profil Driver')

@section('content')

@php
    $activeAssignment = $driver->activeAssignment;
    $vehicle = $activeAssignment?->vehicle;

    $statusClass = match ($driver->driver_status) {
        'active' => 'bg-emerald-100 text-emerald-700',
        'inactive' => 'bg-slate-100 text-slate-600',
        default => 'bg-slate-100 text-slate-600',
    };

    $statusLabel = match ($driver->driver_status) {
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        default => ucfirst($driver->driver_status ?? '-'),
    };
@endphp

<div class="space-y-5">

    {{-- Profil utama --}}
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        <div class="h-24 bg-gradient-to-r from-indigo-600 to-violet-600"></div>

        <div class="-mt-14 px-5 pb-5">

            <div class="flex items-end justify-between gap-4">

                <div>
                    @if ($driver->profile_photo_url)
                        <img
                            src="{{ $driver->profile_photo_url }}"
                            alt="{{ $driver->name }}"
                            class="h-28 w-28 rounded-3xl border-4 border-white bg-white object-cover shadow-lg"
                        >
                    @else
                        <div class="flex h-28 w-28 items-center justify-center rounded-3xl border-4 border-white bg-indigo-100 text-4xl font-bold text-indigo-700 shadow-lg">
                            {{ strtoupper(substr($driver->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <span class="mb-2 inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>

            </div>

            <h1 class="mt-4 text-2xl font-bold text-slate-900">
                {{ $driver->name }}
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                {{ $driver->email }}
            </p>

        </div>

    </section>

    {{-- Informasi driver --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <h2 class="text-base font-bold text-slate-900">
            Informasi Driver
        </h2>

        <div class="mt-4 divide-y divide-slate-100">

            <div class="py-3">
                <p class="text-xs text-slate-400">
                    Nomor Telepon
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $driver->phone ?: '-' }}
                </p>
            </div>

            <div class="py-3">
                <p class="text-xs text-slate-400">
                    Nomor SIM
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $driver->license_number ?: '-' }}
                </p>
            </div>

            <div class="py-3">
                <p class="text-xs text-slate-400">
                    Masa Berlaku SIM
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $driver->license_expiry
                        ? \Carbon\Carbon::parse($driver->license_expiry)->format('d M Y')
                        : '-' }}
                </p>
            </div>

            <div class="py-3">
                <p class="text-xs text-slate-400">
                    Alamat
                </p>

                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">
                    {{ $driver->address ?: '-' }}
                </p>
            </div>

        </div>

    </section>

    {{-- Kendaraan aktif --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Kendaraan Saya
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Assignment kendaraan yang sedang aktif.
                </p>
            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-xl">
                🚗
            </div>

        </div>

        @if ($vehicle)

            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200">

                @if ($vehicle->image_url)
                    <img
                        src="{{ $vehicle->image_url }}"
                        alt="{{ $vehicle->plate_number }}"
                        class="h-44 w-full object-cover"
                    >
                @else
                    <div class="flex h-40 w-full items-center justify-center bg-slate-100 text-5xl">
                        🚗
                    </div>
                @endif

                <div class="p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <p class="text-lg font-bold text-slate-900">
                                {{ $vehicle->plate_number }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $vehicle->brand }}
                                {{ $vehicle->model }}
                            </p>
                        </div>

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                            In Use
                        </span>

                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3">

                        <div class="rounded-xl bg-slate-50 p-3 text-center">
                            <p class="text-[10px] uppercase text-slate-400">
                                Tahun
                            </p>

                            <p class="mt-1 text-xs font-bold text-slate-800">
                                {{ $vehicle->year ?: '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3 text-center">
                            <p class="text-[10px] uppercase text-slate-400">
                                Tipe
                            </p>

                            <p class="mt-1 text-xs font-bold text-slate-800">
                                {{ $vehicle->type ?: '-' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-3 text-center">
                            <p class="text-[10px] uppercase text-slate-400">
                                Warna
                            </p>

                            <p class="mt-1 text-xs font-bold text-slate-800">
                                {{ $vehicle->color ?: '-' }}
                            </p>
                        </div>

                    </div>

                    @if ($activeAssignment?->assigned_at)
                        <p class="mt-4 text-xs text-slate-500">
                            Ditugaskan sejak
                            <span class="font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse(
                                    $activeAssignment->assigned_at
                                )->format('d M Y') }}
                            </span>
                        </p>
                    @endif

                </div>

            </div>

        @else

            <div class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm">
                    🚗
                </div>

                <p class="mt-3 text-sm font-semibold text-slate-800">
                    Belum ada kendaraan
                </p>

                <p class="mt-1 text-xs leading-5 text-slate-500">
                    Admin belum memberikan assignment kendaraan aktif.
                </p>

            </div>

        @endif

    </section>

</div>

@endsection
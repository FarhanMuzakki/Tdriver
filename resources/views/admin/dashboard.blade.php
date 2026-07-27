@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-6">

    {{-- Hero --}}
    <section class="relative overflow-hidden rounded-3xl bg-slate-950 p-6 text-white shadow-xl sm:p-8">

        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 right-32 h-64 w-64 rounded-full bg-violet-500/10 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-300">
                    Fleet Overview
                </p>

                <h1 class="mt-3 text-2xl font-bold tracking-tight sm:text-3xl">
                    Selamat datang, {{ auth()->user()->name }}
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                    Pantau kendaraan, driver, perjalanan, dan maintenance
                    perusahaan dalam satu dashboard.
                </p>

            </div>

            <div class="grid grid-cols-2 gap-3 sm:flex">

                <a
                    href="{{ route('admin.vehicles.create') }}"
                    class="flex items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100"
                >
                    Tambah Kendaraan
                </a>

                <a
                    href="{{ route('admin.assignments.create') }}"
                    class="flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15"
                >
                    Buat Assignment
                </a>

            </div>

        </div>

    </section>

    {{-- Statistik utama --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Kendaraan --}}
        <article class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Kendaraan
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $totalVehicles }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 17h14M6.5 17l-1-5L7.5 7h9l2 5-1 5M7 12h10M8 17v2M16 17v2"/>
                    </svg>
                </div>

            </div>

            <div class="mt-5 grid grid-cols-3 gap-2 text-center">

                <div class="rounded-xl bg-emerald-50 px-2 py-2">
                    <p class="text-sm font-bold text-emerald-700">
                        {{ $vehiclesAvailable }}
                    </p>
                    <p class="text-[10px] text-emerald-600">
                        Tersedia
                    </p>
                </div>

                <div class="rounded-xl bg-blue-50 px-2 py-2">
                    <p class="text-sm font-bold text-blue-700">
                        {{ $vehiclesInUse }}
                    </p>
                    <p class="text-[10px] text-blue-600">
                        Dipakai
                    </p>
                </div>

                <div class="rounded-xl bg-red-50 px-2 py-2">
                    <p class="text-sm font-bold text-red-700">
                        {{ $vehiclesMaintenance }}
                    </p>
                    <p class="text-[10px] text-red-600">
                        Service
                    </p>
                </div>

            </div>

        </article>

        {{-- Driver --}}
        <article class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Driver
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $totalDrivers }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0"/>
                    </svg>
                </div>

            </div>

            <div class="mt-5 flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">

                <span class="text-xs text-slate-500">
                    Driver aktif
                </span>

                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                    {{ $activeDrivers }}
                </span>

            </div>

        </article>

        {{-- Assignment --}}
        <article class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Assignment Aktif
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        {{ $activeAssignments }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H18a3 3 0 010 6h-4.5m-3 6H6a3 3 0 010-6h4.5m-3-3h9"/>
                    </svg>
                </div>

            </div>

            <a
                href="{{ route('admin.assignments.index') }}"
                class="mt-5 flex items-center justify-between rounded-2xl bg-violet-50 px-4 py-3 text-xs font-semibold text-violet-700 transition hover:bg-violet-100"
            >
                Lihat assignment
                <span>→</span>
            </a>

        </article>

        {{-- Maintenance request --}}
        <article class="group rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="text-sm font-medium text-amber-700">
                        Pengajuan Menunggu
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-amber-950">
                        {{ $pendingMaintenanceRequests }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-amber-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.6 2.8 17.5A1.5 1.5 0 004.1 19.8h15.8a1.5 1.5 0 001.3-2.3L13.7 4.6a2 2 0 00-3.4 0Z"/>
                    </svg>
                </div>

            </div>

            <a
                href="{{ route(
                    'admin.maintenance-requests.index',
                    ['status' => 'pending']
                ) }}"
                class="mt-5 flex items-center justify-between rounded-2xl bg-white/80 px-4 py-3 text-xs font-semibold text-amber-800 transition hover:bg-white"
            >
                Proses pengajuan
                <span>→</span>
            </a>

        </article>

    </section>

    {{-- Aktivitas hari ini --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Aktivitas Hari Ini
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Ringkasan operasional {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <a
                href="{{ route('admin.logs.index') }}"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-700"
            >
                Lihat seluruh log →
            </a>

        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            @php
                $todayStats = [
                    [
                        'label' => 'Log Perjalanan',
                        'value' => number_format($todayLogCount, 0, ',', '.'),
                        'suffix' => 'log',
                    ],
                    [
                        'label' => 'Total Jarak',
                        'value' => number_format($todayDistance, 0, ',', '.'),
                        'suffix' => 'km',
                    ],
                    [
                        'label' => 'Biaya Operasional',
                        'value' => 'Rp ' . number_format(
                            $todayOperationalCost,
                            0,
                            ',',
                            '.'
                        ),
                        'suffix' => null,
                    ],
                    [
                        'label' => 'Maintenance Bulan Ini',
                        'value' => 'Rp ' . number_format(
                            $monthlyMaintenanceCost,
                            0,
                            ',',
                            '.'
                        ),
                        'suffix' => null,
                    ],
                ];
            @endphp

            @foreach ($todayStats as $stat)

                <div class="rounded-2xl bg-slate-50 p-4">

                    <p class="text-xs font-medium text-slate-500">
                        {{ $stat['label'] }}
                    </p>

                    <p class="mt-2 text-xl font-bold text-slate-900">
                        {{ $stat['value'] }}

                        @if ($stat['suffix'])
                            <span class="text-xs font-medium text-slate-400">
                                {{ $stat['suffix'] }}
                            </span>
                        @endif
                    </p>

                </div>

            @endforeach

        </div>

    </section>

    {{-- Recent data --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Maintenance requests --}}
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5 sm:px-6">

                <div>
                    <h2 class="font-bold text-slate-900">
                        Pengajuan Maintenance
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Laporan terbaru dari driver
                    </p>
                </div>

                <a
                    href="{{ route('admin.maintenance-requests.index') }}"
                    class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200"
                >
                    Lihat Semua
                </a>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse ($recentMaintenanceRequests as $requestItem)

                    @php
                        $requestStatusLabel = match ($requestItem->status) {
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'completed' => 'Selesai',
                            default => 'Menunggu',
                        };

                        $requestStatusClass = match ($requestItem->status) {
                            'approved' => 'bg-blue-50 text-blue-700',
                            'rejected' => 'bg-red-50 text-red-700',
                            'completed' => 'bg-emerald-50 text-emerald-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp

                    <div class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50 sm:px-6">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a4.5 4.5 0 01-5.86 5.86L4.5 16.5a2.12 2.12 0 103 3l4.34-4.34A4.5 4.5 0 0017.7 9.3l-2.4 2.4-3-3 2.4-2.4Z"/>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-slate-900">
                                {{ $requestItem->vehicle?->plate_number ?? '-' }}
                            </p>

                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $requestItem->issue_type }}
                                •
                                {{ $requestItem->driver?->name ?? '-' }}
                            </p>

                        </div>

                        <span class="shrink-0 rounded-full px-3 py-1 text-[10px] font-semibold {{ $requestStatusClass }}">
                            {{ $requestStatusLabel }}
                        </span>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-medium text-slate-600">
                            Belum ada pengajuan maintenance.
                        </p>
                    </div>

                @endforelse

            </div>

        </article>

        {{-- Recent logs --}}
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5 sm:px-6">

                <div>
                    <h2 class="font-bold text-slate-900">
                        Perjalanan Terbaru
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Log perjalanan terakhir
                    </p>
                </div>

                <a
                    href="{{ route('admin.logs.index') }}"
                    class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200"
                >
                    Lihat Semua
                </a>

            </div>

            <div class="divide-y divide-slate-100">

                @forelse ($recentLogs as $dailyLog)

                    <div class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50 sm:px-6">

                        @if ($dailyLog->vehicle?->image_url)
                            <img
                                src="{{ $dailyLog->vehicle->image_url }}"
                                alt="{{ $dailyLog->vehicle->plate_number }}"
                                class="h-11 w-11 shrink-0 rounded-2xl object-cover"
                            >
                        @else
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                🚗
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-slate-900">
                                {{ $dailyLog->vehicle?->plate_number ?? '-' }}
                            </p>

                            <p class="mt-0.5 truncate text-xs text-slate-500">
                                {{ $dailyLog->destination ?? '-' }}
                                •
                                {{ $dailyLog->driver?->name ?? '-' }}
                            </p>

                        </div>

                        <div class="shrink-0 text-right">

                            <p class="text-xs font-semibold text-slate-600">
                                {{ $dailyLog->log_date?->format('d M Y') ?? '-' }}
                            </p>

                            <p class="mt-1 text-[10px] text-slate-400">
                                {{ number_format(
                                    max(
                                        0,
                                        (int) $dailyLog->end_odometer
                                        - (int) $dailyLog->start_odometer
                                    ),
                                    0,
                                    ',',
                                    '.'
                                ) }} km
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-medium text-slate-600">
                            Belum ada log perjalanan.
                        </p>
                    </div>

                @endforelse

            </div>

        </article>

    </section>

</div>

@endsection
@extends('layouts.driver')

@section('title', 'Detail Perjalanan')
@section('page-label', 'Detail Perjalanan')

@section('content')

@php
    $distance = max(
        0,
        (int) $dailyLog->end_odometer
        - (int) $dailyLog->start_odometer
    );

    $fuelCost = (float) ($dailyLog->fuel_cost ?? 0);
    $tollCost = (float) ($dailyLog->toll_cost ?? 0);
    $parkingCost = (float) ($dailyLog->parking_cost ?? 0);

    $totalCost = $fuelCost + $tollCost + $parkingCost;
    $receiptCount = $dailyLog->receipts?->count() ?? 0;

    $receiptLabels = [
        'fuel' => 'BBM',
        'toll' => 'Tol',
        'parking' => 'Parkir',
        'other' => 'Lainnya',
    ];
@endphp

<div class="space-y-5">

    {{-- Header --}}
    <section class="flex items-start justify-between gap-4">

        <div class="min-w-0">

            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                Daily Log
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-900">
                Detail Perjalanan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Informasi perjalanan yang sudah disimpan.
            </p>

        </div>

        <a
            href="{{ route('driver.logs.edit', $dailyLog) }}"
            class="shrink-0 rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition active:scale-95"
        >
            Edit
        </a>

    </section>

    {{-- Kendaraan --}}
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

        @if ($dailyLog->vehicle?->image_url)

            <img
                src="{{ $dailyLog->vehicle->image_url }}"
                alt="Foto {{ $dailyLog->vehicle->plate_number }}"
                class="h-48 w-full object-cover"
            >

        @else

            <div class="flex h-40 items-center justify-center bg-slate-100 text-5xl">
                🚗
            </div>

        @endif

        <div class="p-5">

            <div class="flex items-start justify-between gap-4">

                <div class="min-w-0">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Kendaraan
                    </p>

                    <h2 class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $dailyLog->vehicle?->plate_number ?? '-' }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ trim(
                            ($dailyLog->vehicle?->brand ?? '') . ' ' .
                            ($dailyLog->vehicle?->model ?? '')
                        ) ?: 'Data kendaraan tidak tersedia' }}
                    </p>

                </div>

                <span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                    {{ $dailyLog->log_date
                        ? \Carbon\Carbon::parse($dailyLog->log_date)->format('d M Y')
                        : '-' }}
                </span>

            </div>

        </div>

    </section>

    {{-- Tujuan dan waktu --}}
    <section class="space-y-5 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <div>

            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Tujuan
            </p>

            <p class="mt-1 text-base font-bold text-slate-900">
                {{ $dailyLog->destination ?: '-' }}
            </p>

        </div>

        <div>

            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Keperluan
            </p>

            <p class="mt-1 text-sm leading-6 text-slate-700">
                {{ $dailyLog->purpose ?: '-' }}
            </p>

        </div>

        <div class="grid grid-cols-2 gap-3">

            <div class="rounded-2xl bg-slate-50 p-4">

                <p class="text-xs text-slate-400">
                    Jam Mulai
                </p>

                <p class="mt-1 font-bold text-slate-900">
                    {{ $dailyLog->start_time
                        ? substr((string) $dailyLog->start_time, 0, 5)
                        : '-' }}
                </p>

            </div>

            <div class="rounded-2xl bg-slate-50 p-4">

                <p class="text-xs text-slate-400">
                    Jam Selesai
                </p>

                <p class="mt-1 font-bold text-slate-900">
                    {{ $dailyLog->end_time
                        ? substr((string) $dailyLog->end_time, 0, 5)
                        : '-' }}
                </p>

            </div>

        </div>

    </section>

    {{-- Odometer --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <h2 class="text-base font-bold text-slate-900">
            Odometer
        </h2>

        <div class="mt-4 grid grid-cols-3 gap-3">

            <div class="rounded-2xl bg-slate-50 p-3 text-center">

                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                    KM Awal
                </p>

                <p class="mt-1 text-sm font-bold text-slate-900">
                    {{ number_format(
                        (int) $dailyLog->start_odometer,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>

            <div class="rounded-2xl bg-slate-50 p-3 text-center">

                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                    KM Akhir
                </p>

                <p class="mt-1 text-sm font-bold text-slate-900">
                    {{ number_format(
                        (int) $dailyLog->end_odometer,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>

            <div class="rounded-2xl bg-indigo-50 p-3 text-center">

                <p class="text-[10px] font-medium uppercase tracking-wide text-indigo-500">
                    Jarak
                </p>

                <p class="mt-1 text-sm font-bold text-indigo-700">
                    {{ number_format($distance, 0, ',', '.') }} km
                </p>

            </div>

        </div>

    </section>

    {{-- Pengeluaran --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-base font-bold text-slate-900">
                    Pengeluaran
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Ringkasan biaya perjalanan.
                </p>

            </div>

            <div class="rounded-2xl bg-indigo-50 px-3 py-2 text-right">

                <p class="text-[10px] uppercase tracking-wide text-indigo-500">
                    Total
                </p>

                <p class="text-sm font-bold text-indigo-700">
                    Rp {{ number_format($totalCost, 0, ',', '.') }}
                </p>

            </div>

        </div>

        <div class="mt-5 divide-y divide-slate-100">

            <div class="flex items-center justify-between py-3">

                <span class="text-sm text-slate-500">
                    Biaya BBM
                </span>

                <span class="text-sm font-semibold text-slate-900">
                    Rp {{ number_format($fuelCost, 0, ',', '.') }}
                </span>

            </div>

            <div class="flex items-center justify-between py-3">

                <span class="text-sm text-slate-500">
                    Biaya Tol
                </span>

                <span class="text-sm font-semibold text-slate-900">
                    Rp {{ number_format($tollCost, 0, ',', '.') }}
                </span>

            </div>

            <div class="flex items-center justify-between py-3">

                <span class="text-sm text-slate-500">
                    Biaya Parkir
                </span>

                <span class="text-sm font-semibold text-slate-900">
                    Rp {{ number_format($parkingCost, 0, ',', '.') }}
                </span>

            </div>

        </div>

    </section>
{{-- Daftar struk --}}
<section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="flex items-center justify-between gap-3">

        <div>
            <h2 class="text-base font-bold text-slate-900">
                Bukti Struk
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                {{ $receiptCount }} bukti pengeluaran tersimpan.
            </p>
        </div>

        <a
            href="{{ route('driver.logs.edit', $dailyLog) }}#receipts"
            class="shrink-0 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-600"
        >
            Kelola
        </a>

    </div>

    @if ($dailyLog->receipts?->isNotEmpty())

        <div class="mt-5 grid grid-cols-2 gap-3">

            @foreach ($dailyLog->receipts as $receipt)

                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                    @if ($receipt->file_url)

                        <a
                            href="{{ $receipt->file_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="block"
                        >
                            <img
                                src="{{ $receipt->file_url }}"
                                alt="Struk {{ $receiptLabels[$receipt->type] ?? 'Pengeluaran' }}"
                                class="h-32 w-full object-cover"
                            >
                        </a>

                    @else

                        <div class="flex h-32 items-center justify-center bg-slate-100 text-3xl">
                            🧾
                        </div>

                    @endif

                    <div class="p-3">

                        <p class="text-xs font-bold text-slate-800">
                            {{ $receiptLabels[$receipt->type]
                                ?? ucfirst($receipt->type ?? 'Struk') }}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Rp {{ number_format(
                                (float) ($receipt->amount ?? 0),
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>

                    </div>

                </article>

            @endforeach

        </div>

    @else

        <div class="mt-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-7 text-center">

            <div class="text-3xl">
                🧾
            </div>

            <p class="mt-2 text-sm font-semibold text-slate-700">
                Belum ada struk
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Struk dapat ditambahkan melalui halaman edit perjalanan.
            </p>

            <a
                href="{{ route('driver.logs.edit', $dailyLog) }}#receipts"
                class="mt-4 inline-flex rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white"
            >
                Tambah Struk
            </a>

        </div>

    @endif

</section>
    {{-- Catatan --}}
    @if ($dailyLog->notes)

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                Catatan
            </p>

            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">
                {{ $dailyLog->notes }}
            </p>

        </section>

    @endif

    {{-- Action --}}
    <section class="grid grid-cols-2 gap-3">

        <a
            href="{{ route('driver.logs.index') }}"
            class="flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition active:scale-95"
        >
            Kembali
        </a>

        <a
            href="{{ route('driver.logs.edit', $dailyLog) }}"
            class="flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition active:scale-95"
        >
            Edit Log
        </a>

    </section>

</div>

@endsection
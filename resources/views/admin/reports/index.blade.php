@extends('layouts.admin')

@section('title', 'Laporan Operasional')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Laporan Operasional
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Rekap perjalanan, pengeluaran, dan maintenance kendaraan.
            </p>
        </div>

        <a
            href="{{ route(
                'admin.reports.export-csv',
                request()->query()
            ) }}"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
        >
            📊  Export CSV
        </a>
<a
    href="{{ route(
        'admin.reports.export-excel',
        request()->query()
    ) }}"
    class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
>
    📊 Export Excel
</a>
    </div>

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="text-sm font-semibold text-red-700">
                Filter tidak valid.
            </p>

            <ul class="mt-2 space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- Filter --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.reports.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4"
        >

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                    Tanggal Awal
                </label>

                <input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                >
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                    Tanggal Akhir
                </label>

                <input
                    type="date"
                    name="end_date"
                    value="{{ request('end_date') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                >
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                    Kendaraan
                </label>

                <select
                    name="vehicle_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                >
                    <option value="">Semua kendaraan</option>

                    @foreach ($vehicles as $vehicle)
                        <option
                            value="{{ $vehicle->id }}"
                            @selected(request('vehicle_id') === $vehicle->id)
                        >
                            {{ $vehicle->plate_number }}
                            —
                            {{ $vehicle->brand }}
                            {{ $vehicle->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">
                    Driver
                </label>

                <select
                    name="driver_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm"
                >
                    <option value="">Semua driver</option>

                    @foreach ($drivers as $driver)
                        <option
                            value="{{ $driver->id }}"
                            @selected(request('driver_id') === $driver->id)
                        >
                            {{ $driver->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 md:col-span-2 xl:col-span-4">

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('admin.reports.index') }}"
                    class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </section>

    {{-- Ringkasan --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Total Perjalanan
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($totalTrips, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Total Jarak
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($totalDistance, 0, ',', '.') }}
                <span class="text-sm font-medium text-slate-400">km</span>
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Biaya Operasional
            </p>

            <p class="mt-2 text-xl font-bold text-slate-900">
                Rp {{ number_format($totalOperationalCost, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Biaya Maintenance
            </p>

            <p class="mt-2 text-xl font-bold text-slate-900">
                Rp {{ number_format($totalMaintenanceCost, 0, ',', '.') }}
            </p>
        </div>

    </section>

    {{-- Rincian biaya --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">
                BBM
            </p>

            <p class="mt-2 font-bold text-slate-900">
                Rp {{ number_format($totalFuelCost, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">
                Tol
            </p>

            <p class="mt-2 font-bold text-slate-900">
                Rp {{ number_format($totalTollCost, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">
                Parkir
            </p>

            <p class="mt-2 font-bold text-slate-900">
                Rp {{ number_format($totalParkingCost, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
            <p class="text-xs uppercase tracking-wide text-indigo-500">
                Total Keseluruhan
            </p>

            <p class="mt-2 font-bold text-indigo-900">
                Rp {{ number_format($grandTotal, 0, ',', '.') }}
            </p>
        </div>

    </section>

    {{-- Tabel --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">
                Rincian Perjalanan
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                {{ $dailyLogs->total() }} data perjalanan ditemukan.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-sm">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 text-left">Tanggal</th>
                        <th class="px-5 py-4 text-left">Driver</th>
                        <th class="px-5 py-4 text-left">Kendaraan</th>
                        <th class="px-5 py-4 text-left">Tujuan</th>
                        <th class="px-5 py-4 text-right">Jarak</th>
                        <th class="px-5 py-4 text-right">BBM</th>
                        <th class="px-5 py-4 text-right">Tol</th>
                        <th class="px-5 py-4 text-right">Parkir</th>
                        <th class="px-5 py-4 text-right">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($dailyLogs as $dailyLog)

                        @php
                            $distance = 0;

                            if (
                                $dailyLog->start_odometer !== null &&
                                $dailyLog->end_odometer !== null
                            ) {
                                $distance = max(
                                    0,
                                    $dailyLog->end_odometer
                                    - $dailyLog->start_odometer
                                );
                            }

                            $rowTotal =
                                (float) $dailyLog->fuel_cost +
                                (float) $dailyLog->toll_cost +
                                (float) $dailyLog->parking_cost;
                        @endphp

                        <tr class="hover:bg-slate-50">

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $dailyLog->log_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $dailyLog->driver?->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">
                                    {{ $dailyLog->vehicle?->plate_number ?? '-' }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $dailyLog->vehicle?->brand ?? '' }}
                                    {{ $dailyLog->vehicle?->model ?? '' }}
                                </p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-900">
                                    {{ $dailyLog->destination ?? '-' }}
                                </p>

                                <p class="max-w-xs truncate text-xs text-slate-500">
                                    {{ $dailyLog->purpose ?? '-' }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                {{ number_format($distance, 0, ',', '.') }} km
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                Rp {{ number_format($dailyLog->fuel_cost, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                Rp {{ number_format($dailyLog->toll_cost, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                Rp {{ number_format($dailyLog->parking_cost, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right font-semibold">
                                Rp {{ number_format($rowTotal, 0, ',', '.') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="9"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                Tidak ada data laporan untuk filter tersebut.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($dailyLogs->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $dailyLogs->links() }}
            </div>
        @endif

    </section>

</div>

@endsection
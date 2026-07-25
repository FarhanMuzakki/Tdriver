@extends('layouts.driver')

@section('title', 'Edit Perjalanan')
@section('page-label', 'Edit Perjalanan')

@section('content')

@php
    $receiptLabels = [
        'fuel' => 'BBM',
        'toll' => 'Tol',
        'parking' => 'Parkir',
        'other' => 'Lainnya',
    ];

    $fuelCost = (float) ($dailyLog->fuel_cost ?? 0);
    $tollCost = (float) ($dailyLog->toll_cost ?? 0);
    $parkingCost = (float) ($dailyLog->parking_cost ?? 0);

    $totalCost = $fuelCost + $tollCost + $parkingCost;
@endphp

<div class="space-y-5">

    {{-- Header --}}
    <section class="flex items-start justify-between gap-4">

        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                Daily Log
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-900">
                Edit Perjalanan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Perbarui perjalanan, kelola struk, dan lihat total pengeluaran.
            </p>
        </div>

        <a
            href="{{ route('driver.logs.show', $dailyLog) }}"
            class="shrink-0 rounded-2xl bg-slate-100 px-4 py-2.5 text-xs font-semibold text-slate-700"
        >
            Detail
        </a>

    </section>

    {{-- Error --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4">

            <p class="text-sm font-semibold text-red-700">
                Data belum dapat diproses.
            </p>

            <ul class="mt-2 space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{--
        Form update utama sengaja tidak membungkus section.
        Semua input update memakai form="daily-log-form".
        Ini mencegah form upload struk bersarang.
    --}}
    <form
        id="daily-log-form"
        method="POST"
        action="{{ route('driver.logs.update', $dailyLog) }}"
    >
        @csrf
        @method('PUT')
    </form>

    {{-- Informasi perjalanan --}}
    <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <h2 class="font-bold text-slate-900">
            Informasi Perjalanan
        </h2>

        <div>
            <label
                for="vehicle_id"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Kendaraan
            </label>

            <select
                id="vehicle_id"
                name="vehicle_id"
                form="daily-log-form"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
            >
                @foreach ($assignments as $assignment)

                    <option
                        value="{{ $assignment->vehicle_id }}"
                        @selected(
                            old(
                                'vehicle_id',
                                $dailyLog->vehicle_id
                            ) === $assignment->vehicle_id
                        )
                    >
                        {{ $assignment->vehicle?->plate_number }}
                        —
                        {{ $assignment->vehicle?->brand }}
                        {{ $assignment->vehicle?->model }}
                    </option>

                @endforeach
            </select>

            @error('vehicle_id')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label
                for="log_date"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Tanggal Perjalanan
            </label>

            <input
                id="log_date"
                type="date"
                name="log_date"
                form="daily-log-form"
                value="{{ old(
                    'log_date',
                    \Carbon\Carbon::parse(
                        $dailyLog->log_date
                    )->format('Y-m-d')
                ) }}"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
            >

            @error('log_date')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">

            <div>
                <label
                    for="start_time"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Jam Mulai
                </label>

                <input
                    id="start_time"
                    type="time"
                    name="start_time"
                    form="daily-log-form"
                    value="{{ old(
                        'start_time',
                        $dailyLog->start_time
                            ? substr(
                                (string) $dailyLog->start_time,
                                0,
                                5
                            )
                            : ''
                    ) }}"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                >

                @error('start_time')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label
                    for="end_time"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Jam Selesai
                </label>

                <input
                    id="end_time"
                    type="time"
                    name="end_time"
                    form="daily-log-form"
                    value="{{ old(
                        'end_time',
                        $dailyLog->end_time
                            ? substr(
                                (string) $dailyLog->end_time,
                                0,
                                5
                            )
                            : ''
                    ) }}"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                >

                @error('end_time')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <div>
            <label
                for="destination"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Tujuan
            </label>

            <input
                id="destination"
                type="text"
                name="destination"
                form="daily-log-form"
                value="{{ old(
                    'destination',
                    $dailyLog->destination
                ) }}"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
            >

            @error('destination')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label
                for="purpose"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Keperluan
            </label>

            <input
                id="purpose"
                type="text"
                name="purpose"
                form="daily-log-form"
                value="{{ old(
                    'purpose',
                    $dailyLog->purpose
                ) }}"
                required
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
            >

            @error('purpose')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

    </section>

    {{-- Odometer --}}
    <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <h2 class="font-bold text-slate-900">
            Odometer
        </h2>

        <div class="grid grid-cols-2 gap-3">

            <div>
                <label
                    for="start_odometer"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    KM Awal
                </label>

                <input
                    id="start_odometer"
                    type="number"
                    name="start_odometer"
                    form="daily-log-form"
                    value="{{ old(
                        'start_odometer',
                        $dailyLog->start_odometer
                    ) }}"
                    min="0"
                    required
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                >

                @error('start_odometer')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label
                    for="end_odometer"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    KM Akhir
                </label>

                <input
                    id="end_odometer"
                    type="number"
                    name="end_odometer"
                    form="daily-log-form"
                    value="{{ old(
                        'end_odometer',
                        $dailyLog->end_odometer
                    ) }}"
                    min="0"
                    required
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                >

                @error('end_odometer')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

    </section>

    {{-- Kelola struk --}}
    <section
        id="receipts"
        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm"
    >

        <div class="flex items-center justify-between gap-3">

            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Kelola Struk
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Masukkan nominal dan unggah bukti pengeluaran.
                </p>
            </div>

            <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                {{ $dailyLog->receipts?->count() ?? 0 }} file
            </span>

        </div>

        {{-- Upload struk --}}
        <form
            method="POST"
            action="{{ route(
                'driver.logs.receipts.store',
                $dailyLog
            ) }}"
            enctype="multipart/form-data"
            class="mt-5 space-y-4 rounded-2xl bg-slate-50 p-4"
        >
            @csrf

            <div>
                <label
                    for="receipt_type"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Jenis Pengeluaran
                </label>

                <select
                    id="receipt_type"
                    name="type"
                    required
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50"
                >
                    <option value="">
                        Pilih jenis pengeluaran
                    </option>

                    <option
                        value="fuel"
                        @selected(old('type') === 'fuel')
                    >
                        BBM
                    </option>

                    <option
                        value="toll"
                        @selected(old('type') === 'toll')
                    >
                        Tol
                    </option>

                    <option
                        value="parking"
                        @selected(old('type') === 'parking')
                    >
                        Parkir
                    </option>

                    <option
                        value="other"
                        @selected(old('type') === 'other')
                    >
                        Lainnya
                    </option>
                </select>

                @error('type')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label
                    for="receipt_amount"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Nominal
                </label>

                <div class="flex overflow-hidden rounded-2xl border border-slate-200 bg-white focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-50">

                    <span class="flex items-center border-r border-slate-200 px-4 text-sm text-slate-500">
                        Rp
                    </span>

                    <input
                        id="receipt_amount"
                        type="number"
                        name="amount"
                        value="{{ old('amount', 0) }}"
                        min="0"
                        required
                        class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm outline-none"
                    >

                </div>

                @error('amount')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label
                    for="receipt"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Foto Struk
                </label>

                <input
                    id="receipt"
                    type="file"
                    name="receipt"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full rounded-2xl border border-slate-200 bg-white p-3 text-sm text-slate-600 file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-indigo-600"
                >

                @error('receipt')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition active:scale-95"
            >
                Upload Struk
            </button>

        </form>

        {{-- Daftar struk --}}
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
                                    ?? ucfirst(
                                        $receipt->type ?? 'Struk'
                                    ) }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                Rp {{ number_format(
                                    (float) ($receipt->amount ?? 0),
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </p>

                            <form
                                method="POST"
                                action="{{ route(
                                    'driver.receipts.destroy',
                                    $receipt
                                ) }}"
                                class="mt-3"
                                onsubmit="return confirm('Hapus struk ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="w-full rounded-xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                >
                                    Hapus
                                </button>

                            </form>

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
                    Masukkan nominal dan unggah struk melalui form di atas.
                </p>

            </div>

        @endif

    </section>

    {{-- Ringkasan pengeluaran --}}
    <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between gap-3">

            <div>
                <h2 class="font-bold text-slate-900">
                    Ringkasan Pengeluaran
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Nominal dihitung otomatis dari struk yang diunggah.
                </p>
            </div>

            <div class="shrink-0 rounded-2xl bg-indigo-50 px-3 py-2 text-right">

                <p class="text-[10px] uppercase tracking-wide text-indigo-500">
                    Total
                </p>

                <p class="text-sm font-bold text-indigo-700">
                    Rp {{ number_format(
                        $totalCost,
                        0,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>

        </div>

        @foreach ([
            'fuel_cost' => [
                'label' => 'Biaya BBM',
                'value' => $fuelCost,
            ],
            'toll_cost' => [
                'label' => 'Biaya Tol',
                'value' => $tollCost,
            ],
            'parking_cost' => [
                'label' => 'Biaya Parkir',
                'value' => $parkingCost,
            ],
        ] as $field => $expense)

            <div>
                <label
                    for="{{ $field }}"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    {{ $expense['label'] }}
                </label>

                <div class="flex overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">

                    <span class="flex items-center border-r border-slate-200 px-4 text-sm text-slate-500">
                        Rp
                    </span>

                    <input
                        id="{{ $field }}"
                        type="number"
                        name="{{ $field }}"
                        form="daily-log-form"
                        value="{{ old(
                            $field,
                            $expense['value']
                        ) }}"
                        readonly
                        class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm font-semibold text-slate-600 outline-none"
                    >

                </div>
            </div>

        @endforeach

    </section>

    {{-- Catatan --}}
    <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

        <div>
            <label
                for="notes"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Catatan
            </label>

            <textarea
                id="notes"
                name="notes"
                form="daily-log-form"
                rows="4"
                placeholder="Catatan tambahan perjalanan..."
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
            >{{ old('notes', $dailyLog->notes) }}</textarea>

            @error('notes')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

    </section>

    {{-- Action --}}
    <div class="grid grid-cols-2 gap-3">

        <a
            href="{{ route('driver.logs.index') }}"
            class="flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700"
        >
            Kembali
        </a>

        <button
            type="submit"
            form="daily-log-form"
            class="rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200"
        >
            Simpan Perubahan
        </button>

    </div>

</div>

@endsection
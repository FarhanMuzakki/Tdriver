@extends('layouts.driver')

@section('title', 'Tambah Perjalanan')
@section('page-label', 'Tambah Perjalanan')

@section('content')

<div class="space-y-5">

    <section>
        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
            Daily Log
        </p>

        <h1 class="mt-1 text-2xl font-bold text-slate-900">
            Tambah Perjalanan
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Catat perjalanan kendaraan yang sedang ditugaskan kepada Anda.
        </p>
    </section>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
            <p class="text-sm font-semibold text-red-700">
                Data belum dapat disimpan.
            </p>

            <ul class="mt-2 space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($assignments->isEmpty())
        <section class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-3xl">
                🚗
            </div>

            <h2 class="mt-4 text-base font-bold text-slate-900">
                Tidak ada kendaraan aktif
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Anda belum mendapat assignment kendaraan atau kendaraan sedang maintenance.
            </p>

            <a
                href="{{ route('driver.logs.index') }}"
                class="mt-5 inline-flex rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700"
            >
                Kembali
            </a>

        </section>
    @else

        <form
            method="POST"
            action="{{ route('driver.logs.store') }}"
            class="space-y-5"
        >
            @csrf

            <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

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
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                    >
                        <option value="">
                            Pilih kendaraan
                        </option>

                        @foreach ($assignments as $assignment)
                            <option
                                value="{{ $assignment->vehicle_id }}"
                                @selected(old('vehicle_id') === $assignment->vehicle_id)
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
                        value="{{ old('log_date', now()->format('Y-m-d')) }}"
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
                            value="{{ old('start_time') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                        >
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
                            value="{{ old('end_time') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                        >
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
                        value="{{ old('destination') }}"
                        placeholder="Contoh: Kantor pusat Jakarta"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                    >
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
                        value="{{ old('purpose') }}"
                        placeholder="Contoh: Mengantar dokumen"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                    >
                </div>

            </section>

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
                            value="{{ old('start_odometer') }}"
                            min="0"
                            required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                        >
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
                            value="{{ old('end_odometer') }}"
                            min="0"
                            required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                        >
                    </div>

                </div>

            </section>

            <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">

                <h2 class="font-bold text-slate-900">
                    Pengeluaran
                </h2>

                @foreach ([
                    'fuel_cost' => 'Biaya BBM',
                    'toll_cost' => 'Biaya Tol',
                    'parking_cost' => 'Biaya Parkir',
                ] as $field => $label)

                    <div>
                        <label
                            for="{{ $field }}"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            {{ $label }}
                        </label>

                        <div class="flex overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-50">

                            <span class="flex items-center border-r border-slate-200 px-4 text-sm text-slate-500">
                                Rp
                            </span>

                            <input
                                id="{{ $field }}"
                                type="number"
                                name="{{ $field }}"
                                value="{{ old($field, 0) }}"
                                min="0"
                                class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm outline-none"
                            >

                        </div>
                    </div>

                @endforeach

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
                        rows="4"
                        placeholder="Catatan tambahan perjalanan..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                    >{{ old('notes') }}</textarea>
                </div>

            </section>

            <div class="grid grid-cols-2 gap-3">

                <a
                    href="{{ route('driver.logs.index') }}"
                    class="flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200"
                >
                    Simpan Log
                </button>

            </div>

        </form>

    @endif

</div>

@endsection
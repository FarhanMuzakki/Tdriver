@extends('layouts.admin')

@section('title', 'Tambah Log Perjalanan')

@section('content')

<div class="mx-auto max-w-4xl">

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                Tambah Log Perjalanan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Catat perjalanan dan biaya operasional kendaraan.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.logs.store') }}"
            class="space-y-6"
        >
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Driver --}}
                <div>
                    <label
                        for="driver_id"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Driver <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="driver_id"
                        name="driver_id"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >
                        <option value="">
                            Pilih driver
                        </option>

                        @foreach ($drivers as $driver)
                            <option
                                value="{{ $driver->id }}"
                                {{ old('driver_id') == $driver->id ? 'selected' : '' }}
                            >
                                {{ $driver->name }} — {{ $driver->email }}
                            </option>
                        @endforeach
                    </select>

                    @error('driver_id')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kendaraan --}}
                <div>
                    <label
                        for="vehicle_id"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Kendaraan <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="vehicle_id"
                        name="vehicle_id"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >
                        <option value="">
                            Pilih kendaraan
                        </option>

                        @foreach ($vehicles as $vehicle)
                            <option
                                value="{{ $vehicle->id }}"
                                {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                                —
                                {{ $vehicle->brand ?? '' }}
                                {{ $vehicle->model ?? $vehicle->type }}
                            </option>
                        @endforeach
                    </select>

                    @error('vehicle_id')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label
                        for="log_date"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Tanggal Perjalanan <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="log_date"
                        type="date"
                        name="log_date"
                        value="{{ old('log_date', now()->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >

                    @error('log_date')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Spacer --}}
                <div></div>

                {{-- Jam mulai --}}
                <div>
                    <label
                        for="start_time"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Jam Mulai
                    </label>

                    <input
                        id="start_time"
                        type="time"
                        name="start_time"
                        value="{{ old('start_time') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                    @error('start_time')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Jam selesai --}}
                <div>
                    <label
                        for="end_time"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Jam Selesai
                    </label>

                    <input
                        id="end_time"
                        type="time"
                        name="end_time"
                        value="{{ old('end_time') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                    @error('end_time')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tujuan --}}
                <div>
                    <label
                        for="destination"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Tujuan <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="destination"
                        type="text"
                        name="destination"
                        value="{{ old('destination') }}"
                        placeholder="Contoh: Kantor pusat"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >

                    @error('destination')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Keperluan --}}
                <div>
                    <label
                        for="purpose"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Keperluan <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="purpose"
                        type="text"
                        name="purpose"
                        value="{{ old('purpose') }}"
                        placeholder="Contoh: Pengiriman dokumen"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >

                    @error('purpose')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Odometer awal --}}
                <div>
                    <label
                        for="start_odometer"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Kilometer Awal <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="start_odometer"
                        type="number"
                        name="start_odometer"
                        value="{{ old('start_odometer') }}"
                        min="0"
                        placeholder="Contoh: 50000"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >

                    @error('start_odometer')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Odometer akhir --}}
                <div>
                    <label
                        for="end_odometer"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Kilometer Akhir <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="end_odometer"
                        type="number"
                        name="end_odometer"
                        value="{{ old('end_odometer') }}"
                        min="0"
                        placeholder="Contoh: 50120"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        required
                    >

                    @error('end_odometer')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Biaya --}}
            <div class="border-t border-gray-200 pt-6">

                <h2 class="mb-4 font-semibold text-gray-900">
                    Biaya Operasional
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    <div>
                        <label
                            for="fuel_cost"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Biaya BBM
                        </label>

                        <input
                            id="fuel_cost"
                            type="number"
                            name="fuel_cost"
                            value="{{ old('fuel_cost', 0) }}"
                            min="0"
                            step="1"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                        >

                        @error('fuel_cost')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="toll_cost"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Biaya Tol
                        </label>

                        <input
                            id="toll_cost"
                            type="number"
                            name="toll_cost"
                            value="{{ old('toll_cost', 0) }}"
                            min="0"
                            step="1"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                        >

                        @error('toll_cost')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="parking_cost"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Biaya Parkir
                        </label>

                        <input
                            id="parking_cost"
                            type="number"
                            name="parking_cost"
                            value="{{ old('parking_cost', 0) }}"
                            min="0"
                            step="1"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                        >

                        @error('parking_cost')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Catatan --}}
            <div>
                <label
                    for="notes"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Catatan
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    placeholder="Catatan tambahan perjalanan"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">

                <a
                    href="{{ route('admin.logs.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Simpan Log
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
@extends('layouts.admin')

@section('title', 'Edit Log Perjalanan')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

```
{{-- Flash message --}}
@if (session('success'))
    <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- Form Edit Log --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            Edit Log Perjalanan
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui data perjalanan kendaraan.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.logs.update', $dailyLog) }}"
        class="space-y-6"
    >
        @csrf
        @method('PUT')

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
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >
                    @foreach ($drivers as $driver)
                        <option
                            value="{{ $driver->id }}"
                            {{ old('driver_id', $dailyLog->driver_id) == $driver->id ? 'selected' : '' }}
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
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >
                    @foreach ($vehicles as $vehicle)
                        <option
                            value="{{ $vehicle->id }}"
                            {{ old('vehicle_id', $dailyLog->vehicle_id) == $vehicle->id ? 'selected' : '' }}
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
                    Tanggal <span class="text-red-500">*</span>
                </label>

                <input
                    id="log_date"
                    type="date"
                    name="log_date"
                    value="{{ old(
                        'log_date',
                        $dailyLog->log_date
                            ? \Carbon\Carbon::parse($dailyLog->log_date)->format('Y-m-d')
                            : ''
                    ) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >

                @error('log_date')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div></div>

            {{-- Jam Mulai --}}
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
                    value="{{ old(
                        'start_time',
                        $dailyLog->start_time
                            ? substr($dailyLog->start_time, 0, 5)
                            : ''
                    ) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                >

                @error('start_time')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Jam Selesai --}}
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
                    value="{{ old(
                        'end_time',
                        $dailyLog->end_time
                            ? substr($dailyLog->end_time, 0, 5)
                            : ''
                    ) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
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
                    value="{{ old('destination', $dailyLog->destination) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
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
                    value="{{ old('purpose', $dailyLog->purpose) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >

                @error('purpose')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kilometer Awal --}}
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
                    value="{{ old('start_odometer', $dailyLog->start_odometer) }}"
                    min="0"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >

                @error('start_odometer')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Kilometer Akhir --}}
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
                    value="{{ old('end_odometer', $dailyLog->end_odometer) }}"
                    min="0"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >

                @error('end_odometer')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- Biaya Operasional --}}
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
                        value="{{ old('fuel_cost', $dailyLog->fuel_cost ?? 0) }}"
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
                        value="{{ old('toll_cost', $dailyLog->toll_cost ?? 0) }}"
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
                        value="{{ old('parking_cost', $dailyLog->parking_cost ?? 0) }}"
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

        {{-- Catatan Log --}}
        <div>
            <label
                for="notes"
                class="mb-1.5 block text-sm font-medium text-gray-700"
            >
                Catatan Perjalanan
            </label>

            <textarea
                id="notes"
                name="notes"
                rows="4"
                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
            >{{ old('notes', $dailyLog->notes) }}</textarea>

            @error('notes')
                <p class="mt-1 text-xs text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Tombol Edit --}}
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
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

{{-- Form Upload Struk --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="mb-5">
        <h2 class="text-lg font-semibold text-gray-900">
            Struk Operasional
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Upload struk BBM, tol, parkir, atau pengeluaran lainnya.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.logs.receipts.store', $dailyLog) }}"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Jenis Struk --}}
            <div>
                <label
                    for="receipt_type"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Jenis Struk <span class="text-red-500">*</span>
                </label>

                <select
                    id="receipt_type"
                    name="type"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >
                    <option value="">
                        Pilih jenis
                    </option>

                    <option
                        value="fuel"
                        {{ old('type') === 'fuel' ? 'selected' : '' }}
                    >
                        BBM
                    </option>

                    <option
                        value="toll"
                        {{ old('type') === 'toll' ? 'selected' : '' }}
                    >
                        Tol
                    </option>

                    <option
                        value="parking"
                        {{ old('type') === 'parking' ? 'selected' : '' }}
                    >
                        Parkir
                    </option>

                    <option
                        value="other"
                        {{ old('type') === 'other' ? 'selected' : '' }}
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

            {{-- Nominal --}}
            <div>
                <label
                    for="receipt_amount"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Nominal <span class="text-red-500">*</span>
                </label>

                <input
                    id="receipt_amount"
                    type="number"
                    name="amount"
                    value="{{ old('amount') }}"
                    min="0"
                    step="1"
                    placeholder="Contoh: 150000"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >

                @error('amount')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- File Struk --}}
            <div class="md:col-span-2">
                <label
                    for="receipt_file"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    File Struk <span class="text-red-500">*</span>
                </label>

                <input
                    id="receipt_file"
                    type="file"
                    name="receipt_file"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    required
                >

                <p class="mt-1 text-xs text-gray-500">
                    Format JPG, JPEG, PNG, atau PDF. Maksimal 5 MB.
                </p>

                @error('receipt_file')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Catatan Struk --}}
            <div class="md:col-span-2">
                <label
                    for="receipt_notes"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Catatan Struk
                </label>

                <textarea
                    id="receipt_notes"
                    name="receipt_notes"
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    placeholder="Catatan tambahan struk"
                >{{ old('receipt_notes') }}</textarea>

                @error('receipt_notes')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        <div class="flex justify-end">

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
            >
                Upload Struk
            </button>

        </div>

    </form>

</div>

{{-- Daftar Struk --}}
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

    <div class="border-b border-gray-200 px-6 py-5">
        <h2 class="font-semibold text-gray-900">
            Daftar Struk
        </h2>
    </div>

    <div class="divide-y divide-gray-100">

        @forelse ($dailyLog->receipts as $receipt)

            <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="font-medium text-gray-900">
                        @switch($receipt->type)
                            @case('fuel')
                                Struk BBM
                                @break

                            @case('toll')
                                Struk Tol
                                @break

                            @case('parking')
                                Struk Parkir
                                @break

                            @default
                                Struk Lainnya
                        @endswitch
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Rp {{ number_format(
                            (float) $receipt->amount,
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>

                    @if ($receipt->original_name)
                        <p class="mt-1 text-xs text-gray-400">
                            {{ $receipt->original_name }}
                        </p>
                    @endif

                    @if ($receipt->notes)
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $receipt->notes }}
                        </p>
                    @endif
                </div>

                <div class="flex gap-2">

                    <a
    href="{{ '/storage/' . ltrim($receipt->file_path, '/') }}"
    target="_blank"
    rel="noopener noreferrer"
    class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
>
    Lihat
</a>

                    <form
                        method="POST"
                        action="{{ route('admin.receipts.destroy', $receipt) }}"
                        onsubmit="return confirm('Hapus struk ini?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-lg bg-red-50 px-4 py-2 text-sm text-red-700 hover:bg-red-100"
                        >
                            Hapus
                        </button>
                    </form>

                </div>

            </div>

        @empty

            <div class="p-8 text-center text-sm text-gray-400">
                Belum ada struk yang diunggah.
            </div>

        @endforelse

    </div>

</div>
```

</div>

@endsection

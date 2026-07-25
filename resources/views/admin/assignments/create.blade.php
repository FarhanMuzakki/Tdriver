@extends('layouts.admin')

@section('title', 'Assign Kendaraan')

@section('content')

<div class="mx-auto max-w-3xl">

```
<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">
            Assign Kendaraan
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Pasangkan kendaraan yang tersedia dengan driver.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.assignments.store') }}"
        class="space-y-5"
    >
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- Driver --}}
            <div>
                <label
                    for="driver_id"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Driver
                </label>

                <select
                    id="driver_id"
                    name="driver_id"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
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
                    Kendaraan
                </label>

                <select
                    id="vehicle_id"
                    name="vehicle_id"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
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

            {{-- Tanggal Mulai --}}
            <div>
                <label
                    for="assigned_at"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Tanggal Mulai
                </label>

                <input
                    id="assigned_at"
                    type="datetime-local"
                    name="assigned_at"
                    value="{{ old('assigned_at', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    required
                >

                @error('assigned_at')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Rencana Kembali --}}
            <div>
                <label
                    for="planned_return_at"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Rencana Kembali
                </label>

                <input
                    id="planned_return_at"
                    type="datetime-local"
                    name="planned_return_at"
                    value="{{ old('planned_return_at') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

                @error('planned_return_at')
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
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
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
                    placeholder="Contoh: Meeting klien"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    required
                    >

                @error('purpose')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Catatan --}}
            <div class="md:col-span-2">
                <label
                    for="notes"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Catatan
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    placeholder="Catatan tambahan assignment"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- Peringatan jika data kosong --}}
        @if ($drivers->isEmpty() || $vehicles->isEmpty())
            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">

                @if ($drivers->isEmpty() && $vehicles->isEmpty())
                    Tidak ada driver dan kendaraan yang tersedia.
                @elseif ($drivers->isEmpty())
                    Tidak ada driver yang tersedia untuk assignment.
                @else
                    Tidak ada kendaraan yang tersedia untuk assignment.
                @endif

            </div>
        @endif

        {{-- Tombol --}}
        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:justify-end">

            <a
                href="{{ route('admin.assignments.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                @disabled($drivers->isEmpty() || $vehicles->isEmpty())
                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Simpan Assignment
            </button>

        </div>

    </form>

</div>
```

</div>

@endsection

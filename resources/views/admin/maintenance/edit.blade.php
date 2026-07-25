@extends('layouts.admin')

@section('title', 'Edit Maintenance')

@section('content')

<div class="mx-auto max-w-4xl">

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                Edit Maintenance
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui data maintenance kendaraan.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.maintenance.update', $maintenanceLog) }}"
            class="space-y-5"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label for="vehicle_id" class="mb-1.5 block text-sm font-medium text-gray-700">
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
                                {{ old('vehicle_id', $maintenanceLog->vehicle_id) == $vehicle->id ? 'selected' : '' }}
                            >
                                {{ $vehicle->plate_number }}
                                —
                                {{ $vehicle->brand }}
                                {{ $vehicle->model }}
                            </option>
                        @endforeach
                    </select>

                    @error('vehicle_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_type" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Jenis Service <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="service_type"
                        type="text"
                        name="service_type"
                        value="{{ old('service_type', $maintenanceLog->service_type) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                        required
                    >

                    @error('service_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_date" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Tanggal Service <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="service_date"
                        type="date"
                        name="service_date"
                        value="{{ old('service_date', $maintenanceLog->service_date?->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                        required
                    >

                    @error('service_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="workshop" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Bengkel
                    </label>

                    <input
                        id="workshop"
                        type="text"
                        name="workshop"
                        value="{{ old('workshop', $maintenanceLog->workshop) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    >
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                        required
                    >
                        <option
                            value="scheduled"
                            {{ old('status', $maintenanceLog->status) === 'scheduled' ? 'selected' : '' }}
                        >
                            Terjadwal
                        </option>

                        <option
                            value="in_progress"
                            {{ old('status', $maintenanceLog->status) === 'in_progress' ? 'selected' : '' }}
                        >
                            Sedang Maintenance
                        </option>

                        <option
                            value="completed"
                            {{ old('status', $maintenanceLog->status) === 'completed' ? 'selected' : '' }}
                        >
                            Selesai
                        </option>
                    </select>
                </div>

                <div>
                    <label for="cost" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Biaya
                    </label>

                    <input
                        id="cost"
                        type="number"
                        name="cost"
                        value="{{ old('cost', $maintenanceLog->cost) }}"
                        min="0"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    >
                </div>

                <div>
                    <label for="odometer" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Odometer
                    </label>

                    <input
                        id="odometer"
                        type="number"
                        name="odometer"
                        value="{{ old('odometer', $maintenanceLog->odometer) }}"
                        min="0"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    >
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Catatan
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    >{{ old('notes', $maintenanceLog->notes) }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">

                <a
                    href="{{ route('admin.maintenance.index') }}"
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

</div>

@endsection
@extends('layouts.admin')

@section('title', 'Detail Kendaraan')

@section('content')

@php
    $statusClass = match ($vehicle->status) {
        'available' => 'bg-green-100 text-green-700',
        'in_use' => 'bg-blue-100 text-blue-700',
        'maintenance' => 'bg-yellow-100 text-yellow-700',
        default => 'bg-gray-100 text-gray-700',
    };

    $statusLabel = match ($vehicle->status) {
        'available' => 'Available',
        'in_use' => 'In Use',
        'maintenance' => 'Maintenance',
        default => ucfirst(str_replace('_', ' ', $vehicle->status)),
    };

    $fuelLabel = match ($vehicle->fuel_type) {
        'gasoline' => 'Bensin',
        'diesel' => 'Diesel',
        'electric' => 'Listrik',
        'hybrid' => 'Hybrid',
        default => ucfirst($vehicle->fuel_type ?? '-'),
    };

    $transmissionLabel = match ($vehicle->transmission) {
        'manual' => 'Manual',
        'automatic' => 'Automatic',
        default => ucfirst($vehicle->transmission ?? '-'),
    };
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Detail Kendaraan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap kendaraan perusahaan.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">

            <a
                href="{{ route('admin.vehicles.edit', $vehicle) }}"
                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                Edit Kendaraan
            </a>

            <a
                href="{{ route('admin.vehicles.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Kembali
            </a>

        </div>

    </div>

    {{-- Foto dan identitas --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        @if ($vehicle->image_url)

            <button
                type="button"
                onclick="openVehicleImage()"
                class="group block w-full overflow-hidden focus:outline-none focus:ring-4 focus:ring-inset focus:ring-indigo-200"
            >
                <img
                    src="{{ $vehicle->image_url }}"
                    alt="Foto {{ $vehicle->plate_number }}"
                    class="h-80 w-full object-cover transition duration-300 group-hover:scale-105"
                >
            </button>

        @else

            <div class="flex h-80 w-full flex-col items-center justify-center bg-gray-100 text-gray-400">

                <span class="text-7xl">
                    🚗
                </span>

                <p class="mt-3 text-sm font-medium">
                    Foto kendaraan belum tersedia
                </p>

            </div>

        @endif

        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex flex-wrap items-center gap-3">

                    <h2 class="text-3xl font-bold text-gray-900">
                        {{ $vehicle->plate_number }}
                    </h2>

                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-2 text-lg font-semibold text-gray-700">
                    {{ $vehicle->brand ?: '-' }}
                    {{ $vehicle->model ?: '' }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $vehicle->type ?: '-' }}
                    •
                    {{ $vehicle->year ?: '-' }}
                    •
                    {{ $vehicle->color ?: '-' }}
                </p>

            </div>

            @if ($vehicle->image_url)
                <button
                    type="button"
                    onclick="openVehicleImage()"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Lihat Foto
                </button>
            @endif

        </div>

    </section>

    {{-- Detail Grid --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Informasi kendaraan --}}
        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-2">

            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Kendaraan
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">
                        Nomor Polisi
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->plate_number }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Brand
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->brand ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Model
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->model ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Tahun
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->year ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Warna
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->color ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Tipe Kendaraan
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->type ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Bahan Bakar
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $fuelLabel }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Transmisi
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $transmissionLabel }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Status
                    </p>

                    <div class="mt-2">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Service Berikutnya
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $vehicle->service_date
                            ? \Carbon\Carbon::parse($vehicle->service_date)->format('d M Y')
                            : '-' }}
                    </p>
                </div>

            </div>

        </section>

        {{-- Driver Assignment --}}
        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Driver Assignment
                </h2>
            </div>

            <div class="p-6">

                @if ($vehicle->activeAssignment?->driver)

                    <div class="rounded-2xl border border-green-100 bg-green-50 p-5">

                        <p class="text-sm font-semibold text-green-700">
                            Assignment Aktif
                        </p>

                        <div class="mt-4 flex items-center gap-3">

                            @if ($vehicle->activeAssignment->driver->profile_photo_url)
                                <img
                                    src="{{ $vehicle->activeAssignment->driver->profile_photo_url }}"
                                    alt="{{ $vehicle->activeAssignment->driver->name }}"
                                    class="h-12 w-12 rounded-2xl object-cover"
                                >
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-lg font-bold text-indigo-700 shadow-sm">
                                    {{ strtoupper(substr(
                                        $vehicle->activeAssignment->driver->name,
                                        0,
                                        1
                                    )) }}
                                </div>
                            @endif

                            <div class="min-w-0">

                                <p class="truncate font-bold text-gray-900">
                                    {{ $vehicle->activeAssignment->driver->name }}
                                </p>

                                <p class="truncate text-sm text-gray-500">
                                    {{ $vehicle->activeAssignment->driver->email }}
                                </p>

                            </div>

                        </div>

                        <div class="mt-4 border-t border-green-100 pt-4">

                            <p class="text-xs uppercase tracking-wide text-gray-400">
                                Ditugaskan pada
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-700">
                                {{ $vehicle->activeAssignment->assigned_at
                                    ? \Carbon\Carbon::parse(
                                        $vehicle->activeAssignment->assigned_at
                                    )->format('d M Y H:i')
                                    : '-' }}
                            </p>

                        </div>

                        <a
                            href="{{ route(
                                'admin.drivers.show',
                                $vehicle->activeAssignment->driver
                            ) }}"
                            class="mt-5 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-700"
                        >
                            Lihat detail driver →
                        </a>

                    </div>

                @else

                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm">
                            👤
                        </div>

                        <p class="mt-3 text-sm font-medium text-gray-600">
                            Kendaraan belum memiliki driver aktif.
                        </p>

                        @if ($vehicle->status === 'available')
                            <a
                                href="{{ route('admin.assignments.create') }}"
                                class="mt-4 inline-flex rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
                            >
                                Assign Driver
                            </a>
                        @endif

                    </div>

                @endif

            </div>

        </section>

    </div>

</div>

{{-- Modal foto --}}
@if ($vehicle->image_url)

    <div
        id="vehicle-image-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/75 p-5"
        onclick="closeVehicleImageFromBackdrop(event)"
    >
        <div class="relative w-full max-w-5xl">

            <button
                type="button"
                onclick="closeVehicleImage()"
                class="absolute -top-12 right-0 rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-800 shadow transition hover:bg-gray-100"
            >
                Tutup
            </button>

            <img
                src="{{ $vehicle->image_url }}"
                alt="Foto {{ $vehicle->plate_number }}"
                class="max-h-[82vh] w-full rounded-2xl bg-white object-contain shadow-2xl"
            >

            <p class="mt-3 text-center font-semibold text-white">
                {{ $vehicle->plate_number }}
                —
                {{ $vehicle->brand }}
                {{ $vehicle->model }}
            </p>

        </div>
    </div>

    <script>
        function openVehicleImage() {
            const modal = document.getElementById(
                'vehicle-image-modal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }

        function closeVehicleImage() {
            const modal = document.getElementById(
                'vehicle-image-modal'
            );

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }

        function closeVehicleImageFromBackdrop(event) {
            if (event.target.id === 'vehicle-image-modal') {
                closeVehicleImage();
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeVehicleImage();
            }
        });
    </script>

@endif

@endsection
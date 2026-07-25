@extends('layouts.admin')

@section('title', 'Detail Driver')

@section('content')

@php
    $statusClass = $driver->driver_status === 'active'
        ? 'bg-green-100 text-green-700'
        : 'bg-gray-100 text-gray-600';

    $statusLabel = $driver->driver_status === 'active'
        ? 'Driver Aktif'
        : 'Driver Nonaktif';

    $licenseExpired = $driver->license_expiry
        ? \Carbon\Carbon::parse($driver->license_expiry)->isPast()
        : false;

    $licenseExpiringSoon = $driver->license_expiry
        ? \Carbon\Carbon::parse($driver->license_expiry)
            ->between(today(), today()->addDays(30))
        : false;
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Detail Driver
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap dan riwayat assignment driver.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">

            <a
                href="{{ route('admin.drivers.edit', $driver) }}"
                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                Edit Driver
            </a>

            <a
                href="{{ route('admin.drivers.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Kembali
            </a>

        </div>

    </div>

    {{-- Foto dan identitas --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="h-32 bg-gradient-to-r from-indigo-600 to-violet-600"></div>

        <div class="-mt-16 px-6 pb-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-end">

                    @if ($driver->profile_photo_url)

                        <button
                            type="button"
                            onclick="openDriverImage()"
                            class="w-fit overflow-hidden rounded-3xl focus:outline-none focus:ring-4 focus:ring-indigo-200"
                        >
                            <img
                                src="{{ $driver->profile_photo_url }}"
                                alt="Foto {{ $driver->name }}"
                                class="h-32 w-32 rounded-3xl border-4 border-white bg-white object-cover shadow-lg transition hover:scale-105"
                            >
                        </button>

                    @else

                        <div class="flex h-32 w-32 items-center justify-center rounded-3xl border-4 border-white bg-indigo-100 text-4xl font-bold text-indigo-700 shadow-lg">
                            {{ strtoupper(mb_substr($driver->name, 0, 1)) }}
                        </div>

                    @endif

                    <div class="pb-2">

                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $driver->name }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $driver->email }}
                        </p>

                        <span class="mt-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>

                    </div>

                </div>

                @if ($driver->profile_photo_url)
                    <button
                        type="button"
                        onclick="openDriverImage()"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Lihat Foto
                    </button>
                @endif

            </div>

        </div>

    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Informasi Driver --}}
        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-2">

            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Driver
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">
                        Nama
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $driver->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Email
                    </p>

                    <p class="mt-1 break-all font-medium text-gray-900">
                        {{ $driver->email }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Nomor Telepon
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $driver->phone ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Nomor SIM
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $driver->license_number ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Masa Berlaku SIM
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $driver->license_expiry
                            ? \Carbon\Carbon::parse(
                                $driver->license_expiry
                            )->format('d M Y')
                            : '-' }}
                    </p>

                    @if ($licenseExpired)
                        <p class="mt-1 text-xs font-medium text-red-600">
                            SIM sudah kedaluwarsa.
                        </p>
                    @elseif ($licenseExpiringSoon)
                        <p class="mt-1 text-xs font-medium text-orange-600">
                            SIM akan segera kedaluwarsa.
                        </p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Status
                    </p>

                    <div class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-gray-500">
                        Alamat
                    </p>

                    <p class="mt-1 whitespace-pre-line font-medium leading-6 text-gray-900">
                        {{ $driver->address ?: '-' }}
                    </p>
                </div>

            </div>

        </section>

        {{-- Assignment Aktif --}}
        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Kendaraan Aktif
                </h2>
            </div>

            <div class="p-6">

                @if ($driver->activeAssignment?->vehicle)

                    @php
                        $activeVehicle = $driver->activeAssignment->vehicle;
                    @endphp

                    <div class="overflow-hidden rounded-2xl border border-green-100 bg-green-50">

                        @if ($activeVehicle->image_url)
                            <img
                                src="{{ $activeVehicle->image_url }}"
                                alt="{{ $activeVehicle->plate_number }}"
                                class="h-40 w-full object-cover"
                            >
                        @else
                            <div class="flex h-36 items-center justify-center bg-white/70 text-5xl">
                                🚗
                            </div>
                        @endif

                        <div class="p-5">

                            <p class="text-sm font-semibold text-green-700">
                                Assignment Aktif
                            </p>

                            <p class="mt-3 text-2xl font-bold text-gray-900">
                                {{ $activeVehicle->plate_number }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $activeVehicle->brand }}
                                {{ $activeVehicle->model }}
                            </p>

                            <div class="mt-4 border-t border-green-100 pt-4">

                                <p class="text-xs uppercase tracking-wide text-gray-400">
                                    Mulai assignment
                                </p>

                                <p class="mt-1 text-sm font-medium text-gray-700">
                                    {{ $driver->activeAssignment->assigned_at
                                        ? \Carbon\Carbon::parse(
                                            $driver->activeAssignment->assigned_at
                                        )->format('d M Y H:i')
                                        : '-' }}
                                </p>

                            </div>

                            <a
                                href="{{ route(
                                    'admin.vehicles.show',
                                    $activeVehicle
                                ) }}"
                                class="mt-5 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-700"
                            >
                                Lihat kendaraan →
                            </a>

                        </div>

                    </div>

                @else

                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-2xl shadow-sm">
                            🚗
                        </div>

                        <p class="mt-3 text-sm text-gray-500">
                            Driver belum memiliki assignment aktif.
                        </p>

                        @if ($driver->driver_status === 'active')
                            <a
                                href="{{ route('admin.assignments.create') }}"
                                class="mt-4 inline-flex rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
                            >
                                Buat Assignment
                            </a>
                        @endif

                    </div>

                @endif

            </div>

        </section>

    </div>

    {{-- Riwayat Assignment --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-gray-900">
                Riwayat Assignment
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Daftar kendaraan yang pernah ditugaskan kepada driver.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px]">

                <thead class="bg-gray-50">
                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Foto
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Kendaraan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Mulai
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Selesai
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($assignments as $assignment)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">

                                @if ($assignment->vehicle?->image_url)
                                    <img
                                        src="{{ $assignment->vehicle->image_url }}"
                                        alt="{{ $assignment->vehicle->plate_number }}"
                                        class="h-14 w-20 rounded-xl border border-gray-200 object-cover"
                                    >
                                @else
                                    <div class="flex h-14 w-20 items-center justify-center rounded-xl bg-gray-100 text-2xl">
                                        🚗
                                    </div>
                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $assignment->vehicle?->plate_number ?? '-' }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $assignment->vehicle?->brand ?? '-' }}
                                    {{ $assignment->vehicle?->model ?? '' }}
                                </p>

                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $assignment->assigned_at
                                    ? \Carbon\Carbon::parse(
                                        $assignment->assigned_at
                                    )->format('d M Y H:i')
                                    : '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $assignment->returned_at
                                    ? \Carbon\Carbon::parse(
                                        $assignment->returned_at
                                    )->format('d M Y H:i')
                                    : '-' }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($assignment->status === 'active')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Selesai
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-center">

                                @if ($assignment->vehicle)
                                    <a
                                        href="{{ route(
                                            'admin.vehicles.show',
                                            $assignment->vehicle
                                        ) }}"
                                        class="rounded-lg bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 transition hover:bg-indigo-100"
                                    >
                                        Detail Kendaraan
                                    </a>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                Belum ada riwayat assignment.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($assignments->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $assignments->links() }}
            </div>
        @endif

    </section>

</div>

{{-- Modal foto driver --}}
@if ($driver->profile_photo_url)

    <div
        id="driver-image-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/75 p-5"
        onclick="closeDriverImageFromBackdrop(event)"
    >
        <div class="relative w-full max-w-3xl">

            <button
                type="button"
                onclick="closeDriverImage()"
                class="absolute -top-12 right-0 rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-800 shadow transition hover:bg-gray-100"
            >
                Tutup
            </button>

            <img
                src="{{ $driver->profile_photo_url }}"
                alt="Foto {{ $driver->name }}"
                class="max-h-[80vh] w-full rounded-2xl bg-white object-contain shadow-2xl"
            >

            <p class="mt-3 text-center font-semibold text-white">
                {{ $driver->name }}
            </p>

        </div>
    </div>

    <script>
        function openDriverImage() {
            const modal = document.getElementById(
                'driver-image-modal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }

        function closeDriverImage() {
            const modal = document.getElementById(
                'driver-image-modal'
            );

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }

        function closeDriverImageFromBackdrop(event) {
            if (event.target.id === 'driver-image-modal') {
                closeDriverImage();
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDriverImage();
            }
        });
    </script>

@endif

@endsection
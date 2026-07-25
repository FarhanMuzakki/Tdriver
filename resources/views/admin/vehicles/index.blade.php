@extends('layouts.admin')

@section('title', 'Data Kendaraan')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Data Kendaraan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola seluruh kendaraan perusahaan.
            </p>
        </div>

        <a
            href="{{ route('admin.vehicles.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
        >
            <span class="mr-2 text-lg">+</span>
            Tambah Kendaraan
        </a>

    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search dan Filter --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.vehicles.index') }}"
            class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_220px_auto_auto]"
        >

            <div>
                <label
                    for="search"
                    class="sr-only"
                >
                    Cari kendaraan
                </label>

                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari plat, brand, model, tipe, atau warna..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >
            </div>

            <div>
                <label
                    for="status"
                    class="sr-only"
                >
                    Filter status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >
                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="available"
                        @selected(request('status') === 'available')
                    >
                        Available
                    </option>

                    <option
                        value="in_use"
                        @selected(request('status') === 'in_use')
                    >
                        In Use
                    </option>

                    <option
                        value="maintenance"
                        @selected(request('status') === 'maintenance')
                    >
                        Maintenance
                    </option>
                </select>
            </div>

            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                Cari
            </button>

            @if (request()->filled('search') || request()->filled('status'))
                <a
                    href="{{ route('admin.vehicles.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Reset
                </a>
            @endif

        </form>

    </div>

    {{-- Total Data --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

        <p class="text-sm text-gray-500">
            Total kendaraan:
            <span class="font-semibold text-gray-800">
                {{ $vehicles->total() }}
            </span>
        </p>

        @if (request()->filled('search'))
            <p class="text-sm text-gray-500">
                Hasil pencarian:
                <span class="font-medium text-gray-800">
                    “{{ request('search') }}”
                </span>
            </p>
        @endif

    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1450px]">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>

                        <th class="w-16 px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            No.
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Foto
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Kendaraan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Tahun
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Tipe
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Bahan Bakar
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Transmisi
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Driver Assignment
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($vehicles as $vehicle)

                        @php
                            $statusClass = match ($vehicle->status) {
                                'available' =>
                                    'bg-green-100 text-green-700',

                                'in_use' =>
                                    'bg-blue-100 text-blue-700',

                                'maintenance' =>
                                    'bg-yellow-100 text-yellow-700',

                                default =>
                                    'bg-gray-100 text-gray-700',
                            };

                            $statusLabel = match ($vehicle->status) {
                                'available' => 'Available',
                                'in_use' => 'In Use',
                                'maintenance' => 'Maintenance',
                                default => ucfirst(
                                    str_replace('_', ' ', $vehicle->status)
                                ),
                            };

                            $fuelLabel = match ($vehicle->fuel_type) {
                                'gasoline' => 'Bensin',
                                'diesel' => 'Diesel',
                                'electric' => 'Listrik',
                                'hybrid' => 'Hybrid',
                                default => ucfirst(
                                    $vehicle->fuel_type ?? '-'
                                ),
                            };

                            $transmissionLabel = match ($vehicle->transmission) {
                                'manual' => 'Manual',
                                'automatic' => 'Automatic',
                                default => ucfirst(
                                    $vehicle->transmission ?? '-'
                                ),
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            {{-- No --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $vehicles->firstItem() + $loop->index }}
                            </td>

                            {{-- Foto --}}
                            <td class="px-6 py-4">
                                @if ($vehicle->image_url)
                                    <button
                                        type="button"
                                        onclick='openVehicleImage(
                                            @json($vehicle->image_url),
                                            @json($vehicle->plate_number)
                                        )'
                                        class="block overflow-hidden rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    >
                                        <img
                                            src="{{ $vehicle->image_url }}"
                                            alt="Foto {{ $vehicle->plate_number }}"
                                            class="h-16 w-24 rounded-xl border border-gray-200 object-cover shadow-sm transition duration-200 hover:scale-105"
                                        >
                                    </button>
                                @else
                                    <div class="flex h-16 w-24 items-center justify-center rounded-xl border border-gray-200 bg-gray-100 text-2xl text-gray-400">
                                        🚗
                                    </div>
                                @endif
                            </td>

                            {{-- Kendaraan --}}
                            <td class="px-6 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $vehicle->plate_number }}
                                </p>

                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $vehicle->brand }}
                                    {{ $vehicle->model }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Warna:
                                    {{ $vehicle->color ?: '-' }}
                                </p>

                            </td>

                            {{-- Tahun --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $vehicle->year ?: '-' }}
                            </td>

                            {{-- Tipe --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $vehicle->type ?: '-' }}
                            </td>

                            {{-- Bahan Bakar --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $fuelLabel }}
                            </td>

                            {{-- Transmisi --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $transmissionLabel }}
                            </td>

                            {{-- Driver Assignment --}}
                            <td class="px-6 py-4">

                                @if ($vehicle->activeAssignment?->driver)
                                    <p class="font-medium text-gray-900">
                                        {{ $vehicle->activeAssignment->driver->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $vehicle->activeAssignment->driver->email }}
                                    </p>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">
                                        Belum ada driver
                                    </span>
                                @endif

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                            </td>

                            {{-- Aksi --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="{{ route('admin.vehicles.show', $vehicle) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                        class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.vehicles.destroy', $vehicle) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="10"
                                class="px-6 py-12 text-center text-gray-500"
                            >
                                Belum ada data kendaraan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Pagination --}}
    @if ($vehicles->hasPages())
        <div>
            {{ $vehicles->links() }}
        </div>
    @endif

</div>

{{-- Modal Foto Kendaraan --}}
<div
    id="vehicle-image-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-5"
    onclick="closeVehicleImageFromBackdrop(event)"
>
    <div class="relative w-full max-w-4xl">

        <button
            type="button"
            onclick="closeVehicleImage()"
            class="absolute -top-12 right-0 rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-800 shadow transition hover:bg-gray-100"
        >
            Tutup
        </button>

        <img
            id="vehicle-image-modal-img"
            src=""
            alt=""
            class="max-h-[80vh] w-full rounded-2xl bg-white object-contain shadow-2xl"
        >

        <p
            id="vehicle-image-modal-title"
            class="mt-3 text-center text-base font-semibold text-white"
        ></p>

    </div>
</div>

<script>
    function openVehicleImage(imageUrl, plateNumber) {
        const modal = document.getElementById(
            'vehicle-image-modal'
        );

        const image = document.getElementById(
            'vehicle-image-modal-img'
        );

        const title = document.getElementById(
            'vehicle-image-modal-title'
        );

        image.src = imageUrl;
        image.alt = 'Foto ' + plateNumber;
        title.textContent = plateNumber;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeVehicleImage() {
        const modal = document.getElementById(
            'vehicle-image-modal'
        );

        const image = document.getElementById(
            'vehicle-image-modal-img'
        );

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        image.src = '';

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

@endsection
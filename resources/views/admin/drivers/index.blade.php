@extends('layouts.admin')

@section('title', 'Data Driver')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Data Driver
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola seluruh akun driver perusahaan.
            </p>
        </div>

        <a
            href="{{ route('admin.drivers.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
        >
            <span class="mr-2 text-lg">+</span>
            Tambah Driver
        </a>

    </div>

    {{-- Flash --}}
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

    {{-- Search --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.drivers.index') }}"
            class="flex flex-col gap-3 md:flex-row"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, email, telepon, atau nomor SIM..."
                class="flex-1 rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            >

            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                Cari
            </button>

            @if (request()->filled('search'))
                <a
                    href="{{ route('admin.drivers.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-6 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Reset
                </a>
            @endif
        </form>

    </div>

    {{-- Total --}}
    <div class="flex items-center justify-between">

        <p class="text-sm text-gray-500">
            Total driver:
            <span class="font-semibold text-gray-800">
                {{ $drivers->total() }}
            </span>
        </p>

        @if (request()->filled('search'))
            <p class="text-sm text-gray-500">
                Hasil:
                <span class="font-medium text-gray-800">
                    “{{ request('search') }}”
                </span>
            </p>
        @endif

    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1150px]">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>

                        <th class="w-16 px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            No.
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Foto
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Driver
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Kontak
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Nomor SIM
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

                    @forelse ($drivers as $driver)

                        @php
                            $statusClass = match ($driver->driver_status) {
                                'active' =>
                                    'bg-green-100 text-green-700',

                                'inactive' =>
                                    'bg-gray-100 text-gray-600',

                                default =>
                                    'bg-gray-100 text-gray-600',
                            };

                            $statusLabel = match ($driver->driver_status) {
                                'active' => 'Aktif',
                                'inactive' => 'Nonaktif',
                                default => ucfirst(
                                    $driver->driver_status ?? '-'
                                ),
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            {{-- No --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $drivers->firstItem() + $loop->index }}
                            </td>

                            {{-- Foto --}}
                            <td class="px-6 py-4">

                                @if ($driver->profile_photo_url)
                                    <button
                                        type="button"
                                        data-image="{{ $driver->profile_photo_url }}"
                                        data-name="{{ $driver->name }}"
                                        onclick="openDriverImage(this)"
                                        class="block overflow-hidden rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    >
                                        <img
                                            src="{{ $driver->profile_photo_url }}"
                                            alt="Foto {{ $driver->name }}"
                                            class="h-16 w-16 rounded-2xl border border-gray-200 object-cover shadow-sm transition duration-200 hover:scale-105"
                                        >
                                    </button>
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-50 text-xl font-bold text-indigo-700">
                                        {{ strtoupper(substr($driver->name, 0, 1)) }}
                                    </div>
                                @endif

                            </td>

                            {{-- Driver --}}
                            <td class="px-6 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $driver->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $driver->email }}
                                </p>

                            </td>

                            {{-- Kontak --}}
                            <td class="px-6 py-4">

                                <p class="text-sm text-gray-700">
                                    {{ $driver->phone ?: '-' }}
                                </p>

                                <p class="mt-1 max-w-[220px] truncate text-xs text-gray-500">
                                    {{ $driver->address ?: 'Alamat belum diisi' }}
                                </p>

                            </td>

                            {{-- SIM --}}
                            <td class="px-6 py-4">

                                <p class="text-sm font-medium text-gray-800">
                                    {{ $driver->license_number ?: '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    @if ($driver->license_expiry)
                                        Berlaku sampai
                                        {{ \Carbon\Carbon::parse(
                                            $driver->license_expiry
                                        )->format('d M Y') }}
                                    @else
                                        Masa berlaku belum diisi
                                    @endif
                                </p>

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
                                        href="{{ route('admin.drivers.show', $driver) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('admin.drivers.edit', $driver) }}"
                                        class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.drivers.destroy', $driver) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus driver ini?')"
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
                                colspan="7"
                                class="px-6 py-12 text-center text-gray-500"
                            >
                                Belum ada data driver.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Pagination --}}
    @if ($drivers->hasPages())
        <div>
            {{ $drivers->links() }}
        </div>
    @endif

</div>

{{-- Modal Foto Driver --}}
<div
    id="driver-image-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-5"
    onclick="closeDriverImageFromBackdrop(event)"
>
    <div class="relative w-full max-w-2xl">

        <button
            type="button"
            onclick="closeDriverImage()"
            class="absolute -top-12 right-0 rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-800 shadow transition hover:bg-gray-100"
        >
            Tutup
        </button>

        <img
            id="driver-image-modal-img"
            src=""
            alt=""
            class="max-h-[80vh] w-full rounded-2xl bg-white object-contain shadow-2xl"
        >

        <p
            id="driver-image-modal-title"
            class="mt-3 text-center text-base font-semibold text-white"
        ></p>

    </div>
</div>

<script>
    function openDriverImage(button) {
        const modal = document.getElementById(
            'driver-image-modal'
        );

        const image = document.getElementById(
            'driver-image-modal-img'
        );

        const title = document.getElementById(
            'driver-image-modal-title'
        );

        const imageUrl = button.dataset.image;
        const driverName = button.dataset.name;

        image.src = imageUrl;
        image.alt = 'Foto ' + driverName;
        title.textContent = driverName;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeDriverImage() {
        const modal = document.getElementById(
            'driver-image-modal'
        );

        const image = document.getElementById(
            'driver-image-modal-img'
        );

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        image.src = '';

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

@endsection
@extends('layouts.admin')

@section('title', 'Pengajuan Maintenance')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Pengajuan Maintenance
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Kelola laporan kerusakan dan kebutuhan servis dari driver.
        </p>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="text-sm font-semibold text-red-700">
                Terjadi kesalahan:
            </p>

            <ul class="mt-2 space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">

        <a
            href="{{ route('admin.maintenance-requests.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
        >
            <p class="text-sm text-gray-500">Semua</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                {{ $counts['all'] }}
            </p>
        </a>

        <a
            href="{{ route('admin.maintenance-requests.index', ['status' => 'pending']) }}"
            class="rounded-xl border border-amber-200 bg-amber-50 p-4"
        >
            <p class="text-sm text-amber-700">Menunggu</p>
            <p class="mt-2 text-2xl font-bold text-amber-800">
                {{ $counts['pending'] }}
            </p>
        </a>

        <a
            href="{{ route('admin.maintenance-requests.index', ['status' => 'approved']) }}"
            class="rounded-xl border border-blue-200 bg-blue-50 p-4"
        >
            <p class="text-sm text-blue-700">Disetujui</p>
            <p class="mt-2 text-2xl font-bold text-blue-800">
                {{ $counts['approved'] }}
            </p>
        </a>

        <a
            href="{{ route('admin.maintenance-requests.index', ['status' => 'rejected']) }}"
            class="rounded-xl border border-red-200 bg-red-50 p-4"
        >
            <p class="text-sm text-red-700">Ditolak</p>
            <p class="mt-2 text-2xl font-bold text-red-800">
                {{ $counts['rejected'] }}
            </p>
        </a>

        <a
            href="{{ route('admin.maintenance-requests.index', ['status' => 'completed']) }}"
            class="rounded-xl border border-green-200 bg-green-50 p-4"
        >
            <p class="text-sm text-green-700">Selesai</p>
            <p class="mt-2 text-2xl font-bold text-green-800">
                {{ $counts['completed'] }}
            </p>
        </a>

    </div>

    {{-- Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.maintenance-requests.index') }}"
            class="flex flex-col gap-3 md:flex-row"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari driver, kendaraan, atau masalah..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
            >

            <select
                name="status"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
            >
                <option value="">Semua status</option>
                <option value="pending" @selected(request('status') === 'pending')>
                    Menunggu
                </option>
                <option value="approved" @selected(request('status') === 'approved')>
                    Disetujui
                </option>
                <option value="rejected" @selected(request('status') === 'rejected')>
                    Ditolak
                </option>
                <option value="completed" @selected(request('status') === 'completed')>
                    Selesai
                </option>
            </select>

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.maintenance-requests.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm text-gray-700"
            >
                Reset
            </a>
        </form>

    </div>

    {{-- Daftar --}}
    <div class="space-y-4">

        @forelse ($maintenanceRequests as $requestItem)

            @php
                $statusLabel = match ($requestItem->status) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                    default => 'Menunggu',
                };

                $statusClass = match ($requestItem->status) {
                    'approved' => 'bg-blue-50 text-blue-700',
                    'rejected' => 'bg-red-50 text-red-700',
                    'completed' => 'bg-green-50 text-green-700',
                    default => 'bg-amber-50 text-amber-700',
                };

                $priorityLabel = match ($requestItem->priority) {
                    'low' => 'Rendah',
                    'high' => 'Tinggi',
                    'urgent' => 'Darurat',
                    default => 'Sedang',
                };
            @endphp

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                    <div class="min-w-0 flex-1">

                        <div class="flex flex-wrap items-center gap-2">

                            <h2 class="text-lg font-bold text-gray-900">
                                {{ $requestItem->vehicle?->plate_number ?? '-' }}
                            </h2>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                Prioritas {{ $priorityLabel }}
                            </span>

                        </div>

                        <p class="mt-2 text-sm text-gray-500">
                            Driver:
                            <span class="font-medium text-gray-800">
                                {{ $requestItem->driver?->name ?? '-' }}
                            </span>
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Kendaraan:
                            {{ $requestItem->vehicle?->brand ?? '' }}
                            {{ $requestItem->vehicle?->model ?? '' }}
                        </p>

                        <div class="mt-4 rounded-xl bg-gray-50 p-4">

                            <p class="text-sm font-semibold text-gray-900">
                                {{ $requestItem->issue_type }}
                            </p>

                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">
                                {{ $requestItem->description }}
                            </p>

                        </div>

                        <p class="mt-3 text-xs text-gray-400">
                            Diajukan:
                            {{ $requestItem->requested_at?->format('d M Y, H:i') ?? '-' }}
                        </p>

                        @if ($requestItem->admin_notes)
                            <div class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                                <p class="text-xs font-semibold text-indigo-700">
                                    Catatan Admin
                                </p>

                                <p class="mt-1 text-sm text-indigo-800">
                                    {{ $requestItem->admin_notes }}
                                </p>
                            </div>
                        @endif

                    </div>

                    <div class="w-full space-y-3 lg:w-80">

                        @if ($requestItem->status === 'pending')

                            {{-- Approve --}}
                            <form
                                method="POST"
                                action="{{ route('admin.maintenance-requests.approve', $requestItem) }}"
                                class="rounded-xl border border-green-200 bg-green-50 p-3"
                            >
                                @csrf
                                @method('PATCH')

                                <textarea
                                    name="admin_notes"
                                    rows="2"
                                    placeholder="Catatan persetujuan (opsional)"
                                    class="w-full rounded-lg border border-green-200 bg-white px-3 py-2 text-sm"
                                ></textarea>

                                <button
                                    type="submit"
                                    onclick="return confirm('Setujui pengajuan ini?')"
                                    class="mt-2 w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white"
                                >
                                    Setujui
                                </button>
                            </form>

                            {{-- Reject --}}
                            <form
                                method="POST"
                                action="{{ route('admin.maintenance-requests.reject', $requestItem) }}"
                                class="rounded-xl border border-red-200 bg-red-50 p-3"
                            >
                                @csrf
                                @method('PATCH')

                                <textarea
                                    name="admin_notes"
                                    rows="2"
                                    placeholder="Alasan penolakan"
                                    required
                                    class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-sm"
                                ></textarea>

                                <button
                                    type="submit"
                                    onclick="return confirm('Tolak pengajuan ini?')"
                                    class="mt-2 w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white"
                                >
                                    Tolak
                                </button>
                            </form>
                            @if (in_array($requestItem->status, ['pending', 'rejected'], true))

    <form
        method="POST"
        action="{{ route(
            'admin.maintenance-requests.destroy',
            $requestItem
        ) }}"
        onsubmit="return confirm('Hapus pengajuan maintenance ini secara permanen?')"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 7.5h12M9.75 7.5V5.25h4.5V7.5m-6 0 .75 12h6l.75-12"
                />
            </svg>

            Hapus Pengajuan
        </button>
    </form>

@endif
                        @elseif ($requestItem->status === 'approved')

    <form
        method="POST"
        action="{{ route(
            'admin.maintenance-requests.complete',
            $requestItem
        ) }}"
        class="space-y-3 rounded-xl border border-blue-200 bg-blue-50 p-4"
    >
        @csrf
        @method('PATCH')

        <div>
            <label
                class="mb-1 block text-xs font-semibold text-blue-800"
            >
                Tanggal Servis
            </label>

            <input
                type="date"
                name="service_date"
                value="{{ old('service_date', now()->toDateString()) }}"
                required
                class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm"
            >
        </div>

        <div>
            <label
                class="mb-1 block text-xs font-semibold text-blue-800"
            >
                Bengkel
            </label>

            <input
                type="text"
                name="workshop"
                value="{{ old('workshop') }}"
                placeholder="Nama bengkel"
                required
                class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm"
            >
        </div>

        <div>
            <label
                class="mb-1 block text-xs font-semibold text-blue-800"
            >
                Biaya Servis
            </label>

            <input
                type="number"
                name="cost"
                value="{{ old('cost', 0) }}"
                min="0"
                step="1"
                required
                class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm"
            >
        </div>

        <div>
            <label
                class="mb-1 block text-xs font-semibold text-blue-800"
            >
                Odometer
            </label>

            <input
                type="number"
                name="odometer"
                value="{{ old('odometer') }}"
                min="0"
                step="1"
                placeholder="Contoh: 85000"
                class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm"
            >
        </div>

        <div>
            <label
                class="mb-1 block text-xs font-semibold text-blue-800"
            >
                Catatan Hasil Servis
            </label>

            <textarea
                name="service_notes"
                rows="3"
                placeholder="Komponen yang diperbaiki atau diganti"
                class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm"
            >{{ old('service_notes') }}</textarea>
        </div>

        <div>
            <label
                class="mb-1 block text-xs font-semibold text-blue-800"
            >
                Catatan untuk Driver
            </label>

            <textarea
                name="admin_notes"
                rows="2"
                placeholder="Catatan tambahan untuk driver"
                class="w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm"
            >{{ old('admin_notes', $requestItem->admin_notes) }}</textarea>
        </div>

        <button
            type="submit"
            onclick="return confirm('Simpan data dan tandai maintenance selesai?')"
            class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Simpan & Tandai Selesai
        </button>
    </form>

                        @else

                            <div class="rounded-xl bg-gray-50 p-4 text-center text-sm text-gray-500">
                                Pengajuan ini sudah diproses.
                            </div>

                        @endif

                    </div>

                </div>

            </article>

        @empty

            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <p class="font-semibold text-gray-900">
                    Belum ada pengajuan maintenance
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Pengajuan dari driver akan muncul di sini.
                </p>
            </div>

        @endforelse

    </div>

    @if ($maintenanceRequests->hasPages())
        {{ $maintenanceRequests->links() }}
    @endif

</div>

@endsection
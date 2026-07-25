@extends('layouts.admin')

@section('title', 'Riwayat Maintenance')

@section('content')

<div class="space-y-6">

```
{{-- Header --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Maintenance
        </h1>

        <p class="mt-1 text-gray-500">
            Kelola seluruh riwayat servis kendaraan.
        </p>
    </div>

    <a
        href="{{ route('admin.maintenance.create') }}"
        class="rounded-xl bg-indigo-600 px-5 py-3 text-center text-white shadow transition hover:bg-indigo-700"
    >
        + Tambah Maintenance
    </a>

</div>

{{-- Flash message --}}
@if (session('success'))
    <div class="rounded-lg border border-green-300 bg-green-100 p-4 text-green-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="rounded-lg border border-red-300 bg-red-100 p-4 text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- Search dan filter --}}
<div class="rounded-xl bg-white p-4 shadow-sm">

    <form
        method="GET"
        action="{{ route('admin.maintenance.index') }}"
        class="flex flex-col gap-4 lg:flex-row"
    >
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari kendaraan, bengkel, atau jenis servis..."
            class="flex-1 rounded-lg border border-gray-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
        >

        <select
            name="status"
            class="rounded-lg border border-gray-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
        >
            <option value="">
                Semua status
            </option>

            <option
                value="scheduled"
                {{ request('status') === 'scheduled' ? 'selected' : '' }}
            >
                Terjadwal
            </option>

            <option
                value="in_progress"
                {{ request('status') === 'in_progress' ? 'selected' : '' }}
            >
                Sedang Maintenance
            </option>

            <option
                value="completed"
                {{ request('status') === 'completed' ? 'selected' : '' }}
            >
                Selesai
            </option>
        </select>

        <button
            type="submit"
            class="rounded-lg bg-indigo-600 px-6 py-3 text-white transition hover:bg-indigo-700"
        >
            Cari
        </button>

        <a
            href="{{ route('admin.maintenance.index') }}"
            class="rounded-lg border border-gray-300 px-6 py-3 text-center text-gray-700 transition hover:bg-gray-50"
        >
            Reset
        </a>
    </form>

</div>

{{-- Table --}}
<div class="overflow-x-auto rounded-xl bg-white shadow-sm">

    <table class="w-full min-w-[1150px]">

        <thead class="bg-gray-100 text-sm text-gray-600">
            <tr>
                <th class="px-6 py-4 text-left font-semibold">
                    Kendaraan
                </th>

                <th class="px-6 py-4 text-left font-semibold">
                    Jenis Service
                </th>

                <th class="px-6 py-4 text-left font-semibold">
                    Tanggal
                </th>

                <th class="px-6 py-4 text-left font-semibold">
                    Bengkel
                </th>

                <th class="px-6 py-4 text-left font-semibold">
                    Biaya
                </th>

                <th class="px-6 py-4 text-left font-semibold">
                    Odometer
                </th>

                <th class="px-6 py-4 text-left font-semibold">
                    Status
                </th>

                <th class="px-6 py-4 text-center font-semibold">
                    Aksi
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse ($maintenanceLogs as $maintenanceLog)

                <tr class="border-t border-gray-100 hover:bg-gray-50">

                    {{-- Kendaraan --}}
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">
                            {{ $maintenanceLog->vehicle?->plate_number ?? '-' }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $maintenanceLog->vehicle?->brand ?? '' }}
                            {{ $maintenanceLog->vehicle?->model ?? '' }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $maintenanceLog->vehicle?->type ?? '-' }}
                        </p>
                    </td>

                    {{-- Jenis service --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $maintenanceLog->service_type
                            ? ucwords(str_replace('_', ' ', $maintenanceLog->service_type))
                            : '-' }}
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $maintenanceLog->service_date
                            ? \Carbon\Carbon::parse($maintenanceLog->service_date)->format('d M Y')
                            : '-' }}
                    </td>

                    {{-- Bengkel --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $maintenanceLog->workshop ?? '-' }}
                    </td>

                    {{-- Biaya --}}
                    <td class="px-6 py-4 text-gray-700">
                        Rp {{ number_format(
                            (float) ($maintenanceLog->cost ?? 0),
                            0,
                            ',',
                            '.'
                        ) }}
                    </td>

                    {{-- Odometer --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $maintenanceLog->odometer
                            ? number_format(
                                (int) $maintenanceLog->odometer,
                                0,
                                ',',
                                '.'
                            ) . ' km'
                            : '-' }}
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">

                        @if ($maintenanceLog->status === 'scheduled')
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                Terjadwal
                            </span>

                        @elseif ($maintenanceLog->status === 'in_progress')
                            <span class="inline-flex rounded-full bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700">
                                Sedang Maintenance
                            </span>

                        @elseif ($maintenanceLog->status === 'completed')
                            <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                Selesai
                            </span>

                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                {{ $maintenanceLog->status
                                    ? ucfirst(str_replace('_', ' ', $maintenanceLog->status))
                                    : '-' }}
                            </span>
                        @endif

                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4">

                        <div class="flex justify-center gap-2">

                            <a
                                href="{{ route('admin.maintenance.edit', $maintenanceLog) }}"
                                class="rounded-lg bg-yellow-100 px-3 py-2 text-yellow-700 transition hover:bg-yellow-200"
                                title="Edit maintenance"
                            >
                                ✏️
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.maintenance.destroy', $maintenanceLog) }}"
                                onsubmit="return confirm('Yakin ingin menghapus data maintenance ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-red-100 px-3 py-2 text-red-700 transition hover:bg-red-200"
                                    title="Hapus maintenance"
                                >
                                    🗑️
                                </button>
                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td
                        colspan="8"
                        class="px-6 py-12 text-center text-gray-400"
                    >
                        Belum ada data maintenance.
                    </td>
                </tr>

            @endforelse
        </tbody>

    </table>

</div>

{{-- Pagination --}}
@if ($maintenanceLogs->hasPages())
    <div>
        {{ $maintenanceLogs->links() }}
    </div>
@endif
```

</div>

@endsection
    
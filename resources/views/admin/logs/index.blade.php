@extends('layouts.admin')

@section('title', 'Log Perjalanan')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Log Perjalanan
            </h1>

            <p class="mt-1 text-gray-500">
                Kelola seluruh aktivitas perjalanan kendaraan.
            </p>
        </div>

        <a
            href="{{ route('admin.logs.create') }}"
            class="rounded-xl bg-indigo-600 px-5 py-3 text-center text-white hover:bg-indigo-700"
        >
            + Tambah Log
        </a>

    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-300 bg-green-100 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.logs.index') }}"
            class="flex flex-col gap-4 lg:flex-row"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari driver, kendaraan, tujuan..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-3"
            >

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="rounded-lg border border-gray-300 px-4 py-3"
            >

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-6 py-3 text-white"
            >
                Cari
            </button>

            <a
                href="{{ route('admin.logs.index') }}"
                class="rounded-lg border border-gray-300 px-6 py-3 text-center"
            >
                Reset
            </a>
        </form>

    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">

        <table class="w-full min-w-[1250px]">

            <thead class="bg-gray-100 text-sm text-gray-600">
                <tr>
                    <th class="px-5 py-4 text-left">Tanggal</th>
                    <th class="px-5 py-4 text-left">Driver</th>
                    <th class="px-5 py-4 text-left">Kendaraan</th>
                    <th class="px-5 py-4 text-left">Tujuan</th>
                    <th class="px-5 py-4 text-left">Jarak</th>
                    <th class="px-5 py-4 text-left">Total Biaya</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($dailyLogs as $dailyLog)

                    <tr class="border-t border-gray-100 hover:bg-gray-50">

                        <td class="px-5 py-4">
                            {{ $dailyLog->log_date?->format('d M Y') ?? '-' }}

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $dailyLog->start_time ?? '-' }}
                                –
                                {{ $dailyLog->end_time ?? '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-medium">
                                {{ $dailyLog->driver?->name ?? '-' }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $dailyLog->driver?->email ?? '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-medium">
                                {{ $dailyLog->vehicle?->plate_number ?? '-' }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $dailyLog->vehicle?->brand ?? '' }}
                                {{ $dailyLog->vehicle?->model ?? '' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p>{{ $dailyLog->destination }}</p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $dailyLog->purpose }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            {{ $dailyLog->distance !== null
                                ? number_format($dailyLog->distance, 0, ',', '.') . ' km'
                                : '-' }}
                        </td>

                        <td class="px-5 py-4">
                            Rp {{ number_format(
                                $dailyLog->total_cost,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td class="px-5 py-4">

                            <div class="flex justify-center gap-2">

                                <a
                                    href="{{ route('admin.logs.edit', $dailyLog) }}"
                                    class="rounded-lg bg-yellow-100 px-3 py-2 text-yellow-700"
                                >
                                    ✏️
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.logs.destroy', $dailyLog) }}"
                                    onsubmit="return confirm('Hapus log perjalanan ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-red-100 px-3 py-2 text-red-700"
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
                            colspan="7"
                            class="px-6 py-12 text-center text-gray-400"
                        >
                            Belum ada log perjalanan.
                        </td>
                    </tr>

                @endforelse
            </tbody>

        </table>

    </div>

    {{ $dailyLogs->links() }}

</div>

@endsection
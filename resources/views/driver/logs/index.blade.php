@extends('layouts.driver')

@section('title', 'Riwayat Perjalanan')
@section('page-label', 'Riwayat Perjalanan')

@section('content')

<div class="space-y-5">

    {{-- Header --}}
    <section class="flex items-start justify-between gap-4">

        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">
                Aktivitas Anda
            </p>

            <h1 class="mt-1 text-2xl font-bold text-slate-900">
                Riwayat Perjalanan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Lihat dan kelola perjalanan yang sudah dicatat.
            </p>
        </div>

        <a
            href="{{ route('driver.logs.create') }}"
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200 transition active:scale-95"
            aria-label="Tambah log perjalanan"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.2"
            >
                <path
                    stroke-linecap="round"
                    d="M12 5v14M5 12h14"
                />
            </svg>
        </a>

    </section>

    {{-- Filter --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('driver.logs.index') }}"
            class="space-y-3"
        >
            <div class="relative">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"
                    />
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kendaraan, tujuan, keperluan..."
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                >

            </div>

            <div class="grid grid-cols-[1fr_auto] gap-2">

                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="min-w-0 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-50"
                >

                <button
                    type="submit"
                    class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition active:scale-95"
                >
                    Filter
                </button>

            </div>

            @if (request()->filled('search') || request()->filled('date'))
                <a
                    href="{{ route('driver.logs.index') }}"
                    class="flex w-full items-center justify-center rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Hapus Filter
                </a>
            @endif

        </form>

    </section>

    {{-- Result info --}}
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-base font-bold text-slate-900">
                Daftar Perjalanan
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                {{ $dailyLogs->total() }} perjalanan ditemukan
            </p>
        </div>

        @if ($dailyLogs->isNotEmpty())
            <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                Halaman {{ $dailyLogs->currentPage() }}
            </span>
        @endif

    </div>

    {{-- Log cards --}}
    <section class="space-y-3">

        @forelse ($dailyLogs as $dailyLog)

            @php
                $distance = null;

                if (
                    $dailyLog->start_odometer !== null &&
                    $dailyLog->end_odometer !== null
                ) {
                    $distance = max(
                        0,
                        $dailyLog->end_odometer - $dailyLog->start_odometer
                    );
                }

                $totalCost =
                    (float) $dailyLog->fuel_cost +
                    (float) $dailyLog->toll_cost +
                    (float) $dailyLog->parking_cost;

                $receiptCount = $dailyLog->receipts?->count() ?? 0;
            @endphp

            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <a
                    href="{{ route('driver.logs.show', $dailyLog) }}"
                    class="block p-4 transition active:bg-slate-50"
                >

                    {{-- Top --}}
                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="text-base font-bold text-slate-900">
                                    {{ $dailyLog->vehicle?->plate_number ?? '-' }}
                                </h3>

                                <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold text-indigo-600">
                                    {{ $dailyLog->log_date
                                        ? \Carbon\Carbon::parse($dailyLog->log_date)->format('d M Y')
                                        : '-' }}
                                </span>

                            </div>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ trim(
                                    ($dailyLog->vehicle?->brand ?? '') . ' ' .
                                    ($dailyLog->vehicle?->model ?? '')
                                ) ?: 'Data kendaraan tidak tersedia' }}
                            </p>

                        </div>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m9 5 7 7-7 7"
                                />
                            </svg>

                        </div>

                    </div>

                    {{-- Destination --}}
                    <div class="mt-4 rounded-2xl bg-slate-50 p-3.5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-indigo-600 shadow-sm">

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
                                        d="M12 21s6-5.25 6-11a6 6 0 10-12 0c0 5.75 6 11 6 11z"
                                    />

                                    <circle cx="12" cy="10" r="2" />
                                </svg>

                            </div>

                            <div class="min-w-0">

                                <p class="truncate text-sm font-semibold text-slate-900">
                                    {{ $dailyLog->destination ?? '-' }}
                                </p>

                                <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500">
                                    {{ $dailyLog->purpose ?? 'Tidak ada keterangan keperluan.' }}
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- Stats --}}
                    <div class="mt-4 grid grid-cols-3 divide-x divide-slate-100">

                        <div class="min-w-0 pr-2">

                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Waktu
                            </p>

                            <p class="mt-1 truncate text-xs font-semibold text-slate-700">
                                {{ $dailyLog->start_time
                                    ? substr($dailyLog->start_time, 0, 5)
                                    : '-' }}

                                –

                                {{ $dailyLog->end_time
                                    ? substr($dailyLog->end_time, 0, 5)
                                    : '-' }}
                            </p>

                        </div>

                        <div class="min-w-0 px-3">

                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Jarak
                            </p>

                            <p class="mt-1 truncate text-xs font-semibold text-slate-700">
                                {{ $distance !== null
                                    ? number_format($distance, 0, ',', '.') . ' km'
                                    : '-' }}
                            </p>

                        </div>

                        <div class="min-w-0 pl-3">

                            <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">
                                Struk
                            </p>

                            <p class="mt-1 truncate text-xs font-semibold text-slate-700">
                                {{ $receiptCount }} file
                            </p>

                        </div>

                    </div>

                    {{-- Cost --}}
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">

                        <p class="text-xs text-slate-500">
                            Total pengeluaran
                        </p>

                        <p class="text-sm font-bold text-slate-900">
                            Rp {{ number_format($totalCost, 0, ',', '.') }}
                        </p>

                    </div>

                </a>

                {{-- Actions --}}
                <div class="grid grid-cols-2 border-t border-slate-100">

                    <a
                        href="{{ route('driver.logs.edit', $dailyLog) }}"
                        class="flex items-center justify-center gap-2 border-r border-slate-100 px-4 py-3 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50 active:bg-indigo-100"
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
                                d="m16.862 3.487 3.651 3.651M5.25 18.75l4.243-.943 10.16-10.16a2.582 2.582 0 00-3.652-3.652L5.84 14.155 5.25 18.75z"
                            />
                        </svg>

                        Edit
                    </a>

                    <form
                        method="POST"
                        action="{{ route('driver.logs.destroy', $dailyLog) }}"
                        onsubmit="return confirm('Hapus log perjalanan ini? Data dan struk yang terkait juga dapat ikut terhapus.')"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 active:bg-red-100"
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

                            Hapus
                        </button>

                    </form>

                </div>

            </article>

        @empty

            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2Zm2 5h6M9 12h6M9 16h4"
                        />
                    </svg>

                </div>

                <h3 class="mt-4 text-base font-bold text-slate-900">
                    Belum ada perjalanan
                </h3>

                <p class="mx-auto mt-2 max-w-xs text-sm leading-6 text-slate-500">
                    Tambahkan log perjalanan pertama Anda untuk mulai mencatat aktivitas kendaraan.
                </p>

                <a
                    href="{{ route('driver.logs.create') }}"
                    class="mt-5 inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition active:scale-95"
                >
                    Tambah Perjalanan
                </a>

            </div>

        @endforelse

    </section>

    {{-- Pagination --}}
    @if ($dailyLogs->hasPages())
        <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
            {{ $dailyLogs->links() }}
        </div>
    @endif

</div>

@endsection
```

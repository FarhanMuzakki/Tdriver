@extends('layouts.driver')

@section('title', 'Dashboard Driver')

@section('content')



<div class="space-y-5">

    {{-- Greeting --}}
    <section class="rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 p-5 text-white shadow-lg">

        <p class="text-sm text-indigo-100">
            Selamat datang,
        </p>

        <h1 class="mt-1 text-2xl font-bold leading-tight">
            {{ auth()->user()->name }}
        </h1>

        <p class="mt-2 text-sm text-indigo-100">
            {{ now()->translatedFormat('d F Y') }}
        </p>

        <a
 @if ($mainAssignment)
    <a
        href="{{ route('driver.assignments.edit', $mainAssignment) }}"
        class="mt-5 flex w-full items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-bold text-indigo-700 shadow-sm transition active:scale-[0.98]"
    >
        Update Assignment
    </a>
@else
    <a
        href="{{ route('driver.logs.create') }}"
        class="mt-5 flex w-full items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-bold text-indigo-700 shadow-sm transition active:scale-[0.98]"
    >
        Catat Perjalanan
    </a>
@endif

    </section>

{{-- Kendaraan Aktif --}}
<section>

    <div class="mb-3 flex items-center justify-between">

        <div>
            <h2 class="text-base font-bold text-slate-900">
                Assignment Aktif
            </h2>

            <p class="text-xs text-slate-500">
                Tugas kendaraan dari admin
            </p>
        </div>

        @if ($activeAssignments->isNotEmpty())
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                {{ $activeAssignments->count() }} Aktif
            </span>
        @endif

    </div>

    @if ($mainAssignment)

        <div class="overflow-hidden rounded-3xl bg-slate-900 p-5 text-white shadow-lg">

            <div class="flex items-start justify-between gap-4">

                <div class="min-w-0">

                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">
                        Nomor Polisi
                    </p>

                    <h3 class="mt-2 truncate text-3xl font-extrabold">
                        {{ $mainAssignment->vehicle?->plate_number ?? '-' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-300">
                        {{ $mainAssignment->vehicle?->brand ?? '-' }}
                        {{ $mainAssignment->vehicle?->model ?? '' }}
                    </p>

                </div>

                <span class="shrink-0 rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-300">
                    Aktif
                </span>

            </div>

            <div class="mt-5 rounded-2xl bg-white/10 p-4">

                <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400">
                    Tugas Assignment
                </p>

                <h4 class="mt-2 text-base font-bold text-white">
                    {{ $mainAssignment->destination ?? 'Tujuan belum diisi' }}
                </h4>

                <p class="mt-2 text-sm leading-relaxed text-slate-300">
                    {{ $mainAssignment->purpose ?? 'Keperluan belum diisi' }}
                </p>

                @if ($mainAssignment->notes)
                    <p class="mt-3 rounded-xl bg-black/20 p-3 text-xs leading-relaxed text-slate-300">
                        Catatan: {{ $mainAssignment->notes }}
                    </p>
                @endif

            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">

                <div class="rounded-2xl bg-white/10 p-3">

                    <p class="text-[11px] text-slate-300">
                        Mulai Assignment
                    </p>

                    <p class="mt-1 text-xs font-semibold">
                        {{ $mainAssignment->assigned_at
                            ? \Carbon\Carbon::parse($mainAssignment->assigned_at)->format('d M Y, H:i')
                            : '-' }}
                    </p>

                </div>

                <div class="rounded-2xl bg-white/10 p-3">

                    <p class="text-[11px] text-slate-300">
                        Rencana Kembali
                    </p>

                    <p class="mt-1 text-xs font-semibold">
                        {{ $mainAssignment->planned_return_at
                            ? \Carbon\Carbon::parse($mainAssignment->planned_return_at)->format('d M Y, H:i')
                            : 'Belum ditentukan' }}
                    </p>

                </div>

            </div>

        </div>

        @if ($activeAssignments->count() > 1)

            <div class="mt-3 rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-3">

                <p class="text-xs font-medium text-indigo-700">
                    Anda memiliki {{ $activeAssignments->count() }} assignment aktif.
                    Kendaraan lainnya dapat dipilih saat membuat log perjalanan.
                </p>

            </div>

        @endif

    @else

        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-6 text-center shadow-sm">

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
                        d="M5 17h14M6.5 17l-1-5L7.5 7h9l2 5-1 5M8 17v2M16 17v2"
                    />
                </svg>

            </div>

            <h3 class="mt-4 text-base font-bold text-slate-900">
                Belum ada kendaraan
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Assignment kendaraan dari admin akan tampil di sini.
            </p>

        </div>

    @endif

</section>

{{-- Ringkasan Hari Ini --}}
<section>

    <div class="mb-3">

        <h2 class="text-base font-bold text-slate-900">
            Aktivitas Hari Ini
        </h2>

        <p class="text-xs text-slate-500">
            Ringkasan log berdasarkan assignment aktif
        </p>

    </div>

    <div class="grid grid-cols-3 gap-3">

        {{-- Log --}}
        <div class="min-w-0 rounded-3xl bg-white p-3 shadow-sm">

            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 3h12a1 1 0 011 1v16a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1Zm3 5h6M9 12h6M9 16h4"
                    />
                </svg>

            </div>

            <p class="mt-3 text-xl font-bold text-slate-900">
                {{ $todayLogCount }}
            </p>

            <p class="text-[10px] leading-tight text-slate-500">
                Perjalanan
            </p>

        </div>

        {{-- Jarak --}}
        <div class="min-w-0 rounded-3xl bg-white p-3 shadow-sm">

            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 19 10 5m4 14 5-14M12 4v3m0 4v3m0 4v2"
                    />
                </svg>

            </div>

            <p class="mt-3 truncate text-lg font-bold text-slate-900">
                {{ number_format($todayDistance, 0, ',', '.') }}
            </p>

            <p class="text-[10px] leading-tight text-slate-500">
                Kilometer
            </p>

        </div>

        {{-- Biaya --}}
        <div class="min-w-0 rounded-3xl bg-white p-3 shadow-sm">

            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 7h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2Zm12 4h4M7 7V5h10v2"
                    />
                </svg>

            </div>

            <p class="mt-3 break-all text-xs font-bold leading-tight text-slate-900">
                Rp {{ number_format($todayCost, 0, ',', '.') }}
            </p>

            <p class="text-[10px] leading-tight text-slate-500">
                Rupiah
            </p>

        </div>

    </div>

    {{-- Tugas Hari Ini --}}
    <div class="mt-4 space-y-3">

        @forelse ($todayTasks as $assignment)

            <div class="rounded-3xl bg-white p-4 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">

                        <p class="text-xs font-semibold text-slate-500">
                            {{ $assignment->vehicle?->plate_number ?? '-' }}
                        </p>

                        <h3 class="mt-1 truncate text-sm font-bold text-slate-900">
                            {{ $assignment->destination ?? 'Tujuan belum diisi' }}
                        </h3>

                        <p class="mt-1 line-clamp-2 text-xs text-slate-500">
                            {{ $assignment->purpose ?? 'Keperluan belum diisi' }}
                        </p>

                    </div>

                    @if ($assignment->is_logged_today)
                        <span class="shrink-0 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700">
                            Sudah Dicatat
                        </span>
                    @else
                        <span class="shrink-0 rounded-full bg-amber-50 px-3 py-1 text-[10px] font-bold text-amber-700">
                            Belum Dicatat
                        </span>
                    @endif

                </div>

                <div class="mt-4 grid grid-cols-3 gap-3 border-t border-slate-100 pt-3">

                    <div>
                        <p class="text-[10px] text-slate-400">
                            Log
                        </p>

                        <p class="text-sm font-bold text-slate-900">
                            {{ $assignment->today_log_count }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] text-slate-400">
                            Jarak
                        </p>

                        <p class="text-sm font-bold text-slate-900">
                            {{ number_format($assignment->today_distance, 0, ',', '.') }} km
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] text-slate-400">
                            Biaya
                        </p>

                        <p class="text-sm font-bold text-slate-900">
                            Rp {{ number_format($assignment->today_cost, 0, ',', '.') }}
                        </p>
                    </div>

                </div>

                <a
    href="{{ route('driver.assignments.edit', $assignment) }}"
    class="mt-4 flex items-center justify-center rounded-2xl bg-indigo-50 px-4 py-2 text-xs font-bold text-indigo-700"
>
    Update Assignment
</a>

            </div>

        @empty

            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-5 text-center">

                <h3 class="text-sm font-bold text-slate-900">
                    Belum ada tugas hari ini
                </h3>

                <p class="mt-2 text-xs text-slate-500">
                    Tugas dari admin akan tampil di bagian ini.
                </p>

            </div>

        @endforelse

    </div>

</section>
    {{-- Menu Cepat --}}
    <section>

        <div class="mb-3">

            <h2 class="text-base font-bold text-slate-900">
                Menu Cepat
            </h2>

            <p class="text-xs text-slate-500">
                Akses fitur utama
            </p>

        </div>

        <div class="grid grid-cols-2 gap-3">

            {{-- Tambah Log --}}
            <a
                href="{{ route('driver.logs.create') }}"
                class="rounded-3xl bg-indigo-600 p-4 text-white shadow-lg transition active:scale-[0.98]"
            >

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
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

                </div>

                <p class="mt-4 text-sm font-bold">
                    Tambah Log
                </p>

                <p class="mt-1 text-[11px] text-indigo-100">
                    Catat perjalanan baru
                </p>

            </a>

            {{-- Maintenance --}}
            <a
                href="{{ route('driver.maintenance-requests.index') }}"
                class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm transition active:scale-[0.98]"
            >

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-red-50 text-red-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11.42 15.17l-5.59 5.59a2.25 2.25 0 01-3.18-3.18l5.59-5.59m3.18 3.18 6.83-6.83a4.5 4.5 0 00-5.66-6.93l-3.42 3.42 6 6 3.42-3.42"
                        />
                    </svg>

                </div>

                <p class="mt-4 text-sm font-bold text-slate-900">
                    Maintenance
                </p>

                <p class="mt-1 text-[11px] text-slate-500">
                    Laporkan kerusakan
                </p>

            </a>

            {{-- Riwayat --}}
            <a
                href="{{ route('driver.logs.index') }}"
                class="col-span-2 flex items-center gap-4 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm transition active:scale-[0.98]"
            >

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2Zm2 5h6M9 12h6M9 16h4"
                        />
                    </svg>

                </div>

                <div class="min-w-0 flex-1">

                    <p class="text-sm font-bold text-slate-900">
                        Riwayat Perjalanan
                    </p>

                    <p class="mt-1 text-[11px] text-slate-500">
                        Lihat semua log perjalanan
                    </p>

                </div>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 shrink-0 text-slate-400"
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

            </a>

        </div>

    </section>

    {{-- Perjalanan Terbaru --}}
    <section>

        <div class="mb-3 flex items-center justify-between">

            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Perjalanan Terbaru
                </h2>

                <p class="text-xs text-slate-500">
                    Aktivitas terakhir Anda
                </p>
            </div>

            <a
                href="{{ route('driver.logs.index') }}"
                class="text-xs font-semibold text-indigo-600"
            >
                Semua
            </a>

        </div>

        <div class="space-y-3">

            @forelse ($recentLogs as $dailyLog)

                <a
                    href="{{ route('driver.logs.edit', $dailyLog) }}"
                    class="block rounded-3xl bg-white p-4 shadow-sm transition active:scale-[0.99]"
                >

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <div class="flex items-center gap-2">

                                <h3 class="truncate text-sm font-bold text-slate-900">
                                    {{ $dailyLog->vehicle?->plate_number ?? '-' }}
                                </h3>

                                <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">
                                    {{ $dailyLog->receipts?->count() ?? 0 }} struk
                                </span>

                            </div>

                            <p class="mt-1 truncate text-sm text-slate-700">
                                {{ $dailyLog->destination ?? '-' }}
                            </p>

                            <p class="mt-1 line-clamp-2 text-xs text-slate-500">
                                {{ $dailyLog->purpose ?? '-' }}
                            </p>

                        </div>

                        <span class="shrink-0 rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold text-indigo-600">
                            {{ $dailyLog->log_date
                                ? \Carbon\Carbon::parse($dailyLog->log_date)->format('d M')
                                : '-' }}
                        </span>

                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">

                        <div class="text-xs text-slate-500">
                            {{ $dailyLog->start_time
                                ? substr($dailyLog->start_time, 0, 5)
                                : '-' }}
                            -
                            {{ $dailyLog->end_time
                                ? substr($dailyLog->end_time, 0, 5)
                                : '-' }}
                        </div>

                        <div class="text-xs font-semibold text-slate-900">

                            @if (
                                $dailyLog->start_odometer !== null &&
                                $dailyLog->end_odometer !== null
                            )
                                {{ number_format(
                                    max(
                                        0,
                                        $dailyLog->end_odometer
                                        - $dailyLog->start_odometer
                                    ),
                                    0,
                                    ',',
                                    '.'
                                ) }} km
                            @else
                                0 km
                            @endif

                        </div>

                    </div>

                </a>

            @empty

                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-6 text-center">

                    <h3 class="text-sm font-bold text-slate-900">
                        Belum ada perjalanan
                    </h3>

                    <p class="mt-2 text-xs text-slate-500">
                        Log perjalanan yang sudah dicatat akan tampil di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection
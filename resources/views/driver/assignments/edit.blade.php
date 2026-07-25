@extends('layouts.driver')

@section('title', 'Update Assignment')

@section('content')

<div class="space-y-5">

    {{-- Header Assignment --}}
    <section class="overflow-hidden rounded-3xl bg-slate-900 text-white shadow-lg">

        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 p-5">

            <p class="text-xs uppercase tracking-[0.2em] text-indigo-100">
                Assignment Aktif
            </p>

            <div class="mt-3 flex items-start justify-between gap-4">

                <div class="min-w-0">

                    <h1 class="truncate text-3xl font-extrabold">
                        {{ $assignment->vehicle?->plate_number ?? '-' }}
                    </h1>

                    <p class="mt-1 text-sm text-indigo-100">
                        {{ $assignment->vehicle?->brand ?? '-' }}
                        {{ $assignment->vehicle?->model ?? '' }}
                    </p>

                </div>

                <span class="shrink-0 rounded-full bg-white/20 px-3 py-1 text-xs font-bold text-white">
                    Aktif
                </span>

            </div>

        </div>

        <div class="space-y-4 p-5">

            <div class="rounded-2xl bg-white/10 p-4">

                <p class="text-[11px] uppercase tracking-[0.18em] text-slate-400">
                    Tujuan
                </p>

                <h2 class="mt-2 text-base font-bold">
                    {{ $assignment->destination ?? 'Tujuan belum diisi' }}
                </h2>

                <p class="mt-3 text-[11px] uppercase tracking-[0.18em] text-slate-400">
                    Keperluan
                </p>

                <p class="mt-2 text-sm leading-relaxed text-slate-300">
                    {{ $assignment->purpose ?? 'Keperluan belum diisi' }}
                </p>

            </div>

            <div class="grid grid-cols-2 gap-3">

                <div class="rounded-2xl bg-white/10 p-3">

                    <p class="text-[11px] text-slate-400">
                        Mulai
                    </p>

                    <p class="mt-1 text-xs font-semibold">
                        {{ $assignment->assigned_at
                            ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y, H:i')
                            : '-' }}
                    </p>

                </div>

                <div class="rounded-2xl bg-white/10 p-3">

                    <p class="text-[11px] text-slate-400">
                        Rencana Kembali
                    </p>

                    <p class="mt-1 text-xs font-semibold">
                        {{ $assignment->planned_return_at
                            ? \Carbon\Carbon::parse($assignment->planned_return_at)->format('d M Y, H:i')
                            : 'Belum ditentukan' }}
                    </p>

                </div>

            </div>

        </div>

    </section>

    {{-- Pesan Error --}}
    @if ($errors->any())

        <section class="rounded-3xl bg-red-50 p-4 text-sm text-red-700">

            <p class="mb-2 font-bold">
                Data belum sesuai:
            </p>

            <ul class="list-disc space-y-1 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </section>

    @endif

    {{-- Form Update Aktivitas --}}
    <section class="rounded-3xl bg-white p-5 shadow-sm">

        <div class="mb-5">

            <h2 class="text-base font-bold text-slate-900">
                Update Aktivitas
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Simpan jam perjalanan, odometer, dan catatan setelah semua struk selesai diunggah.
            </p>

        </div>

        <form
            action="{{ route('driver.assignments.update', $assignment) }}"
            method="POST"
            class="space-y-5"
        >
            @csrf
            @method('PUT')

            <input type="hidden" name="action" value="save_assignment">

            {{-- Waktu --}}
            <div>

                <h3 class="mb-3 text-sm font-bold text-slate-900">
                    Waktu Perjalanan
                </h3>

                <div class="grid grid-cols-2 gap-3">

                    <div>
                        <label class="text-xs font-semibold text-slate-600">
                            Jam Mulai
                        </label>

                        <input
                            type="time"
                            name="start_time"
                            value="{{ old('start_time', $dailyLog?->start_time ? substr($dailyLog->start_time, 0, 5) : '') }}"
                            class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600">
                            Jam Selesai
                        </label>

                        <input
                            type="time"
                            name="end_time"
                            value="{{ old('end_time', $dailyLog?->end_time ? substr($dailyLog->end_time, 0, 5) : '') }}"
                            class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                </div>

            </div>

            {{-- Odometer --}}
            <div>

                <h3 class="mb-3 text-sm font-bold text-slate-900">
                    Odometer
                </h3>

                <div class="grid grid-cols-2 gap-3">

                    <div>
                        <label class="text-xs font-semibold text-slate-600">
                            Odometer Awal
                        </label>

                        <input
                            type="number"
                            name="start_odometer"
                            value="{{ old('start_odometer', $dailyLog?->start_odometer) }}"
                            class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: 12000"
                            required
                        >
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600">
                            Odometer Akhir
                        </label>

                        <input
                            type="number"
                            name="end_odometer"
                            value="{{ old('end_odometer', $dailyLog?->end_odometer) }}"
                            class="mt-1 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: 12050"
                            required
                        >
                    </div>

                </div>

            </div>

            {{-- Catatan --}}
            <div>

                <label class="text-sm font-bold text-slate-900">
                    Catatan Perjalanan
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    class="mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Tambahkan catatan perjalanan jika diperlukan"
                >{{ old('notes', $dailyLog?->notes) }}</textarea>

            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition active:scale-[0.98]"
            >
                Simpan Update Assignment
            </button>

        </form>

    </section>

    {{-- Upload Struk --}}
    <section class="rounded-3xl bg-white p-5 shadow-sm">

        <div class="mb-5 flex items-start justify-between gap-3">

            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Upload Struk Pengeluaran
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Upload bon BBM, tol, parkir, atau biaya lainnya. Total biaya akan otomatis dihitung dari semua struk.
                </p>
            </div>

            <div class="shrink-0 rounded-2xl bg-slate-900 px-4 py-3 text-right text-white">

                <p class="text-[10px] uppercase tracking-wide text-slate-400">
                    Total
                </p>

                <p class="mt-1 text-sm font-bold">
                    Rp {{ number_format($dailyLog?->receipts?->sum('amount') ?? 0, 0, ',', '.') }}
                </p>

            </div>

        </div>

        <form
            action="{{ route('driver.assignments.update', $assignment) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4 rounded-3xl border border-slate-100 bg-slate-50 p-4"
        >
            @csrf
            @method('PUT')

            <input type="hidden" name="action" value="upload_receipt">

            <div class="grid grid-cols-2 gap-3">

                <div>
                    <label class="text-xs font-semibold text-slate-600">
                        Jenis Struk
                    </label>

                    <select
                        name="receipt_type"
                        class="mt-1 w-full rounded-2xl border-slate-200 bg-white text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                        <option value="">Pilih jenis</option>
                        <option value="fuel" {{ old('receipt_type') === 'fuel' ? 'selected' : '' }}>
                            BBM
                        </option>
                        <option value="toll" {{ old('receipt_type') === 'toll' ? 'selected' : '' }}>
                            Tol
                        </option>
                        <option value="parking" {{ old('receipt_type') === 'parking' ? 'selected' : '' }}>
                            Parkir
                        </option>
                        <option value="other" {{ old('receipt_type') === 'other' ? 'selected' : '' }}>
                            Lainnya
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-600">
                        Nominal
                    </label>

                    <input
                        type="number"
                        name="receipt_amount"
                        value="{{ old('receipt_amount') }}"
                        class="mt-1 w-full rounded-2xl border-slate-200 bg-white text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="50000"
                        required
                    >
                </div>

            </div>

            <div>

                <label class="text-xs font-semibold text-slate-600">
                    File Struk
                </label>

                <input
                    type="file"
                    name="receipt"
                    accept="image/*"
                    class="mt-1 w-full rounded-2xl border border-slate-200 bg-white p-3 text-sm file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-xs file:font-bold file:text-indigo-700"
                    required
                >

                <p class="mt-2 text-[11px] text-slate-400">
                    Format yang didukung: JPG, JPEG, PNG, atau WEBP.
                </p>

            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white shadow-sm transition active:scale-[0.98]"
            >
                Upload Struk
            </button>

        </form>

    </section>

    {{-- Daftar Struk --}}
    <section class="rounded-3xl bg-white p-5 shadow-sm">

        <div class="mb-5">

            <h2 class="text-base font-bold text-slate-900">
                Struk yang Sudah Diupload
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Daftar struk pada assignment hari ini.
            </p>

        </div>

        @if ($dailyLog && $dailyLog->receipts->isNotEmpty())

            <div class="space-y-3">

                @foreach ($dailyLog->receipts as $receipt)

                    <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl text-xs font-bold
                                    @if ($receipt->type === 'fuel')
                                        bg-emerald-50 text-emerald-600
                                    @elseif ($receipt->type === 'toll')
                                        bg-indigo-50 text-indigo-600
                                    @elseif ($receipt->type === 'parking')
                                        bg-amber-50 text-amber-600
                                    @else
                                        bg-slate-100 text-slate-600
                                    @endif
                                ">

                                    @if ($receipt->type === 'fuel')
                                        BBM
                                    @elseif ($receipt->type === 'toll')
                                        Tol
                                    @elseif ($receipt->type === 'parking')
                                        P
                                    @else
                                        +
                                    @endif

                                </div>

                                <div class="min-w-0">

                                    <p class="text-sm font-bold text-slate-900">
                                        @if ($receipt->type === 'fuel')
                                            Bahan Bakar
                                        @elseif ($receipt->type === 'toll')
                                            Tol
                                        @elseif ($receipt->type === 'parking')
                                            Parkir
                                        @else
                                            Lainnya
                                        @endif
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Rp {{ number_format($receipt->amount, 0, ',', '.') }}
                                    </p>

                                </div>

                            </div>

                            <div class="flex shrink-0 items-center gap-2">

                                <a
                                    href="{{ asset('storage/' . $receipt->file_path) }}"
                                    target="_blank"
                                    class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-indigo-700 shadow-sm"
                                >
                                    Lihat
                                </a>

                                <form
                                    action="{{ route('driver.receipts.destroy', $receipt) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus struk ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-xl bg-red-50 px-3 py-2 text-xs font-bold text-red-700"
                                    >
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">

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
                            d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"
                        />
                    </svg>

                </div>

                <h3 class="mt-4 text-sm font-bold text-slate-900">
                    Belum ada struk
                </h3>

                <p class="mt-2 text-xs text-slate-500">
                    Struk yang diunggah akan tampil di bagian ini.
                </p>

            </div>

        @endif

    </section>

</div>

@endsection
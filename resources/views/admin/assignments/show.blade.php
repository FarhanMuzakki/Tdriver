@extends('layouts.admin')

@section('title', 'Detail Assignment')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

```
{{-- Header --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            Detail Assignment
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Informasi penugasan driver dan kendaraan.
        </p>
    </div>

    <a
        href="{{ route('admin.assignments.index') }}"
        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
    >
        Kembali
    </a>

</div>

{{-- Flash message --}}
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

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- Informasi assignment --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm lg:col-span-2">

        <div class="border-b border-gray-200 px-6 py-5">
            <h2 class="font-semibold text-gray-900">
                Informasi Assignment
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            {{-- Driver --}}
            <div>
                <p class="text-sm text-gray-500">
                    Driver
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->driver?->name ?? '-' }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $assignment->driver?->email ?? '-' }}
                </p>
            </div>

            {{-- Kendaraan --}}
            <div>
                <p class="text-sm text-gray-500">
                    Kendaraan
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->vehicle?->plate_number ?? '-' }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $assignment->vehicle?->brand ?? '-' }}
                    {{ $assignment->vehicle?->model ?? '' }}
                </p>
            </div>

            {{-- Tanggal mulai --}}
            <div>
                <p class="text-sm text-gray-500">
                    Tanggal Mulai
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->assigned_at?->format('d M Y H:i') ?? '-' }}
                </p>
            </div>

            {{-- Rencana kembali --}}
            <div>
                <p class="text-sm text-gray-500">
                    Rencana Kembali
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->planned_return_at?->format('d M Y H:i') ?? '-' }}
                </p>
            </div>

            {{-- Tujuan --}}
            <div>
                <p class="text-sm text-gray-500">
                    Tujuan
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->destination ?? '-' }}
                </p>
            </div>

            {{-- Keperluan --}}
            <div>
                <p class="text-sm text-gray-500">
                    Keperluan
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->purpose ?? '-' }}
                </p>
            </div>

            {{-- Catatan --}}
            <div class="sm:col-span-2">
                <p class="text-sm text-gray-500">
                    Catatan
                </p>

                <p class="mt-1 whitespace-pre-line text-gray-900">
                    {{ $assignment->notes ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    {{-- Status dan action --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-gray-500">
            Status Assignment
        </p>

        <div class="mt-3">

            @if ($assignment->status === 'active')
                <span class="inline-flex rounded-full bg-green-50 px-4 py-2 text-sm font-semibold text-green-700">
                    Aktif
                </span>
            @elseif ($assignment->status === 'finished')
                <span class="inline-flex rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600">
                    Selesai
                </span>
            @else
                <span class="inline-flex rounded-full bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700">
                    {{ ucfirst($assignment->status) }}
                </span>
            @endif

        </div>

        @if ($assignment->returned_at)
            <div class="mt-5 border-t border-gray-200 pt-5">

                <p class="text-sm text-gray-500">
                    Dikembalikan pada
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $assignment->returned_at->format('d M Y H:i') }}
                </p>

            </div>
        @endif

        <div class="mt-6 space-y-3">

            @if ($assignment->vehicle)
                <a
                    href="{{ route('admin.vehicles.show', $assignment->vehicle) }}"
                    class="block rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Lihat Kendaraan
                </a>
            @endif

            @if ($assignment->driver)
                <a
                    href="{{ route('admin.drivers.show', $assignment->driver) }}"
                    class="block rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Lihat Driver
                </a>
            @endif

            @if ($assignment->status === 'active')
                <form
                    method="POST"
                    action="{{ route('admin.assignments.finish', $assignment) }}"
                    onsubmit="return confirm('Yakin ingin menyelesaikan assignment ini?')"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
                    >
                        Selesaikan Assignment
                    </button>
                </form>
            @endif

        </div>

    </div>

</div>
```

</div>

@endsection

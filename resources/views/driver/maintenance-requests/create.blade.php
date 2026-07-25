@extends('layouts.driver')

@section('title', 'Ajukan Maintenance')

@section('content')

<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">

        <a
            href="{{ route('driver.maintenance-requests.index') }}"
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm"
            aria-label="Kembali"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.75 19.5L8.25 12l7.5-7.5"
                />
            </svg>
        </a>

        <div>
            <h1 class="text-xl font-bold text-slate-900">
                Ajukan Maintenance
            </h1>

            <p class="mt-0.5 text-sm text-slate-500">
                Laporkan masalah kendaraan.
            </p>
        </div>

    </div>

    {{-- Validation summary --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4">

            <p class="text-sm font-semibold text-red-700">
                Periksa kembali data pengajuan.
            </p>

            <ul class="mt-2 space-y-1 text-xs text-red-600">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('driver.maintenance-requests.store') }}"
        class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm"
    >
        @csrf

        @include('driver.maintenance-requests._form')

    </form>

</div>

@endsection
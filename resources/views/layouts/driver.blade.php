<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Driver App')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        html {
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }

        body {
            overscroll-behavior-y: none;
        }

        .driver-app {
            padding-bottom: calc(
                100px + env(safe-area-inset-bottom)
            );
        }

        .driver-bottom-nav {
            padding-bottom: max(
                8px,
                env(safe-area-inset-bottom)
            );
        }
    </style>
</head>

<body class="bg-slate-200 text-slate-900 antialiased">

    <div class="driver-app mx-auto min-h-screen w-full max-w-md bg-[#f5f7fb] shadow-xl">

        {{-- Header --}}
        <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur-xl">

            <div class="flex min-h-16 items-center justify-between gap-3 px-4 py-3">

                {{-- Brand --}}
                <a
                    href="{{ route('driver.dashboard') }}"
                    class="flex min-w-0 items-center gap-3"
                >
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-md shadow-indigo-200">

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
                                d="M5 17h14M6.5 17l-1-5L7.5 7h9l2 5-1 5M7 12h10M8 17v2M16 17v2"
                            />
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="truncate text-sm font-bold text-slate-900">
                            Vehicle Management
                        </p>

                        <p class="truncate text-[11px] font-medium text-slate-500">
                            @yield('page-label', 'Driver Application')
                        </p>

                    </div>
                </a>

                {{-- User --}}
                <div class="flex shrink-0 items-center gap-2">

                    <div class="hidden text-right min-[360px]:block">

                        <p class="max-w-[96px] truncate text-xs font-semibold text-slate-900">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-[10px] font-medium text-slate-400">
                            Driver
                        </p>

                    </div>

                    {{-- Foto Profil --}}
                    <a
                        href="{{ route('driver.profile.show') }}"
                        class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-indigo-50 text-sm font-bold text-indigo-600"
                        aria-label="Buka profil"
                    >
                        @if (auth()->user()->profile_photo_url)
                            <img
                                src="{{ auth()->user()->profile_photo_url }}"
                                alt="{{ auth()->user()->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            {{ strtoupper(
                                mb_substr(auth()->user()->name, 0, 1)
                            ) }}
                        @endif
                    </a>

                    {{-- Logout --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        onsubmit="return confirm('Yakin ingin keluar dari aplikasi?')"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 active:scale-95"
                            aria-label="Keluar"
                            title="Keluar"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-[18px] w-[18px]"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3H9m9.75 0l-3-3m3 3l-3 3"
                                />
                            </svg>
                        </button>
                    </form>

                </div>

            </div>

        </header>

        {{-- Flash success --}}
        @if (session('success'))
            <div class="px-4 pt-4">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Flash error --}}
        @if (session('error'))
            <div class="px-4 pt-4">
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Validation error umum --}}
        @if ($errors->any())
            <div class="px-4 pt-4">
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3">

                    <p class="text-sm font-semibold text-red-700">
                        Data belum dapat diproses.
                    </p>

                    <ul class="mt-2 space-y-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            </div>
        @endif

        {{-- Content --}}
        <main class="px-4 py-4">
            @yield('content')
        </main>

        {{-- Bottom Navigation --}}
        <nav class="fixed inset-x-0 bottom-0 z-50">

            <div class="driver-bottom-nav mx-auto w-full max-w-md border-t border-slate-200/80 bg-white/95 px-2 pt-2 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] backdrop-blur-xl">

                <div class="grid grid-cols-5 items-end">

                    {{-- Beranda --}}
                    <a
                        href="{{ route('driver.dashboard') }}"
                        class="group flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl px-1 py-2 transition active:scale-95
                            {{ request()->routeIs('driver.dashboard')
                                ? 'text-indigo-600'
                                : 'text-slate-400 hover:text-slate-600' }}"
                    >
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-xl transition
                                {{ request()->routeIs('driver.dashboard')
                                    ? 'bg-indigo-50'
                                    : 'group-hover:bg-slate-50' }}"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.9"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 10.5 12 3l9 7.5M5.25 9.75v10.5h13.5V9.75M9 20.25v-6h6v6"
                                />
                            </svg>
                        </div>

                        <span class="truncate text-[10px] font-semibold">
                            Beranda
                        </span>
                    </a>

                    {{-- Riwayat --}}
                    <a
                        href="{{ route('driver.logs.index') }}"
                        class="group flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl px-1 py-2 transition active:scale-95
                            {{ request()->routeIs('driver.logs.index')
                                || request()->routeIs('driver.logs.show')
                                || request()->routeIs('driver.logs.edit')
                                    ? 'text-indigo-600'
                                    : 'text-slate-400 hover:text-slate-600' }}"
                    >
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-xl transition
                                {{ request()->routeIs('driver.logs.index')
                                    || request()->routeIs('driver.logs.show')
                                    || request()->routeIs('driver.logs.edit')
                                        ? 'bg-indigo-50'
                                        : 'group-hover:bg-slate-50' }}"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.9"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6.75 3.75h10.5A2.25 2.25 0 0119.5 6v12a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V6a2.25 2.25 0 012.25-2.25ZM8.25 8.25h7.5M8.25 12h7.5M8.25 15.75h4.5"
                                />
                            </svg>
                        </div>

                        <span class="truncate text-[10px] font-semibold">
                            Riwayat
                        </span>
                    </a>

                    {{-- Tambah Log --}}
                    <a
                        href="{{ route('driver.logs.create') }}"
                        class="relative -mt-7 flex min-w-0 flex-col items-center justify-center gap-1 px-1 py-2"
                    >
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl border-4 border-white bg-indigo-600 text-white shadow-lg shadow-indigo-200 transition active:scale-95
                                {{ request()->routeIs('driver.logs.create')
                                    ? 'ring-4 ring-indigo-100'
                                    : '' }}"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
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

                        <span
                            class="truncate text-[10px] font-semibold
                                {{ request()->routeIs('driver.logs.create')
                                    ? 'text-indigo-600'
                                    : 'text-slate-500' }}"
                        >
                            Tambah
                        </span>
                    </a>

                    {{-- Maintenance --}}
                    <a
                        href="{{ route('driver.maintenance-requests.index') }}"
                        class="group flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl px-1 py-2 transition active:scale-95
                            {{ request()->routeIs('driver.maintenance-requests.*')
                                ? 'text-indigo-600'
                                : 'text-slate-400 hover:text-slate-600' }}"
                    >
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-xl transition
                                {{ request()->routeIs('driver.maintenance-requests.*')
                                    ? 'bg-indigo-50'
                                    : 'group-hover:bg-slate-50' }}"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.9"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.7 6.3a4.5 4.5 0 01-5.86 5.86L4.5 16.5a2.12 2.12 0 103 3l4.34-4.34A4.5 4.5 0 0017.7 9.3l-2.4 2.4-3-3 2.4-2.4Z"
                                />
                            </svg>
                        </div>

                        <span class="truncate text-[10px] font-semibold">
                            Service
                        </span>
                    </a>

                    {{-- Profil --}}
                    <a
                        href="{{ route('driver.profile.show') }}"
                        class="group flex min-w-0 flex-col items-center justify-center gap-1 rounded-2xl px-1 py-2 transition active:scale-95
                            {{ request()->routeIs('driver.profile.*')
                                ? 'text-indigo-600'
                                : 'text-slate-400 hover:text-slate-600' }}"
                    >
                        <div
                            class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-xl transition
                                {{ request()->routeIs('driver.profile.*')
                                    ? 'bg-indigo-50'
                                    : 'group-hover:bg-slate-50' }}"
                        >
                            @if (auth()->user()->profile_photo_url)
                                <img
                                    src="{{ auth()->user()->profile_photo_url }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="h-8 w-8 rounded-xl object-cover"
                                >
                            @else
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.9"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0"
                                    />
                                </svg>
                            @endif
                        </div>

                        <span class="truncate text-[10px] font-semibold">
                            Profil
                        </span>
                    </a>

                </div>

            </div>

        </nav>

    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Admin Dashboard') — FleetSys
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    <style>
        html {
            scroll-behavior: smooth;
            -webkit-tap-highlight-color: transparent;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-900 antialiased">

    <div
        x-data="{ sidebarOpen: false }"
        class="min-h-screen"
    >
        {{-- Mobile overlay --}}
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        {{-- Sidebar --}}
        @include('partials.admin.sidebar')

        {{-- Main --}}
        <div class="min-h-screen lg:pl-72">

            @include('partials.admin.navbar')

            {{-- Flash messages --}}
            <div class="mx-auto w-full max-w-[1600px] px-4 pt-4 sm:px-6 lg:px-8">

                @if (session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 shadow-sm">

                        <p class="text-sm font-semibold text-red-700">
                            Data belum dapat diproses.
                        </p>

                        <ul class="mt-2 space-y-1 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

            </div>

            {{-- Content --}}
            <main class="mx-auto w-full max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>

        </div>
    </div>

</body>

</html>
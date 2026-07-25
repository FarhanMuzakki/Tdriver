@php
    $menuItems = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
            'icon' => 'home',
        ],
        [
            'label' => 'Kendaraan',
            'route' => 'admin.vehicles.index',
            'active' => 'admin.vehicles.*',
            'icon' => 'vehicle',
        ],
        [
            'label' => 'Driver',
            'route' => 'admin.drivers.index',
            'active' => 'admin.drivers.*',
            'icon' => 'user',
        ],
        [
            'label' => 'Assignment',
            'route' => 'admin.assignments.index',
            'active' => 'admin.assignments.*',
            'icon' => 'assignment',
        ],
        [
            'label' => 'Maintenance',
            'route' => 'admin.maintenance.index',
            'active' => 'admin.maintenance.*',
            'exclude' => 'admin.maintenance-requests.*',
            'icon' => 'maintenance',
        ],
        [
            'label' => 'Pengajuan Service',
            'route' => 'admin.maintenance-requests.index',
            'active' => 'admin.maintenance-requests.*',
            'icon' => 'request',
        ],
        [
            'label' => 'Log Perjalanan',
            'route' => 'admin.logs.index',
            'active' => 'admin.logs.*',
            'icon' => 'log',
        ],
        [
            'label' => 'Laporan',
            'route' => 'admin.reports.index',
            'active' => 'admin.reports.*',
            'icon' => 'report',
        ],
    ];
@endphp

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-800 bg-slate-950 text-white shadow-2xl transition-transform duration-300 lg:translate-x-0"
>
    {{-- Brand --}}
    <div class="flex h-20 items-center justify-between border-b border-white/10 px-5">

        <a
            href="{{ route('admin.dashboard') }}"
            class="flex min-w-0 items-center gap-3"
        >
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-500 text-white shadow-lg shadow-indigo-950/40">

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
                        d="M5 17h14M6.5 17l-1-5L7.5 7h9l2 5-1 5M7 12h10M8 17v2M16 17v2"
                    />
                </svg>

            </div>

            <div class="min-w-0">
                <p class="truncate text-lg font-bold tracking-tight">
                    FleetSys
                </p>

                <p class="truncate text-xs text-slate-400">
                    Vehicle Management
                </p>
            </div>
        </a>

        <button
            type="button"
            class="rounded-xl p-2 text-slate-400 hover:bg-white/10 hover:text-white lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Tutup sidebar"
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
                    d="M6 18 18 6M6 6l12 12"
                />
            </svg>
        </button>

    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-4 py-5">

        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[0.22em] text-slate-500">
            Menu Utama
        </p>

        <div class="space-y-1.5">

            @foreach ($menuItems as $item)

                @php
                    $isActive = request()->routeIs($item['active']);

                    if (
                        isset($item['exclude']) &&
                        request()->routeIs($item['exclude'])
                    ) {
                        $isActive = false;
                    }
                @endphp

                <a
                    href="{{ route($item['route']) }}"
                    @click="sidebarOpen = false"
                    class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium transition
                        {{ $isActive
                            ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-950/30'
                            : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition
                            {{ $isActive
                                ? 'bg-white/15'
                                : 'bg-white/5 group-hover:bg-white/10' }}"
                    >
                        @switch($item['icon'])

                            @case('home')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5M5.25 9.75v10.5h13.5V9.75M9 20.25v-6h6v6"/>
                                </svg>
                                @break

                            @case('vehicle')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 17h14M6.5 17l-1-5L7.5 7h9l2 5-1 5M7 12h10M8 17v2M16 17v2"/>
                                </svg>
                                @break

                            @case('user')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0"/>
                                </svg>
                                @break

                            @case('assignment')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H18a3 3 0 010 6h-4.5m-3 6H6a3 3 0 010-6h4.5m-3-3h9"/>
                                </svg>
                                @break

                            @case('maintenance')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a4.5 4.5 0 01-5.86 5.86L4.5 16.5a2.12 2.12 0 103 3l4.34-4.34A4.5 4.5 0 0017.7 9.3l-2.4 2.4-3-3 2.4-2.4Z"/>
                                </svg>
                                @break

                            @case('request')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.6 2.8 17.5A1.5 1.5 0 004.1 19.8h15.8a1.5 1.5 0 001.3-2.3L13.7 4.6a2 2 0 00-3.4 0Z"/>
                                </svg>
                                @break

                            @case('log')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h10.5A2.25 2.25 0 0119.5 6v12a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V6a2.25 2.25 0 012.25-2.25ZM8.25 8.25h7.5M8.25 12h7.5M8.25 15.75h4.5"/>
                                </svg>
                                @break

                            @case('report')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15M7.5 16.5v-6m4.5 6V6m4.5 10.5v-3"/>
                                </svg>
                                @break

                        @endswitch
                    </span>

                    <span class="truncate">
                        {{ $item['label'] }}
                    </span>

                    @if ($isActive)
                        <span class="ml-auto h-2 w-2 rounded-full bg-white"></span>
                    @endif
                </a>

            @endforeach

        </div>

    </nav>

    {{-- Admin profile --}}
    <div class="border-t border-white/10 p-4">

        <div class="rounded-2xl bg-white/5 p-3">

            <div class="flex items-center gap-3">

                @if (auth()->user()->profile_photo_url)
                    <img
                        src="{{ auth()->user()->profile_photo_url }}"
                        alt="{{ auth()->user()->name }}"
                        class="h-11 w-11 shrink-0 rounded-2xl object-cover"
                    >
                @else
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-500 text-sm font-bold text-white">
                        {{ strtoupper(
                            mb_substr(auth()->user()->name, 0, 1)
                        ) }}
                    </div>
                @endif

                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="truncate text-xs text-slate-400">
                        Administrator
                    </p>
                </div>

            </div>

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mt-3"
                onsubmit="return confirm('Yakin ingin keluar?')"
            >
                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 px-3 py-2.5 text-xs font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3H9m9.75 0-3-3m3 3-3 3"/>
                    </svg>

                    Keluar
                </button>
            </form>

        </div>

        <p class="mt-3 text-center text-[10px] text-slate-600">
            FleetSys v1.0
        </p>

    </div>

</aside>
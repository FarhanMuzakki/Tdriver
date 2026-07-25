<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">

    <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        <div class="flex min-w-0 items-center gap-3">

            <button
                type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Buka sidebar"
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
                        d="M4 7h16M4 12h16M4 17h16"
                    />
                </svg>
            </button>

            <div class="min-w-0">

                <p class="truncate text-lg font-bold text-slate-900">
                    @yield('title', 'Dashboard')
                </p>

                <p class="hidden truncate text-xs text-slate-500 sm:block">
                    Kelola kendaraan, driver, dan operasional perusahaan
                </p>

            </div>

        </div>

        <div class="flex shrink-0 items-center gap-3">

            <div class="hidden rounded-2xl bg-slate-100 px-4 py-2 text-right md:block">

                <p class="text-xs font-semibold text-slate-700">
                    {{ now()->translatedFormat('d F Y') }}
                </p>

                <p class="text-[10px] text-slate-400">
                    {{ now()->translatedFormat('l') }}
                </p>

            </div>

            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-1.5 pr-3 shadow-sm">

                @if (auth()->user()->profile_photo_url)
                    <img
                        src="{{ auth()->user()->profile_photo_url }}"
                        alt="{{ auth()->user()->name }}"
                        class="h-9 w-9 rounded-xl object-cover"
                    >
                @else
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-700">
                        {{ strtoupper(
                            mb_substr(auth()->user()->name, 0, 1)
                        ) }}
                    </div>
                @endif

                <div class="hidden min-w-0 sm:block">
                    <p class="max-w-32 truncate text-xs font-semibold text-slate-800">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-[10px] text-slate-400">
                        Administrator
                    </p>
                </div>

            </div>

        </div>

    </div>

</header>
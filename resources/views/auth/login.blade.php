<x-guest-layout>
    <div class="min-h-screen bg-slate-100 lg:grid lg:grid-cols-2">

        {{-- Bagian kiri --}}
        <div class="relative hidden overflow-hidden bg-slate-950 lg:flex lg:flex-col lg:justify-between">
            <div
                class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-blue-600/20 blur-3xl">
            </div>

            <div
                class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl">
            </div>

            <div class="relative z-10 p-12">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 shadow-lg shadow-blue-600/30">
                        <svg
                            class="h-7 w-7 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm7.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM3.75 15.75V9.621a2.25 2.25 0 011.007-1.872l1.486-.99A2.25 2.25 0 017.49 6.375h9.02a2.25 2.25 0 011.247.384l1.486.99a2.25 2.25 0 011.007 1.872v6.129M5.25 12h13.5"
                            />
                        </svg>
                    </div>

                    <div>
                        <div class="text-2xl font-black tracking-tight text-white">
                            T<span class="text-blue-500">Driver</span>
                        </div>

                        <p class="text-xs tracking-widest text-slate-400">
                            VEHICLE MANAGEMENT
                        </p>
                    </div>
                </a>
            </div>

            <div class="relative z-10 max-w-xl px-12 pb-16">
                <span
                    class="mb-5 inline-flex rounded-full border border-blue-400/20 bg-blue-500/10 px-4 py-2 text-sm font-medium text-blue-300"
                >
                    Sistem Manajemen Kendaraan
                </span>

                <h1 class="text-4xl font-black leading-tight text-white xl:text-5xl">
                    Kelola kendaraan dan perjalanan dalam satu sistem.
                </h1>

                <p class="mt-6 max-w-lg text-base leading-7 text-slate-400">
                    TDriver membantu admin dan driver dalam mengelola kendaraan,
                    penugasan, perjalanan, pengeluaran, serta maintenance secara
                    terintegrasi.
                </p>

                <div class="mt-10 grid grid-cols-3 gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-2xl font-bold text-white">01</p>
                        <p class="mt-1 text-xs text-slate-400">Kelola Kendaraan</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-2xl font-bold text-white">02</p>
                        <p class="mt-1 text-xs text-slate-400">Catat Perjalanan</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-2xl font-bold text-white">03</p>
                        <p class="mt-1 text-xs text-slate-400">Pantau Maintenance</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian kanan --}}
        <div class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">

                {{-- Logo mobile --}}
                <div class="mb-10 flex justify-center lg:hidden">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 shadow-lg shadow-blue-600/30">
                            <svg
                                class="h-6 w-6 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm7.5 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM3.75 15.75V9.621a2.25 2.25 0 011.007-1.872l1.486-.99A2.25 2.25 0 017.49 6.375h9.02a2.25 2.25 0 011.247.384l1.486.99a2.25 2.25 0 011.007 1.872v6.129M5.25 12h13.5"
                                />
                            </svg>
                        </div>

                        <div class="text-2xl font-black tracking-tight text-slate-900">
                            T<span class="text-blue-600">Driver</span>
                        </div>
                    </a>
                </div>

                <div class="mb-8">
                    <p class="mb-2 text-sm font-semibold text-blue-600">
                        Selamat datang kembali
                    </p>

                    <h2 class="text-3xl font-black tracking-tight text-slate-900">
                        Masuk ke akun Anda
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Masukkan email dan kata sandi untuk mengakses sistem TDriver.
                    </p>
                </div>

                <x-auth-session-status
                    class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
                    :status="session('status')"
                />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Alamat email
                        </label>

                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg
                                    class="h-5 w-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-8.69 5.79a2.25 2.25 0 01-2.12 0L2.25 6.75"
                                    />
                                </svg>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="block w-full rounded-xl border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            >
                        </div>

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label
                                for="password"
                                class="block text-sm font-semibold text-slate-700"
                            >
                                Kata sandi
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                >
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg
                                    class="h-5 w-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75a2.25 2.25 0 01-2.25-2.25v-6A2.25 2.25 0 016.75 10.5z"
                                    />
                                </svg>
                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="block w-full rounded-xl border border-slate-300 bg-white py-3.5 pl-12 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            >

                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-blue-600"
                            >
                                <svg
                                    id="eyeIcon"
                                    class="h-5 w-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember me --}}
                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >

                        <span class="text-sm text-slate-600">
                            Ingat saya
                        </span>
                    </label>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                    >
                        Masuk ke TDriver

                        <svg
                            class="h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                            />
                        </svg>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                    Sistem Informasi Manajemen Kendaraan<br>
                    PT Tesco Indomaritim
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');

            togglePassword.addEventListener('click', function () {
                const currentType = passwordInput.getAttribute('type');

                passwordInput.setAttribute(
                    'type',
                    currentType === 'password' ? 'text' : 'password'
                );
            });
        });
    </script>
</x-guest-layout>
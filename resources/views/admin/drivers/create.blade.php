@extends('layouts.admin')

@section('title', 'Tambah Driver')

@section('content')

<div class="mx-auto max-w-4xl">

    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                Tambah Driver
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan akun dan informasi driver baru.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.drivers.store') }}"
            class="space-y-6"
        >
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Nama --}}
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Nama Driver
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        required
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        required
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        required
                    >

                    @error('password')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        required
                    >
                </div>

                {{-- Telepon --}}
                <div>
                    <label
                        for="phone"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Nomor Telepon
                    </label>

                    <input
                        id="phone"
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    >

                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nomor SIM --}}
                <div>
                    <label
                        for="license_number"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Nomor SIM
                    </label>

                    <input
                        id="license_number"
                        type="text"
                        name="license_number"
                        value="{{ old('license_number') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    >

                    @error('license_number')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Masa Berlaku SIM --}}
                <div>
                    <label
                        for="license_expiry"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Masa Berlaku SIM
                    </label>

                    <input
                        id="license_expiry"
                        type="date"
                        name="license_expiry"
                        value="{{ old('license_expiry') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    >

                    @error('license_expiry')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label
                        for="driver_status"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Status Driver
                    </label>

                    <select
                        id="driver_status"
                        name="driver_status"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                        required
                    >
                        <option
                            value="active"
                            {{ old('driver_status', 'active') === 'active' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="inactive"
                            {{ old('driver_status') === 'inactive' ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>
                    </select>

                    @error('driver_status')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label
                        for="address"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Alamat
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3"
                    >{{ old('address') }}</textarea>

                    @error('address')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <div class="flex flex-col-reverse gap-3 border-t pt-6 sm:flex-row">

                <a
                    href="{{ route('admin.drivers.index') }}"
                    class="rounded-lg border border-gray-300 px-6 py-3 text-center text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white hover:bg-indigo-700"
                >
                    Simpan Driver
                </button>
<form
    method="POST"
    action="{{ route('admin.vehicles.store') }}"
    enctype="multipart/form-data"
></form>
            </div>

        </form>

    </div>

</div>

@endsection
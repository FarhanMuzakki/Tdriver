@extends('layouts.admin')

@section('title', 'Edit Driver')

@section('content')

<div class="mx-auto max-w-4xl">

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900">
                Edit Driver
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi dan foto driver.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-semibold text-red-700">
                    Data belum dapat disimpan.
                </p>

                <ul class="mt-2 space-y-1 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.drivers.update', $driver) }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Foto Driver --}}
            <section class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

                <h2 class="text-base font-semibold text-gray-900">
                    Foto Driver
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Format JPG, JPEG, PNG, atau WebP. Maksimal 2 MB.
                </p>

                <div class="mt-5 flex flex-col gap-5 sm:flex-row sm:items-center">

                    <div class="shrink-0">

                        @if ($driver->profile_photo_url)
                            <img
                                id="profile-photo-preview"
                                src="{{ $driver->profile_photo_url }}"
                                alt="Foto {{ $driver->name }}"
                                class="h-32 w-32 rounded-2xl border-4 border-white object-cover shadow"
                            >
                        @else
                            <div
                                id="profile-photo-placeholder"
                                class="flex h-32 w-32 items-center justify-center rounded-2xl border-4 border-white bg-indigo-100 text-4xl font-bold text-indigo-700 shadow"
                            >
                                {{ strtoupper(substr($driver->name, 0, 1)) }}
                            </div>

                            <img
                                id="profile-photo-preview"
                                src=""
                                alt="Preview foto driver"
                                class="hidden h-32 w-32 rounded-2xl border-4 border-white object-cover shadow"
                            >
                        @endif

                    </div>

                    <div class="flex-1">

                        <label
                            for="profile_photo"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Pilih Foto Baru
                        </label>

                        <input
                            id="profile_photo"
                            type="file"
                            name="profile_photo"
                            accept="image/jpeg,image/png,image/webp"
                            class="block w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"
                        >

                        @error('profile_photo')
                            <p class="mt-2 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($driver->profile_photo_path)
                            <p class="mt-2 text-xs text-gray-500">
                                Kosongkan jika foto lama tidak ingin diganti.
                            </p>
                        @endif

                    </div>

                </div>

            </section>

            {{-- Data Driver --}}
            <section>

                <h2 class="mb-4 text-base font-semibold text-gray-900">
                    Informasi Driver
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div>
                        <label
                            for="name"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Nama
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $driver->name) }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                        @error('name')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="email"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $driver->email) }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                        @error('email')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="phone"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Nomor Telepon
                        </label>

                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone', $driver->phone) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="license_number"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Nomor SIM
                        </label>

                        <input
                            id="license_number"
                            type="text"
                            name="license_number"
                            value="{{ old('license_number', $driver->license_number) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                        @error('license_number')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="license_expiry"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Masa Berlaku SIM
                        </label>

                        <input
                            id="license_expiry"
                            type="date"
                            name="license_expiry"
                            value="{{ old(
                                'license_expiry',
                                $driver->license_expiry
                                    ? \Carbon\Carbon::parse($driver->license_expiry)->format('Y-m-d')
                                    : ''
                            ) }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                        @error('license_expiry')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="driver_status"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Status
                        </label>

                        <select
                            id="driver_status"
                            name="driver_status"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >
                            <option
                                value="active"
                                @selected(
                                    old('driver_status', $driver->driver_status)
                                    === 'active'
                                )
                            >
                                Aktif
                            </option>

                            <option
                                value="inactive"
                                @selected(
                                    old('driver_status', $driver->driver_status)
                                    === 'inactive'
                                )
                            >
                                Nonaktif
                            </option>
                        </select>

                        @error('driver_status')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="address"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Alamat
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >{{ old('address', $driver->address) }}</textarea>

                        @error('address')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </section>

            {{-- Password --}}
            <section class="border-t border-gray-200 pt-6">

                <h2 class="text-base font-semibold text-gray-900">
                    Ubah Password
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Kosongkan jika password tidak ingin diubah.
                </p>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div>
                        <label
                            for="password"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Password Baru
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >
                    </div>

                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Konfirmasi Password
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >
                    </div>

                </div>

                @error('password')
                    <p class="mt-2 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </section>

            {{-- Action --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.drivers.show', $driver) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    document
        .getElementById('profile_photo')
        .addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const preview = document.getElementById(
                'profile-photo-preview'
            );

            const placeholder = document.getElementById(
                'profile-photo-placeholder'
            );

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');

            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        });
</script>

@endsection
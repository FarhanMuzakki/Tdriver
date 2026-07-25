@extends('layouts.admin')

@section('title', 'Tambah Kendaraan')

@section('content')

<div class="mx-auto max-w-4xl">

    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                Tambah Kendaraan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan data kendaraan baru ke dalam sistem.
            </p>
        </div>

        {{-- Error validasi --}}
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

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('admin.vehicles.store') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Plat Nomor --}}
                <div>
                    <label
                        for="plate_number"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Plat Nomor
                    </label>

                    <input
                        id="plate_number"
                        type="text"
                        name="plate_number"
                        value="{{ old('plate_number') }}"
                        placeholder="Contoh: B 1234 ABC"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 uppercase focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('plate_number')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Brand --}}
                <div>
                    <label
                        for="brand"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Brand
                    </label>

                    <input
                        id="brand"
                        type="text"
                        name="brand"
                        value="{{ old('brand') }}"
                        placeholder="Contoh: Toyota"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Model --}}
                <div>
                    <label
                        for="model"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Model
                    </label>

                    <input
                        id="model"
                        type="text"
                        name="model"
                        value="{{ old('model') }}"
                        placeholder="Contoh: Avanza"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('model')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tahun --}}
                <div>
                    <label
                        for="year"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Tahun
                    </label>

                    <input
                        id="year"
                        type="number"
                        name="year"
                        value="{{ old('year') }}"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        placeholder="Contoh: 2024"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('year')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Warna --}}
                <div>
                    <label
                        for="color"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Warna
                    </label>

                    <input
                        id="color"
                        type="text"
                        name="color"
                        value="{{ old('color') }}"
                        placeholder="Contoh: Hitam"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('color')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tipe Kendaraan --}}
                <div>
                    <label
                        for="type"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Tipe Kendaraan
                    </label>

                    <input
                        id="type"
                        type="text"
                        name="type"
                        value="{{ old('type') }}"
                        placeholder="Contoh: MPV, SUV, Sedan"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('type')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Fuel Type --}}
                <div>
                    <label
                        for="fuel_type"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Jenis Bahan Bakar
                    </label>

                    <select
                        id="fuel_type"
                        name="fuel_type"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                        <option value="">Pilih bahan bakar</option>

                        <option
                            value="gasoline"
                            @selected(old('fuel_type') === 'gasoline')
                        >
                            Bensin
                        </option>

                        <option
                            value="diesel"
                            @selected(old('fuel_type') === 'diesel')
                        >
                            Diesel
                        </option>

                        <option
                            value="electric"
                            @selected(old('fuel_type') === 'electric')
                        >
                            Listrik
                        </option>

                        <option
                            value="hybrid"
                            @selected(old('fuel_type') === 'hybrid')
                        >
                            Hybrid
                        </option>
                    </select>

                    @error('fuel_type')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Transmission --}}
                <div>
                    <label
                        for="transmission"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Transmisi
                    </label>

                    <select
                        id="transmission"
                        name="transmission"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                        <option value="">Pilih transmisi</option>

                        <option
                            value="manual"
                            @selected(old('transmission') === 'manual')
                        >
                            Manual
                        </option>

                        <option
                            value="automatic"
                            @selected(old('transmission') === 'automatic')
                        >
                            Automatic
                        </option>
                    </select>

                    @error('transmission')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label
                        for="status"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                        <option
                            value="available"
                            @selected(old('status', 'available') === 'available')
                        >
                            Available
                        </option>

                        <option
                            value="in_use"
                            @selected(old('status') === 'in_use')
                        >
                            In Use
                        </option>

                        <option
                            value="maintenance"
                            @selected(old('status') === 'maintenance')
                        >
                            Maintenance
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Service Date --}}
                <div>
                    <label
                        for="service_date"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Tanggal Servis Berikutnya
                    </label>

                    <input
                        id="service_date"
                        type="date"
                        name="service_date"
                        value="{{ old('service_date') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >

                    @error('service_date')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Driver Assignment --}}
                <div class="md:col-span-2">
                    <label
                        for="driver_id"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Driver Assignment
                    </label>

                    <select
                        id="driver_id"
                        name="driver_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                        <option value="">
                            Belum assign driver
                        </option>

                        @foreach ($drivers as $driver)
                            <option
                                value="{{ $driver->id }}"
                                @selected(old('driver_id') === $driver->id)
                            >
                                {{ $driver->name }} — {{ $driver->email }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-1 text-xs text-gray-500">
                        Jika driver dipilih, status kendaraan otomatis menjadi In Use.
                    </p>

                    @error('driver_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Foto Kendaraan --}}
                <div class="md:col-span-2">
                    <label
                        for="image"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Foto Kendaraan
                    </label>

                    <div
                        id="image-preview-wrapper"
                        class="mb-4 hidden overflow-hidden rounded-2xl border border-gray-200 bg-gray-100"
                    >
                        <img
                            id="image-preview"
                            src=""
                            alt="Preview foto kendaraan"
                            class="h-64 w-full object-cover"
                        >
                    </div>

                    <input
                        id="image"
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm"
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        Format JPG, PNG, atau WebP. Maksimal 2 MB.
                    </p>

                    @error('image')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row">

                <a
                    href="{{ route('admin.vehicles.index') }}"
                    class="rounded-lg border border-gray-300 px-6 py-3 text-center font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white transition hover:bg-indigo-700"
                >
                    Simpan Kendaraan
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const driverSelect = document.getElementById('driver_id');
        const statusSelect = document.getElementById('status');
        const imageInput = document.getElementById('image');
        const previewWrapper = document.getElementById('image-preview-wrapper');
        const previewImage = document.getElementById('image-preview');

        if (driverSelect && statusSelect) {
            driverSelect.addEventListener('change', function () {
                if (this.value !== '') {
                    statusSelect.value = 'in_use';
                } else if (statusSelect.value === 'in_use') {
                    statusSelect.value = 'available';
                }
            });
        }

        if (imageInput && previewWrapper && previewImage) {
            imageInput.addEventListener('change', function () {
                const file = this.files[0];

                if (!file) {
                    previewWrapper.classList.add('hidden');
                    previewImage.src = '';
                    return;
                }

                previewImage.src = URL.createObjectURL(file);
                previewWrapper.classList.remove('hidden');
            });
        }
    });
</script>

@endsection
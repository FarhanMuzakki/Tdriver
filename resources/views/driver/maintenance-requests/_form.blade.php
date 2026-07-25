@php
    $isEdit = isset($maintenanceRequest);
@endphp

<div class="space-y-5">

    {{-- Kendaraan --}}
    <div>
        <label
            for="vehicle_id"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Kendaraan
            <span class="text-red-500">*</span>
        </label>

        <select
            id="vehicle_id"
            name="vehicle_id"
            required
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">
                Pilih kendaraan
            </option>

            @foreach ($assignments as $assignment)
                <option
                    value="{{ $assignment->vehicle_id }}"
                    @selected(
                        old(
                            'vehicle_id',
                            $maintenanceRequest->vehicle_id ?? ''
                        ) == $assignment->vehicle_id
                    )
                >
                    {{ $assignment->vehicle?->plate_number ?? '-' }}
                    —
                    {{ $assignment->vehicle?->brand ?? '' }}
                    {{ $assignment->vehicle?->model ?? '' }}
                </option>
            @endforeach
        </select>

        @error('vehicle_id')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror

        @if ($assignments->isEmpty())
            <p class="mt-2 text-xs text-amber-600">
                Anda belum memiliki assignment kendaraan aktif.
            </p>
        @endif
    </div>

    {{-- Jenis Masalah --}}
    <div>
        <label
            for="issue_type"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Jenis Masalah
            <span class="text-red-500">*</span>
        </label>

        <select
            id="issue_type"
            name="issue_type"
            required
            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">
                Pilih jenis masalah
            </option>

            @php
                $issueTypes = [
                    'Mesin' => 'Mesin',
                    'Rem' => 'Rem',
                    'Ban' => 'Ban',
                    'Kelistrikan' => 'Kelistrikan',
                    'AC' => 'AC',
                    'Transmisi' => 'Transmisi',
                    'Suspensi' => 'Suspensi',
                    'Body Kendaraan' => 'Body Kendaraan',
                    'Servis Berkala' => 'Servis Berkala',
                    'Lainnya' => 'Lainnya',
                ];
            @endphp

            @foreach ($issueTypes as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(
                        old(
                            'issue_type',
                            $maintenanceRequest->issue_type ?? ''
                        ) === $value
                    )
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('issue_type')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Prioritas --}}
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Tingkat Prioritas
            <span class="text-red-500">*</span>
        </label>

        <div class="grid grid-cols-2 gap-3">

            @php
                $priorities = [
                    'low' => [
                        'label' => 'Rendah',
                        'description' => 'Tidak mendesak',
                    ],
                    'medium' => [
                        'label' => 'Sedang',
                        'description' => 'Perlu diperiksa',
                    ],
                    'high' => [
                        'label' => 'Tinggi',
                        'description' => 'Mengganggu penggunaan',
                    ],
                    'urgent' => [
                        'label' => 'Darurat',
                        'description' => 'Berbahaya digunakan',
                    ],
                ];

                $selectedPriority = old(
                    'priority',
                    $maintenanceRequest->priority ?? 'medium'
                );
            @endphp

            @foreach ($priorities as $value => $priority)

                <label class="cursor-pointer">

                    <input
                        type="radio"
                        name="priority"
                        value="{{ $value }}"
                        class="peer sr-only"
                        @checked($selectedPriority === $value)
                    >

                    <div class="rounded-2xl border border-slate-200 bg-white p-3 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-1 peer-checked:ring-indigo-500">

                        <p class="text-sm font-bold text-slate-900">
                            {{ $priority['label'] }}
                        </p>

                        <p class="mt-1 text-[11px] leading-4 text-slate-500">
                            {{ $priority['description'] }}
                        </p>

                    </div>

                </label>

            @endforeach

        </div>

        @error('priority')
            <p class="mt-2 text-xs font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label
            for="description"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            Deskripsi Masalah
            <span class="text-red-500">*</span>
        </label>

        <textarea
            id="description"
            name="description"
            rows="6"
            maxlength="3000"
            required
            placeholder="Jelaskan gejala, suara, kondisi, atau masalah kendaraan secara rinci..."
            class="w-full resize-none rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm leading-6 text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old(
            'description',
            $maintenanceRequest->description ?? ''
        ) }}</textarea>

        <div class="mt-2 flex items-center justify-between">

            @error('description')
                <p class="text-xs font-medium text-red-600">
                    {{ $message }}
                </p>
            @else
                <p class="text-xs text-slate-400">
                    Berikan informasi sejelas mungkin.
                </p>
            @enderror

            <p class="text-xs text-slate-400">
                Maks. 3000 karakter
            </p>

        </div>
    </div>

    {{-- Informasi --}}
    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">

        <div class="flex items-start gap-3">

            <div class="mt-0.5 shrink-0 text-blue-600">

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
                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M12 8.25h.008v.008H12V8.25zm9 3.75a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

            </div>

            <div>
                <p class="text-sm font-semibold text-blue-800">
                    Informasi Pengajuan
                </p>

                <p class="mt-1 text-xs leading-5 text-blue-700">
                    Pengajuan akan dikirim ke admin dan berstatus menunggu sampai diperiksa.
                </p>
            </div>

        </div>

    </div>

    {{-- Action --}}
    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row">

        <a
            href="{{ route('driver.maintenance-requests.index') }}"
            class="flex flex-1 items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700"
        >
            Batal
        </a>

        <button
            type="submit"
            @disabled($assignments->isEmpty())
            class="flex flex-1 items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition active:scale-[0.98] disabled:cursor-not-allowed disabled:bg-slate-300"
        >
            {{ $isEdit ? 'Simpan Perubahan' : 'Kirim Pengajuan' }}
        </button>

    </div>

</div>
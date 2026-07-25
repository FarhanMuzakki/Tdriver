@extends('layouts.driver')

@section('title', 'Pengajuan Maintenance')

@section('content')

<div class="space-y-5">

    <div class="flex items-start justify-between gap-4">

        <div>
            <h1 class="text-xl font-bold text-slate-900">
                Pengajuan Maintenance
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Riwayat laporan kerusakan kendaraan.
            </p>
        </div>

        <a
            href="{{ route('driver.maintenance-requests.create') }}"
            class="shrink-0 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white"
        >
            Ajukan
        </a>

    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-3">

        @forelse ($maintenanceRequests as $requestItem)

            @php
                $statusClasses = match ($requestItem->status) {
                    'approved' => 'bg-blue-50 text-blue-700',
                    'rejected' => 'bg-red-50 text-red-700',
                    'completed' => 'bg-emerald-50 text-emerald-700',
                    default => 'bg-amber-50 text-amber-700',
                };

                $statusLabel = match ($requestItem->status) {
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                    default => 'Menunggu',
                };
            @endphp

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">
                        <p class="text-xs text-slate-500">
                            {{ $requestItem->requested_at?->format('d M Y, H:i') ?? '-' }}
                        </p>

                        <h2 class="mt-1 font-bold text-slate-900">
                            {{ $requestItem->vehicle?->plate_number ?? '-' }}
                        </h2>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            {{ $requestItem->issue_type }}
                        </p>
                    </div>

                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">
                    {{ $requestItem->description }}
                </p>

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">

                    <span class="text-xs font-medium text-slate-500">
                        Prioritas:
                        <span class="font-semibold text-slate-800">
                            {{ ucfirst($requestItem->priority) }}
                        </span>
                    </span>

                    @if ($requestItem->status === 'pending')
                        <div class="flex gap-2">

                            <a
                                href="{{ route('driver.maintenance-requests.edit', $requestItem) }}"
                                class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('driver.maintenance-requests.destroy', $requestItem) }}"
                                onsubmit="return confirm('Hapus pengajuan ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700"
                                >
                                    Hapus
                                </button>
                            </form>

                        </div>
                    @endif

                </div>

                @if ($requestItem->admin_notes)
                    <div class="mt-3 rounded-xl bg-slate-50 p-3">
                        <p class="text-xs font-semibold text-slate-500">
                            Catatan admin
                        </p>

                        <p class="mt-1 text-sm text-slate-700">
                            {{ $requestItem->admin_notes }}
                        </p>
                    </div>
                @endif

            </article>

        @empty

            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">

                <h2 class="font-semibold text-slate-900">
                    Belum ada pengajuan
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Laporkan masalah kendaraan melalui tombol Ajukan.
                </p>

            </div>

        @endforelse

    </div>

    @if ($maintenanceRequests->hasPages())
        {{ $maintenanceRequests->links() }}
    @endif

</div>

@endsection
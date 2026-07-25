@extends('layouts.admin')

@section('title', 'Assignment Kendaraan')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Assignment Kendaraan
            </h1>

            <p class="mt-1 text-gray-500">
                Kelola penugasan kendaraan kepada driver.
            </p>
        </div>

        <a
            href="{{ route('admin.assignments.create') }}"
            class="rounded-xl bg-indigo-600 px-5 py-3 text-center text-white shadow hover:bg-indigo-700"
        >
            + Assign Kendaraan
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-300 bg-green-100 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-300 bg-red-100 p-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl bg-white p-4 shadow">
        <form method="GET" class="flex flex-col gap-4 md:flex-row">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari driver atau kendaraan..."
                class="flex-1 rounded-lg border px-4 py-3"
            >

            <select
                name="status"
                class="rounded-lg border px-4 py-3"
            >
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>
                    Active
                </option>
                <option value="finished" @selected(request('status') === 'finished')>
                    Finished
                </option>
            </select>

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-6 py-3 text-white"
            >
                Cari
            </button>

            <a
                href="{{ route('admin.assignments.index') }}"
                class="rounded-lg border px-6 py-3 text-center"
            >
                Reset
            </a>

        </form>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow">

        <table class="w-full min-w-[950px]">

            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left">Driver</th>
                    <th class="px-6 py-4 text-left">Kendaraan</th>
                    <th class="px-6 py-4 text-left">Ditugaskan</th>
                    <th class="px-6 py-4 text-left">Dikembalikan</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($assignments as $assignment)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">
                                {{ $assignment->driver->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $assignment->driver->email }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-medium">
                                {{ $assignment->vehicle->plate_number }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $assignment->vehicle->type }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            {{ $assignment->assigned_at?->format('d M Y H:i') ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $assignment->returned_at?->format('d M Y H:i') ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($assignment->status === 'active')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                    Finished
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">

                                @if ($assignment->status === 'active')
                                    <form
                                        method="POST"
                                        action="{{ route('admin.assignments.finish', $assignment) }}"
                                        onsubmit="return confirm('Tandai kendaraan sudah dikembalikan?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-green-100 px-3 py-2 text-sm text-green-700 hover:bg-green-200"
                                        >
                                            Selesai
                                        </button>
                                    </form>
                                @endif

                                <form
                                    method="POST"
                                    action="{{ route('admin.assignments.destroy', $assignment) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus assignment ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200"
                                    >
                                        🗑️
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Belum ada assignment kendaraan.
                        </td>
                    </tr>

                @endforelse
            </tbody>

        </table>

    </div>

    {{ $assignments->links() }}

</div>

@endsection
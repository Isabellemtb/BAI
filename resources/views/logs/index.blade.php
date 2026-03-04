@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold">Action Logs</h1>

        {{-- Formulaire de filtres --}}
        <form method="GET" action="{{ route('logs.index') }}" class="flex flex-wrap items-end gap-4 bg-gray-50 p-4 rounded-lg">

            {{-- Filtre par action --}}
            <div>
                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select id="action" name="action" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">Toutes les actions</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" @selected($a === $action)>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre date début --}}
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}"
                       class="border-gray-300 rounded-md shadow-sm text-sm">
            </div>

            {{-- Filtre date fin --}}
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}"
                       class="border-gray-300 rounded-md shadow-sm text-sm">
            </div>

            {{-- Boutons --}}
            <div class="flex gap-2">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                    Filtrer
                </button>
                <a href="{{ route('logs.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
            </div>
        </form>

        {{-- Tableau des logs --}}
        <table class="w-full text-sm border-collapse">
            <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-1">Date</th>
                <th class="border px-2 py-1">User</th>
                <th class="border px-2 py-1">Action</th>
                <th class="border px-2 py-1">Idea</th>
                <th class="border px-2 py-1">Comment</th>
                <th class="border px-2 py-1">IP</th>
            </tr>
            </thead>

            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="border px-2 py-1">{{ $log->created_at }}</td>
                    <td class="border px-2 py-1">{{ $log->user?->name ?? 'Guest' }}</td>
                    <td class="border px-2 py-1">{{ $log->action }}</td>
                    <td class="border px-2 py-1">{{ $log->idea_id ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ $log->comment_id ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ $log->ip_address ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border px-2 py-4 text-center text-gray-500">
                        Aucun log trouvé.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $logs->links() }}

    </div>

@endsection

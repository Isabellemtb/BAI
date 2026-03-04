@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto space-y-8">

        {{-- Status message --}}
        @if(session('status'))
            <div class="p-2 bg-green-100 border rounded">
                {{ session('status') }}
            </div>
        @endif

        {{-- Idea card --}}
        <div class="p-4 bg-white border rounded">

            <h1 class="text-2xl font-bold">{{ $idea->title }}</h1>

            <p class="text-gray-600 text-sm">
                By {{ $idea->user?->name ?? 'Unknown' }}
                • {{ $idea->created_at->toDayDateTimeString() }}
            </p>

            <p class="text-sm text-gray-600">
                Application: {{ $idea->application ?? 'N/A' }}
            </p>

            <div class="mt-4 text-sm">
                {!! nl2br(e($idea->description)) !!}
            </div>

            {{-- Edit / Delete --}}
            <div class="mt-4 flex space-x-3">
                <a href="{{ route('ideas.edit', $idea) }}"
                   class="text-blue-600">Edit</a>

                <form action="{{ route('ideas.destroy', $idea) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Delete</button>
                </form>
            </div>

        </div>

        {{-- Add a comment --}}
        <div class="p-4 bg-white border rounded">
            <h2 class="text-xl font-semibold mb-2">Add a Comment</h2>

            <form action="{{ route('comments.store', $idea) }}" method="POST">
                @csrf

                <textarea name="description"
                          rows="3"
                          class="w-full border rounded p-2"></textarea>

                <button class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">
                    Post Comment
                </button>
            </form>
        </div>

        {{-- Comments --}}
        <div class="p-4 bg-white border rounded">
            <h2 class="text-xl font-semibold mb-2">Comments</h2>

            @forelse($idea->comments as $comment)

                <div class="border-b py-2" x-data="{ editing: false }">

                    <p class="text-sm text-gray-600">
                        {{ $comment->user?->name ?? 'Unknown' }}
                        • {{ $comment->created_at->diffForHumans() }}
                    </p>

                    {{-- Affichage normal --}}
                    <div x-show="!editing">
                        <div class="mt-1 text-sm">
                            {!! nl2br(e($comment->description)) !!}
                        </div>

                        <div class="flex space-x-2 mt-1">
                            @can('update', $comment)
                                <button @click="editing = true" class="text-xs text-blue-600">
                                    Edit
                                </button>
                            @endcan

                            <form action="{{ route('comments.destroy', [$idea, $comment]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Formulaire d'édition --}}
                    <form x-show="editing" x-cloak
                          action="{{ route('comments.update', [$idea, $comment]) }}"
                          method="POST" class="mt-2">
                        @csrf
                        @method('PUT')

                        <textarea name="description" rows="3"
                                  class="w-full border rounded p-2 text-sm">{{ $comment->description }}</textarea>

                        <div class="flex space-x-2 mt-1">
                            <button type="submit"
                                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded">
                                Save
                            </button>
                            <button type="button" @click="editing = false"
                                    class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded">
                                Cancel
                            </button>
                        </div>
                    </form>

                </div>

            @empty
                <p>No comments yet.</p>
            @endforelse

        </div>

    </div>

@endsection

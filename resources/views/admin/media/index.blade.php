@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Media Library</h1>
            <a href="{{ route('admin.media.create') }}" class="btn-primary">Upload Media</a>
        </div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                @if ($media->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($media as $medium)
                            <div class="border border-border rounded-lg overflow-hidden bg-background">
                                <a href="{{ route('admin.media.edit', $medium) }}">
                                    <img src="{{ asset('storage/' . $medium->path) }}" alt="{{ $medium->alt_text }}" class="w-full h-32 object-cover">
                                    <div class="p-2">
                                        <p class="text-sm truncate text-text-secondary">{{ $medium->name }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $media->links() }}
                    </div>
                @else
                    <p>No media items found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

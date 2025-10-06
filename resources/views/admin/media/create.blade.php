@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 border-b border-border">
                <h1 class="text-2xl font-semibold text-text-primary mb-6">Upload Media</h1>

                {{-- Drag and Drop Upload Zone --}}
                <div id="media-dropzone" class="border-2 border-dashed border-border rounded-lg p-12 text-center transition-colors hover:border-accent cursor-pointer bg-background">
                    <div class="space-y-4">
                        <div class="flex justify-center">
                            <svg class="w-16 h-16 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary">Drop files here to upload</h3>
                            <p class="text-sm text-text-secondary mt-1">or click to browse</p>
                            <p class="text-xs text-text-secondary mt-2">Supports: JPG, PNG, GIF, WebP, SVG • Max 10MB per file</p>
                            <p class="text-xs text-accent mt-1">💡 Pro tip: You can also paste images from clipboard!</p>
                        </div>
                        <input type="file" name="file" multiple accept="image/*" class="hidden">
                    </div>
                </div>

                {{-- Upload Preview Container (dynamically created) --}}
                <div id="upload-preview" class="hidden"></div>

                {{-- Traditional form fallback --}}
                <div class="mt-8 pt-8 border-t border-border">
                    <p class="text-sm text-text-secondary mb-4">Or use traditional upload:</p>
                    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center gap-4">
                            <input type="file" name="file" id="file" class="text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-accent file:text-white hover:file:bg-opacity-90">
                            <button type="submit" class="btn-primary">Upload</button>
                            <a href="{{ route('admin.media.index') }}" class="text-sm text-text-secondary hover:text-text-primary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

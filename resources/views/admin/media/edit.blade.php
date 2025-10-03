@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 border-b border-border">
                <h1 class="text-2xl font-semibold text-text-primary mb-6">Edit Media</h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <img src="{{ asset('storage/' . $medium->path) }}" alt="{{ $medium->alt_text }}" class="w-full rounded-lg border border-border">
                    </div>
                    <div class="md:col-span-2">
                        <form action="{{ route('admin.media.update', $medium) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <div class="bg-surface p-6 rounded-lg border border-border">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-text-secondary">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $medium->name) }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        </div>
                                        <div>
                                            <label for="alt_text" class="block text-sm font-medium text-text-secondary">Alt Text</label>
                                            <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text', $medium->alt_text) }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        </div>
                                        <div>
                                            <label for="caption" class="block text-sm font-medium text-text-secondary">Caption</label>
                                            <textarea name="caption" id="caption" rows="3" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('caption', $medium->caption) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end">
                                    <a href="{{ route('admin.media.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
                                    <button type="submit" class="btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                        <div class="mt-6 border-t border-border pt-6">
                             <form action="{{ route('admin.media.destroy', $medium) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="link-delete">Delete Media</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

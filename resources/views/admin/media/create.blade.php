@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 border-b border-border">
                <h1 class="text-2xl font-semibold text-text-primary mb-6">Upload Media</h1>

                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div class="bg-surface p-6 rounded-lg border border-border">
                            <div class="space-y-4">
                                <div>
                                    <label for="file" class="block text-sm font-medium text-text-secondary">File</label>
                                    <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-opacity-90">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.media.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
                            <button type="submit" class="btn-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

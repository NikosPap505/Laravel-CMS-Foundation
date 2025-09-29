@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Categories</h1>
            <a href="{{ route('admin.categories.create') }}" class="btn-primary">Add Category</a>
        </div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Slug</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="link-edit">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="link-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
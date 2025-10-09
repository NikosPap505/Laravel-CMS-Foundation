@extends('layouts.admin')

@section('title', 'Pages Management')
@section('subtitle', 'Create and manage static pages')

@section('quick-actions')
<a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
    Add New Page
</a>
@endsection

@section('content')
        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-center w-16 text-xs font-medium text-text-secondary uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Slug</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-list">
                        @foreach ($pages as $page)
                        <tr data-id="{{ $page->id }}">
                            <td class="px-6 py-4 cursor-grab text-center drag-handle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $page->title }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $page->slug }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="link-edit">Edit</a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
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

@push('scripts')
<script>
    const sortableList = document.getElementById('sortable-list');
    new Sortable(sortableList, {
        animation: 150,
        handle: '.drag-handle', // Only the handle can be used to drag
        onEnd: function (evt) {
            const newOrder = Array.from(evt.to.children).map(row => row.getAttribute('data-id'));
            fetch('{{ route("admin.pages.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order: newOrder })
            })
            .then(response => response.json())
            .then(data => console.log('Order saved:', data))
            .catch(error => console.error('Error:', error));
        }
    });
</script>
@endpush
@endsection
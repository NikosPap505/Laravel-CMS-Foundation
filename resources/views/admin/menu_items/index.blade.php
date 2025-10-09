@extends('layouts.admin')

@section('title', 'Menu Items')
@section('subtitle', 'Manage your site navigation menu')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Menu Items</h1>
            <a href="{{ route('admin.menu-items.create') }}" class="btn-primary">Add Menu Item</a>
        </div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-center w-16 text-xs font-medium text-text-secondary uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Link</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-menu">
                        {{-- THE FIX IS HERE: Changed $posts to $menuItems --}}
                        @foreach ($menuItems as $item)
                        <tr data-id="{{ $item->id }}">
                            <td class="px-6 py-4 cursor-grab text-center drag-handle">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->link }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.menu-items.edit', $item) }}" class="link-edit">Edit</a>
                                <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
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
    const sortableMenu = document.getElementById('sortable-menu');
    if (sortableMenu) {
        new Sortable(sortableMenu, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function (evt) {
                const newOrder = Array.from(evt.to.children).map(row => row.getAttribute('data-id'));
                fetch('{{ route("admin.menu-items.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order: newOrder })
                });
            }
        });
    }
</script>
@endpush
@endsection
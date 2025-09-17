@extends('layouts.app')

@section('content')
<h1>Pages</h1>
<a href="{{ route('admin.pages.create') }}">Add New Page</a>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Slug</th>
        <th>Actions</th>
    </tr>
    @foreach($pages as $page)
    <tr>
        <td>{{ $page->title }}</td>
        <td>{{ $page->slug }}</td>
        <td>
            <a href="{{ route('admin.pages.edit', $page) }}">Edit</a>
            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<div class="pagination">
    {{ $pages->links('pagination::bootstrap-4') }}
</div>
@endsection

@extends('layouts.app')

@section('content')
<h1>Edit Page</h1>

<a href="{{ route('admin.pages.index') }}">Back to Pages</a>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}">
    </div>
    <div class="mb-3">
        <label>Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
    </div>
    <div class="mb-3">
        <label>Content</label>
        <textarea name="content" class="form-control">{{ old('content', $page->content) }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection

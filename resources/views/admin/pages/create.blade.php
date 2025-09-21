@extends('layouts.app')

@section('content')
<h1>Create Page</h1>

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

<form action="{{ route('admin.pages.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
    </div>
    <div class="mb-3">
        <label>Slug</label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
    </div>
    <div class="mb-3">
        <label>Content</label>
        <textarea name="content" class="form-control">{{ old('content') }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection

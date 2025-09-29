@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Create New Post</h1>
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to Posts</a>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Please correct the errors below:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                        <input type="file" name="featured_image" id="featured_image" class="mt-1 block w-full">
                    </div>
                    <div class="mb-4">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('excerpt') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="body-editor" class="block text-sm font-medium text-gray-700">Body</label>
                        <textarea name="body" id="body-editor" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('body') }}</textarea>
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
  tinymce.init({
    selector: 'textarea#body-editor', // Στοχεύουμε το textarea με id="body-editor"
    plugins: 'code table lists image link',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link'
  });
</script>
@endpush
@endsection
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $item->title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>

<div class="mb-4">
    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
    <input type="text" name="slug" id="slug" value="{{ old('slug', $item->slug ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>

<div class="mb-4">
    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
    <textarea name="excerpt" id="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label for="content-editor" class="block text-sm font-medium text-gray-700">Body</label>
    <textarea name="body" id="content-editor" rows="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('body', $item->body ?? '') }}</textarea>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
    <button type="submit" class="btn-primary">Save</button>
</div>

@push('scripts')
<script>
  tinymce.init({
    selector: 'textarea#content-editor',
    plugins: 'code table lists image link',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link'
  });
</script>
@endpush
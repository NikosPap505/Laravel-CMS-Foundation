<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $post->title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>
<div class="mb-4">
    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
</div>
<div class="mb-4">
    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-4">
    <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
    <input type="file" name="featured_image" id="featured_image" class="mt-1 block w-full">
    @if(isset($post) && $post->featured_image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-32 h-auto">
        </div>
    @endif
</div>
<div class="mb-4">
    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
    <textarea name="excerpt" id="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</div>
<div class="mb-4">
    <label for="body-editor" class="block text-sm font-medium text-gray-700">Body</label>
    <textarea name="body" id="body-editor" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('body', $post->body ?? '') }}</textarea>
</div>
<div class="flex items-center justify-end mt-6">
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
        {{ isset($post) ? 'Update Post' : 'Create Post' }}
    </button>
</div>

@push('scripts')
<script>
  tinymce.init({
    selector: 'textarea#body-editor',
    plugins: 'code table lists image link',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link',
    // Ενεργοποίηση του file picker για εικόνες
    file_picker_types: 'image',
    file_picker_callback: (cb, value, meta) => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.addEventListener('load', () => cb(reader.result, { title: file.name }));
            reader.readAsDataURL(file);
        });

        input.click();
    }
  });
</script>
@endpush
@if ($errors->any())
    <div class="bg-error/20 border border-error text-error px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-text-secondary">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $item->title ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" required>
</div>

<div class="mb-4">
    <label for="slug" class="block text-sm font-medium text-text-secondary">Slug</label>
    <input type="text" name="slug" id="slug" value="{{ old('slug', $item->slug ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" required>
</div>

<div class="mt-6 pt-6 border-t border-border">
    <h3 class="text-lg font-medium text-text-primary">SEO</h3>
    <div class="mt-4">
        <label for="meta_title" class="block text-sm font-medium text-text-secondary">Meta Title</label>
        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $item->meta_title ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
    </div>
    <div class="mt-4">
        <label for="meta_description" class="block text-sm font-medium text-text-secondary">Meta Description</label>
        <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
    </div>
</div>

<div class="mb-4">
    <label for="content-editor" class="block text-sm font-medium text-text-secondary">Content</label>
    <textarea name="content" id="content-editor" rows="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content', $item->content ?? '') }}</textarea>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.pages.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
    <button type="submit" class="btn-primary">Save Page</button>
</div>

@push('scripts')
<script>
    tinymce.init({
        selector: 'textarea#content-editor',
        plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        height: 400,
        branding: true,

        // ✅ START: IMAGE UPLOAD CONFIGURATION
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        images_upload_url: '{{ route("admin.images.upload") }}', // Our new route
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.images.upload") }}');

            // Add the CSRF token to the request header
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            xhr.setRequestHeader('X-CSRF-TOKEN', token);

            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = () => {
                if (xhr.status === 422) {
                    reject('HTTP Error: ' + xhr.status + ', Ελέγξτε ότι το αρχείο είναι εικόνα και κάτω από 2MB.');
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = () => {
              reject('Image upload failed due to a network error. Code: ' + xhr.status);
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }),
        // ✅ END: IMAGE UPLOAD CONFIGURATION
    });
</script>
@endpush
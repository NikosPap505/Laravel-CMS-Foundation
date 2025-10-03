@if ($errors->any())
    <div class="bg-error/20 border border-error text-error px-4 py-3 rounded-md relative mb-4">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="space-y-6">
    <div class="bg-surface p-6 rounded-lg border border-border">
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-text-secondary">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $item->title ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent text-lg font-semibold" required>
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-text-secondary">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $item->slug ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" required>
            </div>
        </div>
    </div>

    <div class="bg-surface p-6 rounded-lg border border-border">
        <h3 class="text-lg font-medium text-text-primary mb-4 border-b border-border pb-2">Publishing</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-text-secondary">Status</label>
                <select name="status" id="status" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" required>
                    <option value="draft" @selected(old('status', $item->status ?? 'draft') == 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $item->status ?? '') == 'published')>Published</option>
                    <option value="scheduled" @selected(old('status', $item->status ?? '') == 'scheduled')>Scheduled</option>
                </select>
            </div>
            <div id="published_at_container" class="{{ old('status', $item->status ?? 'draft') == 'scheduled' ? '' : 'hidden' }}">
                <label for="published_at" class="block text-sm font-medium text-text-secondary">Publish Date</label>
                <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', isset($item->published_at) ? $item->published_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" style="color-scheme: dark;">
            </div>
        </div>
    </div>

    <div class="bg-surface p-6 rounded-lg border border-border">
         <h3 class="text-lg font-medium text-text-primary mb-4 border-b border-border pb-2">Details</h3>
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="category_id" class="block text-sm font-medium text-text-secondary">Category</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="featured_image" class="block text-sm font-medium text-text-secondary">Featured Image</label>
                <div class="mt-1 flex items-center">
                    <input type="text" name="featured_image_url" id="featured_image_url" class="flex-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" readonly>
                    <button type="button" id="browse-media" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md">Browse</button>
                </div>
                <input type="hidden" name="featured_image" id="featured_image">
            </div>
        </div>
    </div>

    <div id="media-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Media Library
                            </h3>
                            <div id="media-grid" class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                <!-- Media items will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="close-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    <div class="bg-surface p-6 rounded-lg border border-border">
        <h3 class="text-lg font-medium text-text-primary mb-4 border-b border-border pb-2">Excerpt & SEO</h3>
        <div class="space-y-4">
            <div>
                <label for="excerpt" class="block text-sm font-medium text-text-secondary">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="4" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" required>{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
            </div>
            <div>
                <label for="meta_title" class="block text-sm font-medium text-text-secondary">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $item->meta_title ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
            </div>
            <div>
                <label for="meta_description" class="block text-sm font-medium text-text-secondary">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-surface rounded-lg border border-border">
        <div class="p-2">
            <label for="content-editor" class="block text-sm font-medium text-text-secondary p-4">Body</label>
            <textarea name="body" id="content-editor" rows="25">{{ old('body', $item->body ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- Form Actions --}}
<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.posts.index') }}" class="text-sm text-text-secondary hover:text-text-primary mr-4">Cancel</a>
    <button type="submit" class="btn-primary">Save Post</button>
</div>

@push('scripts')
<script>
    // TinyMCE Initialization
    tinymce.init({
        selector: 'textarea#content-editor',
        plugins: 'code table lists image link media',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link | media',
        skin: 'oxide-dark',
        content_css: 'dark'
    });
  
    // Script to show/hide the publish date field
    const statusSelect = document.getElementById('status');
    const publishedAtContainer = document.getElementById('published_at_container');
    
    if (statusSelect && publishedAtContainer) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'scheduled') {
                publishedAtContainer.classList.remove('hidden');
            } else {
                publishedAtContainer.classList.add('hidden');
            }
        });
    }

    // Script for automatic slug generation
    if (document.getElementById('title') && document.getElementById('slug')) {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('keyup', function() {
            const slug = this.value.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\u00BF-\u1FFF\u2C00-\uD7FF\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
            
            slugInput.value = slug;
        });
    }

    // Media Library Modal
    const browseButton = document.getElementById('browse-media');
    const closeModalButton = document.getElementById('close-modal');
    const modal = document.getElementById('media-modal');
    const mediaGrid = document.getElementById('media-grid');
    const featuredImageUrl = document.getElementById('featured_image_url');
    const featuredImageInput = document.getElementById('featured_image');

    if (browseButton) {
        browseButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
            fetch('{{ route("admin.api.media.index") }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(media => {
                    mediaGrid.innerHTML = '';
                    media.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('cursor-pointer', 'border', 'rounded-lg', 'overflow-hidden');
                        div.dataset.id = item.id;
                        div.dataset.url = item.url;
                        div.innerHTML = `<img src="${item.url}" alt="${item.alt_text}" class="w-full h-32 object-cover">`;
                        mediaGrid.appendChild(div);
                    });
                });
        });
    }

    if (closeModalButton) {
        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

    if (mediaGrid) {
        mediaGrid.addEventListener('click', (e) => {
            if (e.target.closest('div[data-id]')) {
                const selected = e.target.closest('div[data-id]');
                featuredImageUrl.value = selected.dataset.url;
                featuredImageInput.value = selected.dataset.id;
                modal.classList.add('hidden');
            }
        });
    }
</script>
@endpush
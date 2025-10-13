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
                <div class="relative">
                    <input type="text" name="title" id="title" value="{{ old('title', $item->title ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent text-lg font-semibold pr-16" required>
                    <button type="button" onclick="generateAITitle()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-purple-500 to-blue-500 text-white px-3 py-2 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-1 text-sm font-medium" title="🤖 AI Title Suggestions">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        AI
                    </button>
                </div>
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
                <label for="tags" class="block text-sm font-medium text-text-secondary">Tags</label>
                <div class="relative">
                    <input type="text" name="tags" id="tags" value="{{ old('tags', isset($item->tags) ? $item->tags->pluck('name')->join(', ') : '') }}" placeholder="Enter tags separated by commas" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent pr-16">
                    <button type="button" onclick="generateAITags()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-yellow-500 to-amber-500 text-white px-3 py-2 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-1 text-sm font-medium" title="🤖 AI Tags Generator">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        AI
                    </button>
                </div>
                <p class="mt-1 text-xs text-text-secondary">Separate multiple tags with commas</p>
            </div>
            <div>
                <label for="featured_image_id" class="block text-sm font-medium text-text-secondary">Featured Image</label>
                <div class="mt-1 flex items-center">
                    <input type="text" name="featured_image_url" id="featured_image_url" class="flex-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent" readonly>
                    <button type="button" id="browse-media" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md">Browse</button>
                </div>
                <input type="hidden" name="featured_image_id" id="featured_image_id">
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
                <div class="relative">
                    <textarea name="excerpt" id="excerpt" rows="4" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent pr-16" required>{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
                    <button type="button" onclick="generateAIExcerpt()" class="absolute right-2 top-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-3 py-2 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-1 text-sm font-medium" title="🤖 AI Excerpt">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        AI
                    </button>
                </div>
            </div>
            <div>
                <label for="meta_title" class="block text-sm font-medium text-text-secondary">Meta Title</label>
                <div class="relative">
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $item->meta_title ?? '') }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent pr-16">
                    <button type="button" onclick="generateAIMetaTitle()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-green-500 to-teal-500 text-white px-3 py-2 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-1 text-sm font-medium" title="🤖 AI Meta Title">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        AI
                    </button>
                </div>
            </div>
            <div>
                <label for="meta_description" class="block text-sm font-medium text-text-secondary">Meta Description</label>
                <div class="relative">
                    <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent pr-16">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
                    <button type="button" onclick="generateAIMetaDescription()" class="absolute right-2 top-3 bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-2 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center gap-1 text-sm font-medium" title="🤖 AI Meta Description">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        AI
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-surface rounded-lg border border-border relative">
        <div class="p-2">
            <label for="content-editor" class="block text-sm font-medium text-text-secondary p-4">Body</label>
            <textarea name="body" id="content-editor" rows="25">{{ old('body', $item->body ?? '') }}</textarea>
        </div>
        <!-- Floating AI Assistant Button -->
        <div class="absolute top-4 right-4 z-10">
            <button type="button" onclick="openAIAssistant()" class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-white px-4 py-3 rounded-xl shadow-2xl hover:shadow-3xl transition-all duration-300 hover:scale-110 flex items-center gap-2 font-bold text-sm animate-pulse" title="🤖 AI Content Assistant">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                AI Assistant
            </button>
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
    // TinyMCE Initialization with Basic Configuration
    tinymce.init({
        selector: 'textarea#content-editor',
        plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        height: 600,
        branding: true,
        promotion: false,
        statusbar: true,
        resize: true,
        elementpath: true,
        content_style: `
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 20px;
            }
            h1, h2, h3, h4, h5, h6 {
                font-weight: 600;
                line-height: 1.3;
                margin-top: 1.5em;
                margin-bottom: 0.5em;
            }
            h1 { font-size: 2.25em; }
            h2 { font-size: 1.875em; }
            h3 { font-size: 1.5em; }
            h4 { font-size: 1.25em; }
            h5 { font-size: 1.125em; }
            h6 { font-size: 1em; }
            p {
                margin-bottom: 1em;
                line-height: 1.7;
            }
            ul, ol {
                margin: 1em 0;
                padding-left: 2em;
            }
            li {
                margin-bottom: 0.5em;
            }
            blockquote {
                border-left: 4px solid #e2e8f0;
                padding-left: 1.5em;
                margin: 1.5em 0;
                font-style: italic;
                color: #64748b;
            }
            code {
                background-color: #f1f5f9;
                padding: 0.125em 0.25em;
                border-radius: 0.25em;
                font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
                font-size: 0.875em;
            }
            pre {
                background-color: #1e293b;
                color: #e2e8f0;
                padding: 1.5em;
                border-radius: 0.5em;
                overflow-x: auto;
                margin: 1.5em 0;
            }
            pre code {
                background: none;
                padding: 0;
                color: inherit;
            }
            img {
                max-width: 100%;
                height: auto;
                border-radius: 0.5em;
                margin: 1em 0;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin: 1.5em 0;
            }
            th, td {
                border: 1px solid #e2e8f0;
                padding: 0.75em;
                text-align: left;
            }
            th {
                background-color: #f8fafc;
                font-weight: 600;
            }
            a {
                color: #3b82f6;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
        `,
        // Enhanced formatting options
        formats: {
            h1: { block: 'h1', classes: 'text-3xl font-bold mb-4' },
            h2: { block: 'h2', classes: 'text-2xl font-bold mb-3' },
            h3: { block: 'h3', classes: 'text-xl font-bold mb-2' },
            h4: { block: 'h4', classes: 'text-lg font-bold mb-2' },
            h5: { block: 'h5', classes: 'text-base font-bold mb-1' },
            h6: { block: 'h6', classes: 'text-sm font-bold mb-1' },
            p: { block: 'p', classes: 'mb-4' },
            blockquote: { block: 'blockquote', classes: 'border-l-4 border-gray-300 pl-4 italic text-gray-600 my-4' }
        },
        // Auto-save functionality
        auto_save_interval: '30s',
        auto_save_retention: '2m',
        auto_save_restore_when_empty: true,
        // Paste handling
        paste_data_images: true,
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles_if_webkit: false,
        paste_merge_formats: true,
        paste_convert_word_fake_lists: true,
        // Image handling
        image_advtab: true,
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        // Table handling
        table_default_attributes: {
            border: '1'
        },
        table_default_styles: {
            'border-collapse': 'collapse',
            'width': '100%'
        },
        // Link handling
        link_assume_external_targets: true,
        link_context_toolbar: true,
        // Word count
        wordcount: {
            show_paragraphs: false,
            show_wordcount: true,
            show_charcount: true,
            count_whitespace_chars: false
        },
        // Setup callback for additional configuration
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
            
            // Add custom button for inserting formatted content
            editor.ui.registry.addButton('insertcontent', {
                text: 'Insert Content',
                onAction: function () {
                    editor.insertContent('<p>Your content here...</p>');
                }
            });
        }
    });

    // AI Assistant Functions
    async function generateAITitle() {
        const titleInput = document.getElementById('title');
        const currentTitle = titleInput.value.trim();
        
        if (!currentTitle) {
            alert('Please enter a topic or existing title first');
            return;
        }

        try {
            showLoading(titleInput);
            
            const response = await fetch('{{ route("admin.ai.generate-titles") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    topic: currentTitle,
                    count: 5
                })
            });

            const data = await response.json();
            
            if (data.success) {
                showTitleSuggestions(data.data.titles, titleInput);
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to generate title suggestions');
        } finally {
            hideLoading(titleInput);
        }
    }

    async function generateAIMetaTitle() {
        const metaTitleInput = document.getElementById('meta_title');
        const titleInput = document.getElementById('title');
        const title = titleInput.value.trim();
        
        if (!title) {
            alert('Please enter a title first');
            return;
        }

        try {
            showLoading(metaTitleInput);
            
            const response = await fetch('{{ route("admin.ai.generate-meta-description") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    content_type: 'blog_post'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                metaTitleInput.value = title; // Use the main title as meta title
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to generate meta title');
        } finally {
            hideLoading(metaTitleInput);
        }
    }

    async function generateAIMetaDescription() {
        const metaDescInput = document.getElementById('meta_description');
        const titleInput = document.getElementById('title');
        const excerptInput = document.getElementById('excerpt');
        
        const title = titleInput.value.trim();
        const content = excerptInput.value.trim();
        
        if (!title) {
            alert('Please enter a title first');
            return;
        }

        try {
            showLoading(metaDescInput);
            
            const response = await fetch('{{ route("admin.ai.generate-meta-description") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    content: content,
                    content_type: 'blog_post'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                metaDescInput.value = data.data.meta_description;
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to generate meta description');
        } finally {
            hideLoading(metaDescInput);
        }
    }

    async function generateAIExcerpt() {
        const excerptInput = document.getElementById('excerpt');
        const titleInput = document.getElementById('title');
        const contentEditor = tinymce.get('content-editor');
        
        const title = titleInput.value.trim();
        const content = contentEditor ? contentEditor.getContent() : '';
        
        if (!title) {
            alert('Please enter a title first');
            return;
        }

        try {
            showLoading(excerptInput);
            
            const response = await fetch('{{ route("admin.ai.generate-meta-description") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    content: content,
                    content_type: 'blog_post'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                // Use meta description as excerpt (they serve similar purposes)
                excerptInput.value = data.data.meta_description;
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to generate excerpt');
        } finally {
            hideLoading(excerptInput);
        }
    }

    async function generateAITags() {
        const tagsInput = document.getElementById('tags');
        const titleInput = document.getElementById('title');
        const excerptInput = document.getElementById('excerpt');
        const contentEditor = tinymce.get('content-editor');
        
        const title = titleInput.value.trim();
        const excerpt = excerptInput.value.trim();
        const content = contentEditor ? contentEditor.getContent() : '';
        
        if (!title) {
            alert('Please enter a title first');
            return;
        }

        try {
            showLoading(tagsInput);
            
            // Combine title, excerpt, and content for better tag generation
            const fullContent = `${title} ${excerpt} ${content}`.replace(/<[^>]*>/g, ''); // Remove HTML tags
            
            const response = await fetch('{{ route("admin.ai.generate-tags") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: fullContent,
                    max_tags: 8
                })
            });

            const data = await response.json();
            
            if (data.success) {
                const tags = data.data.tags.join(', ');
                tagsInput.value = tags;
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to generate tags');
        } finally {
            hideLoading(tagsInput);
        }
    }

    function openAIAssistant() {
        // Create AI Assistant Modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        modal.innerHTML = `
            <div class="bg-surface rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-border">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-text-primary flex items-center gap-2">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            AI Content Assistant
                        </h2>
                        <button onclick="closeAIAssistant()" class="text-text-secondary hover:text-text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <!-- AI Usage Widget -->
                    <div id="ai-usage-widget" class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    AI Credits
                                </h3>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Loading usage data...</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100" id="credits-display">$100.00</div>
                                <div class="text-xs text-blue-600 dark:text-blue-400" id="usage-status">Healthy</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-blue-600 dark:text-blue-400 mb-1">
                                <span>Usage Today</span>
                                <span id="today-requests">0 requests</span>
                            </div>
                            <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2">
                                <div id="usage-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button onclick="improveContentGrammar()" class="p-4 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:shadow-lg transition-all">
                            <h3 class="font-semibold">✨ Improve Grammar</h3>
                            <p class="text-sm opacity-90">Fix grammar and spelling errors</p>
                        </button>
                        <button onclick="improveContentSEO()" class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg hover:shadow-lg transition-all">
                            <h3 class="font-semibold">🎯 SEO Optimization</h3>
                            <p class="text-sm opacity-90">Optimize for search engines</p>
                        </button>
                        <button onclick="improveContentReadability()" class="p-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg transition-all">
                            <h3 class="font-semibold">📖 Improve Readability</h3>
                            <p class="text-sm opacity-90">Make content easier to read</p>
                        </button>
                        <button onclick="analyzeContent()" class="p-4 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg hover:shadow-lg transition-all">
                            <h3 class="font-semibold">📊 Content Analysis</h3>
                            <p class="text-sm opacity-90">Get detailed content insights</p>
                        </button>
                    </div>
                    <div class="border-t border-border pt-4">
                        <h3 class="font-semibold text-text-primary mb-2">Content Preview</h3>
                        <div id="content-preview" class="bg-background p-3 rounded border border-border text-sm text-text-secondary max-h-32 overflow-y-auto">
                            ${tinymce.get('content-editor') ? tinymce.get('content-editor').getContent().substring(0, 200) + '...' : 'No content to preview'}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeAIAssistant();
            }
        });
        
        // Load AI usage data when modal opens
        setTimeout(() => {
            loadAIUsageData();
        }, 100);
        
        // Fallback: Ensure widget is visible even if API fails
        setTimeout(() => {
            const widget = document.getElementById('ai-usage-widget');
            if (widget) {
                console.log('AI Usage Widget is visible:', widget.offsetHeight > 0);
                widget.style.display = 'block';
            }
        }, 500);
    }

    function closeAIAssistant() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) {
            modal.remove();
        }
    }

    async function improveContentGrammar() {
        const contentEditor = tinymce.get('content-editor');
        if (!contentEditor) {
            alert('Please add some content first');
            return;
        }
        
        const content = contentEditor.getContent();
        if (!content.trim()) {
            alert('Please add some content first');
            return;
        }

        try {
            const response = await fetch('{{ route("admin.ai.improve-content") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content,
                    focus: 'grammar'
                })
            });

            const data = await response.json();
            
            if (data.success && data.data.improved_content) {
                contentEditor.setContent(data.data.improved_content);
                closeAIAssistant();
                alert('Content grammar has been improved!');
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to improve content grammar');
        }
    }

    async function improveContentSEO() {
        const contentEditor = tinymce.get('content-editor');
        if (!contentEditor) {
            alert('Please add some content first');
            return;
        }
        
        const content = contentEditor.getContent();
        if (!content.trim()) {
            alert('Please add some content first');
            return;
        }

        try {
            const response = await fetch('{{ route("admin.ai.improve-content") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content,
                    focus: 'seo'
                })
            });

            const data = await response.json();
            
            if (data.success && data.data.improved_content) {
                contentEditor.setContent(data.data.improved_content);
                closeAIAssistant();
                alert('Content has been optimized for SEO!');
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to optimize content for SEO');
        }
    }

    async function improveContentReadability() {
        const contentEditor = tinymce.get('content-editor');
        if (!contentEditor) {
            alert('Please add some content first');
            return;
        }
        
        const content = contentEditor.getContent();
        if (!content.trim()) {
            alert('Please add some content first');
            return;
        }

        try {
            const response = await fetch('{{ route("admin.ai.improve-content") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content,
                    focus: 'readability'
                })
            });

            const data = await response.json();
            
            if (data.success && data.data.improved_content) {
                contentEditor.setContent(data.data.improved_content);
                closeAIAssistant();
                alert('Content readability has been improved!');
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to improve content readability');
        }
    }

    async function analyzeContent() {
        const contentEditor = tinymce.get('content-editor');
        if (!contentEditor) {
            alert('Please add some content first');
            return;
        }
        
        const content = contentEditor.getContent();
        if (!content.trim()) {
            alert('Please add some content first');
            return;
        }

        try {
            const response = await fetch('{{ route("admin.ai.analyze-content") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content
                })
            });

            const data = await response.json();
            
            if (data.success) {
                // Show analysis results in a modal
                showAnalysisResults(data.data);
                refreshUsageAfterAI();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to analyze content');
        }
    }

    function showAnalysisResults(analysis) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
        modal.innerHTML = `
            <div class="bg-surface rounded-xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-border">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-text-primary">📊 Content Analysis Results</h2>
                        <button onclick="closeAIAssistant()" class="text-text-secondary hover:text-text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-background p-4 rounded-lg border border-border">
                                <h3 class="font-semibold text-text-primary mb-2">📝 Word Count</h3>
                                <p class="text-2xl font-bold text-accent">${analysis.word_count || 'N/A'}</p>
                            </div>
                            <div class="bg-background p-4 rounded-lg border border-border">
                                <h3 class="font-semibold text-text-primary mb-2">⏱️ Reading Time</h3>
                                <p class="text-2xl font-bold text-accent">${analysis.reading_time || 'N/A'} min</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-background p-4 rounded-lg border border-border">
                                <h3 class="font-semibold text-text-primary mb-2">📊 Readability Score</h3>
                                <p class="text-2xl font-bold text-accent">${analysis.readability_score || 'N/A'}</p>
                            </div>
                            <div class="bg-background p-4 rounded-lg border border-border">
                                <h3 class="font-semibold text-text-primary mb-2">🎯 SEO Score</h3>
                                <p class="text-2xl font-bold text-accent">${analysis.seo_score || 'N/A'}</p>
                            </div>
                        </div>
                    </div>
                    ${analysis.suggestions ? `
                        <div class="mt-6">
                            <h3 class="font-semibold text-text-primary mb-3">💡 Suggestions</h3>
                            <ul class="space-y-2">
                                ${analysis.suggestions.map(suggestion => `
                                    <li class="bg-background p-3 rounded border border-border text-text-secondary">• ${suggestion}</li>
                                `).join('')}
                            </ul>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeAIAssistant();
            }
        });
    }

    function showLoading(input) {
        const button = input.parentElement.querySelector('button');
        if (button) {
            button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Loading...';
            button.disabled = true;
            button.classList.add('opacity-75');
        }
    }

    function hideLoading(input) {
        const button = input.parentElement.querySelector('button');
        if (button) {
            // Restore original content based on button position
            const isTitle = button.closest('div').querySelector('#title');
            const isMetaTitle = button.closest('div').querySelector('#meta_title');
            const isMetaDesc = button.closest('div').querySelector('#meta_description');
            const isExcerpt = button.closest('div').querySelector('#excerpt');
            const isTags = button.closest('div').querySelector('#tags');
            
            if (isTitle) {
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg> AI';
            } else if (isMetaTitle) {
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg> AI';
            } else if (isMetaDesc) {
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg> AI';
            } else if (isExcerpt) {
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg> AI';
            } else if (isTags) {
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg> AI';
            }
            
            button.disabled = false;
            button.classList.remove('opacity-75');
        }
    }

    function showTitleSuggestions(titles, input) {
        const suggestions = titles.join('\n\n');
        const useSuggestion = confirm(`Here are 5 title suggestions:\n\n${suggestions}\n\nWould you like to use the first suggestion?`);
        
        if (useSuggestion) {
            input.value = titles[0];
        }
    }

    // AI Usage Tracking Functions
    async function loadAIUsageData() {
        try {
            const response = await fetch('{{ route("admin.ai.usage") }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                updateAIUsageDisplay(data.data);
            } else {
                // Show default data if API fails
                showDefaultUsageData();
            }
        } catch (error) {
            console.error('Failed to load AI usage data:', error);
            // Show default data if API fails
            showDefaultUsageData();
        }
    }

    function showDefaultUsageData() {
        console.log('Showing default AI usage data');
        const defaultData = {
            credits_remaining: 100.00,
            requests_today: 0,
            usage_percentage: 0,
            status: 'healthy'
        };
        updateAIUsageDisplay(defaultData);
    }

    function updateAIUsageDisplay(usageData) {
        console.log('Updating AI usage display with data:', usageData);
        const creditsDisplay = document.getElementById('credits-display');
        const usageStatus = document.getElementById('usage-status');
        const todayRequests = document.getElementById('today-requests');
        const usageBar = document.getElementById('usage-bar');
        
        console.log('Found elements:', {
            creditsDisplay: !!creditsDisplay,
            usageStatus: !!usageStatus,
            todayRequests: !!todayRequests,
            usageBar: !!usageBar
        });

        if (creditsDisplay) {
            creditsDisplay.textContent = `$${usageData.credits_remaining.toFixed(2)}`;
        }

        if (usageStatus) {
            usageStatus.textContent = capitalizeFirst(usageData.status);
            
            // Update status color based on usage level
            const statusColors = {
                'healthy': 'text-green-600 dark:text-green-400',
                'moderate': 'text-yellow-600 dark:text-yellow-400',
                'low': 'text-orange-600 dark:text-orange-400',
                'exhausted': 'text-red-600 dark:text-red-400'
            };
            
            usageStatus.className = `text-xs ${statusColors[usageData.status] || 'text-blue-600 dark:text-blue-400'}`;
        }

        if (todayRequests) {
            todayRequests.textContent = `${usageData.requests_today} requests`;
        }

        if (usageBar) {
            const percentage = Math.min(usageData.usage_percentage, 100);
            usageBar.style.width = `${percentage}%`;
            
            // Update bar color based on usage level
            const barColors = {
                'healthy': 'bg-green-500',
                'moderate': 'bg-yellow-500',
                'low': 'bg-orange-500',
                'exhausted': 'bg-red-500'
            };
            
            usageBar.className = `${barColors[usageData.status] || 'bg-blue-600'} h-2 rounded-full transition-all duration-300`;
        }
    }

    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Refresh usage data after AI operations
    function refreshUsageAfterAI() {
        setTimeout(() => {
            loadAIUsageData();
        }, 1000);
    }
  
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
    const featuredImageInput = document.getElementById('featured_image_id');

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
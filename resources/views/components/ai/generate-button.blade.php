@props([
    'type' => 'content',
    'target' => null,
    'size' => 'sm',
    'variant' => 'outline-primary',
    'text' => 'Generate with AI',
    'icon' => 'magic',
    'disabled' => false,
    'data' => []
])

@php
    $sizeClasses = [
        'xs' => 'btn-xs',
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg'
    ];
    
    $iconMap = [
        'magic' => 'bi-magic',
        'robot' => 'bi-robot',
        'lightbulb' => 'bi-lightbulb',
        'gear' => 'bi-gear',
        'pencil' => 'bi-pencil-square',
        'tags' => 'bi-tags',
        'search' => 'bi-search',
        'share' => 'bi-share'
    ];
    
    $buttonClass = 'btn ai-generate-btn ' . ($sizeClasses[$size] ?? '') . ' btn-' . $variant;
    $iconClass = $iconMap[$icon] ?? 'bi-magic';
    
    $isDisabled = $disabled || !($aiAvailable ?? true);
@endphp

<button 
    type="button" 
    class="{{ $buttonClass }} {{ $isDisabled ? 'disabled' : '' }}"
    data-ai-type="{{ $type }}"
    @if($target) data-ai-target="{{ $target }}" @endif
    @foreach($data as $key => $value)
        data-{{ $key }}="{{ $value }}"
    @endforeach
    {{ $isDisabled ? 'disabled' : '' }}
    {{ $attributes->merge(['onclick' => 'generateAIContent(this)']) }}
>
    <i class="bi {{ $iconClass }} me-1"></i>
    {{ $text }}
    <span class="ai-loading-spinner d-none ms-2">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </span>
</button>

@once
@push('styles')
<style>
.ai-generate-btn {
    position: relative;
    transition: all 0.2s ease;
}

.ai-generate-btn:hover:not(.disabled) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ai-generate-btn.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.ai-generate-btn .ai-loading-spinner {
    display: inline-block;
}

.ai-generate-btn.loading {
    opacity: 0.8;
    cursor: wait;
}

.ai-generate-btn.loading .ai-loading-spinner {
    display: inline-block !important;
}

.ai-tooltip {
    font-size: 0.875rem;
}

@keyframes ai-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.ai-generate-btn.pulse {
    animation: ai-pulse 2s infinite;
}
</style>
@endpush

@push('scripts')
<script>
// Global AI content generation function
window.generateAIContent = function(button) {
    const type = button.dataset.aiType;
    const target = button.dataset.aiTarget;
    
    // Check if AI is available
    @if(!($aiAvailable ?? true))
        alert('AI service is not available. Please check your configuration.');
        return;
    @endif
    
    // Show loading state
    showAILoading(button);
    
    // Route to appropriate generation function
    switch(type) {
        case 'meta-description':
            generateMetaDescriptionForTarget(target, button);
            break;
        case 'tags':
            generateTagsForTarget(target, button);
            break;
        case 'title':
            generateTitleForTarget(target, button);
            break;
        case 'content':
            generateContentForTarget(target, button);
            break;
        case 'excerpt':
            generateExcerptForTarget(target, button);
            break;
        default:
            console.warn('Unknown AI generation type:', type);
            hideAILoading(button);
    }
};

function showAILoading(button) {
    button.classList.add('loading');
    button.disabled = true;
    const spinner = button.querySelector('.ai-loading-spinner');
    if (spinner) {
        spinner.classList.remove('d-none');
    }
}

function hideAILoading(button) {
    button.classList.remove('loading');
    button.disabled = false;
    const spinner = button.querySelector('.ai-loading-spinner');
    if (spinner) {
        spinner.classList.add('d-none');
    }
}

// Meta Description Generation
async function generateMetaDescriptionForTarget(target, button) {
    try {
        const titleField = document.getElementById(target + '_title') || document.getElementById('title');
        const contentField = document.getElementById(target + '_content') || document.getElementById('body');
        const metaField = document.getElementById(target) || document.getElementById('meta_description');
        
        if (!titleField || !metaField) {
            throw new Error('Required fields not found');
        }
        
        const title = titleField.value.trim();
        if (!title) {
            throw new Error('Please enter a title first');
        }
        
        const response = await fetch('{{ route("admin.ai.generate-meta-description") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: title,
                content: contentField ? contentField.value : ''
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            metaField.value = result.data.meta_description;
            showAISuccess('Meta description generated successfully!');
            
            // Update character count if exists
            const charCount = metaField.parentElement.querySelector('.char-count');
            if (charCount) {
                charCount.textContent = result.data.character_count + '/155';
            }
        } else {
            throw new Error(result.message || 'Generation failed');
        }
    } catch (error) {
        showAIError('Failed to generate meta description: ' + error.message);
    } finally {
        hideAILoading(button);
    }
}

// Tags Generation
async function generateTagsForTarget(target, button) {
    try {
        const contentField = document.getElementById(target + '_content') || document.getElementById('body');
        const tagsField = document.getElementById(target) || document.getElementById('tags');
        
        if (!contentField || !tagsField) {
            throw new Error('Required fields not found');
        }
        
        const content = contentField.value.trim();
        if (!content) {
            throw new Error('Please add some content first');
        }
        
        const response = await fetch('{{ route("admin.ai.generate-tags") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                content: content,
                max_tags: 8
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            const tags = result.data.tags.join(', ');
            
            if (tagsField.tagName === 'SELECT') {
                // Handle Select2 or similar tag selectors
                result.data.tags.forEach(tag => {
                    const option = new Option(tag, tag, false, true);
                    tagsField.add(option);
                });
                $(tagsField).trigger('change'); // Trigger Select2 update if present
            } else {
                tagsField.value = tags;
            }
            
            showAISuccess('Tags generated successfully!');
        } else {
            throw new Error(result.message || 'Generation failed');
        }
    } catch (error) {
        showAIError('Failed to generate tags: ' + error.message);
    } finally {
        hideAILoading(button);
    }
}

// Title Generation
async function generateTitleForTarget(target, button) {
    try {
        const topicField = document.getElementById(target + '_topic') || document.getElementById('topic');
        const titleField = document.getElementById(target) || document.getElementById('title');
        
        if (!titleField) {
            throw new Error('Title field not found');
        }
        
        let topic = '';
        if (topicField) {
            topic = topicField.value.trim();
        } else {
            // Try to derive topic from existing content
            const contentField = document.getElementById('body') || document.getElementById('content');
            if (contentField && contentField.value.trim()) {
                topic = contentField.value.trim().substring(0, 100);
            }
        }
        
        if (!topic) {
            topic = prompt('Enter a topic for title generation:');
            if (!topic) return;
        }
        
        const response = await fetch('{{ route("admin.ai.generate-titles") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                topic: topic,
                count: 5
            })
        });
        
        const result = await response.json();
        
        if (result.success && result.data.titles.length > 0) {
            // Show title options to user
            const titles = result.data.titles;
            let options = 'Choose a title:\n\n';
            titles.forEach((title, index) => {
                options += `${index + 1}. ${title}\n`;
            });
            
            const choice = prompt(options + '\nEnter number (1-' + titles.length + '):');
            const choiceIndex = parseInt(choice) - 1;
            
            if (choiceIndex >= 0 && choiceIndex < titles.length) {
                titleField.value = titles[choiceIndex];
                showAISuccess('Title updated successfully!');
                
                // Auto-generate slug if slug field exists
                const slugField = document.getElementById('slug');
                if (slugField && !slugField.value) {
                    slugField.value = titles[choiceIndex].toLowerCase()
                        .replace(/[^a-z0-9\s]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .trim('-');
                }
            }
        } else {
            throw new Error(result.message || 'No titles generated');
        }
    } catch (error) {
        showAIError('Failed to generate titles: ' + error.message);
    } finally {
        hideAILoading(button);
    }
}

// Content Generation
async function generateContentForTarget(target, button) {
    try {
        const titleField = document.getElementById('title');
        const contentField = document.getElementById(target) || document.getElementById('body');
        
        if (!contentField) {
            throw new Error('Content field not found');
        }
        
        let topic = '';
        if (titleField && titleField.value.trim()) {
            topic = titleField.value.trim();
        } else {
            topic = prompt('Enter a topic for content generation:');
            if (!topic) return;
        }
        
        const response = await fetch('{{ route("admin.ai.generate-blog-post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                topic: topic,
                tone: 'professional',
                word_count: 800
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update content field (handle TinyMCE if present)
            if (typeof tinymce !== 'undefined' && tinymce.get(contentField.id)) {
                tinymce.get(contentField.id).setContent(result.data.content.replace(/\n/g, '<br>'));
            } else {
                contentField.value = result.data.content;
            }
            
            // Update other fields if they exist and are empty
            const titleField = document.getElementById('title');
            if (titleField && !titleField.value.trim()) {
                titleField.value = result.data.title;
            }
            
            const excerptField = document.getElementById('excerpt');
            if (excerptField && !excerptField.value.trim()) {
                excerptField.value = result.data.excerpt;
            }
            
            const metaField = document.getElementById('meta_description');
            if (metaField && !metaField.value.trim()) {
                metaField.value = result.data.meta_description;
            }
            
            showAISuccess('Content generated successfully!');
        } else {
            throw new Error(result.message || 'Generation failed');
        }
    } catch (error) {
        showAIError('Failed to generate content: ' + error.message);
    } finally {
        hideAILoading(button);
    }
}

// Excerpt Generation
async function generateExcerptForTarget(target, button) {
    try {
        const contentField = document.getElementById('body') || document.getElementById('content');
        const excerptField = document.getElementById(target) || document.getElementById('excerpt');
        
        if (!contentField || !excerptField) {
            throw new Error('Required fields not found');
        }
        
        const content = contentField.value.trim();
        if (!content) {
            throw new Error('Please add some content first');
        }
        
        // Simple excerpt generation (first 160 characters of clean text)
        const cleanText = content.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();
        let excerpt = cleanText.substring(0, 157);
        
        if (cleanText.length > 157) {
            excerpt += '...';
        }
        
        excerptField.value = excerpt;
        showAISuccess('Excerpt generated successfully!');
    } catch (error) {
        showAIError('Failed to generate excerpt: ' + error.message);
    } finally {
        hideAILoading(button);
    }
}

// Utility functions
function showAISuccess(message) {
    // You can customize this to use your preferred notification system
    if (typeof toastr !== 'undefined') {
        toastr.success(message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', message, 'success');
    } else {
        alert('✓ ' + message);
    }
}

function showAIError(message) {
    // You can customize this to use your preferred notification system
    if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire('Error!', message, 'error');
    } else {
        alert('✗ ' + message);
    }
}
</script>
@endpush
@endonce
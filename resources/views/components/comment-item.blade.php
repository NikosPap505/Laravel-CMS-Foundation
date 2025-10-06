{{-- Individual Comment Item --}}
<div class="comment-item {{ $level > 0 ? 'ml-6 border-l-2 border-accent/30 pl-4 bg-background/50 rounded-r-lg' : '' }}">
    <div class="flex space-x-3">
        {{-- Avatar --}}
        <img src="{{ $comment->author_avatar }}" 
             alt="{{ $comment->author_name }}" 
             class="w-10 h-10 rounded-full flex-shrink-0">
        
        {{-- Comment Content --}}
        <div class="flex-1">
            {{-- Author & Date --}}
            <div class="flex items-center space-x-2 mb-1">
                <h4 class="font-medium text-text-primary">{{ $comment->author_name }}</h4>
                <span class="text-xs text-text-secondary">{{ $comment->created_at->diffForHumans() }}</span>
                @if($comment->user && $comment->user->hasRole('admin'))
                    <span class="px-2 py-1 text-xs bg-accent text-white rounded-full">Admin</span>
                @endif
            </div>
            
            {{-- Comment Text --}}
            <div class="text-text-secondary mb-3">
                {!! nl2br(e($comment->content)) !!}
            </div>
            
            {{-- Actions --}}
            <div class="flex items-center space-x-4 text-sm">
                @auth
                    <button onclick="showReplyForm({{ $comment->id }})" 
                            class="text-accent hover:text-accent/80 font-medium hover:underline transition-colors">
                        💬 Reply
                    </button>
                @endauth
                
                @if($comment->hasReplies())
                    <button onclick="toggleReplies({{ $comment->id }})" 
                            class="text-text-secondary hover:text-accent transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        {{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}
                    </button>
                @endif
            </div>
            
            {{-- Reply Form (Hidden by default) --}}
            <div id="reply-form-{{ $comment->id }}" class="mt-4 p-4 bg-background/50 rounded-lg border border-border" style="display: none;">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    <span class="text-sm font-medium text-text-primary">Replying to {{ $comment->author_name }}</span>
                </div>
                <form class="reply-form" data-parent-id="{{ $comment->id }}">
                    @csrf
                    <div class="mb-3">
                        <textarea 
                            name="content" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Write a reply to {{ $comment->author_name }}..."
                            required
                        ></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" 
                                onclick="hideReplyForm({{ $comment->id }})"
                                class="px-4 py-2 text-text-secondary hover:text-text-primary transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors">
                            💬 Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Replies --}}
    @if($comment->replies->count() > 0 && $level < 2)
        <div id="replies-{{ $comment->id }}" class="mt-4 space-y-4 {{ $level === 0 ? 'hidden' : '' }}">
            @foreach($comment->replies as $reply)
                @include('components.comment-item', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>

<script>
// Simple, reliable reply functions
function showReplyForm(commentId) {
    console.log('Showing reply form for comment:', commentId);
    const replyForm = document.getElementById('reply-form-' + commentId);
    console.log('Found reply form:', replyForm);
    
    if (replyForm) {
        replyForm.style.display = 'block';
        replyForm.classList.remove('hidden');
        
        // Focus the textarea
        const textarea = replyForm.querySelector('textarea');
        if (textarea) {
            textarea.focus();
        }
        
        console.log('Reply form should now be visible');
    } else {
        console.error('Reply form not found for comment:', commentId);
        alert('Reply form not found. Please refresh the page.');
    }
}

function hideReplyForm(commentId) {
    const replyForm = document.getElementById('reply-form-' + commentId);
    if (replyForm) {
        replyForm.style.display = 'none';
        replyForm.classList.add('hidden');
        
        // Clear the textarea
        const textarea = replyForm.querySelector('textarea');
        if (textarea) {
            textarea.value = '';
        }
    }
}

function toggleReplies(commentId) {
    const replies = document.getElementById('replies-' + commentId);
    if (replies) {
        if (replies.style.display === 'none') {
            replies.style.display = 'block';
        } else {
            replies.style.display = 'none';
        }
    }
}

// Make functions globally available for testing
window.showReplyForm = showReplyForm;
window.hideReplyForm = hideReplyForm;
window.toggleReplies = toggleReplies;

// Test function - you can call this in console
window.testReply = function() {
    console.log('Testing reply functionality...');
    const firstReplyForm = document.querySelector('[id^="reply-form-"]');
    if (firstReplyForm) {
        const commentId = firstReplyForm.id.replace('reply-form-', '');
        console.log('Found comment ID:', commentId);
        showReplyForm(commentId);
    } else {
        console.log('No reply forms found on page');
    }
};

// Handle reply form submission
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const parentId = this.dataset.parentId;
            const content = formData.get('content');
            
            if (!content.trim()) {
                alert('Please enter a reply.');
                return;
            }
            
            // Add parent_id to form data
            formData.append('parent_id', parentId);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Posting...';
            submitBtn.disabled = true;
            
            fetch('{{ route("comments.store", $post) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear form and hide it
                    this.reset();
                    this.closest('.hidden').classList.add('hidden');
                    
            // Show success message using toast system
            if (window.toast) {
                window.toast.success(data.message);
            } else {
                showNotification(data.message, 'success');
            }
                    
                    // Reload comments
                    loadComments();
                } else {
                    if (window.toast) {
                        window.toast.error(data.message || 'Error posting reply.');
                    } else {
                        showNotification(data.message || 'Error posting reply.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.toast) {
                    window.toast.error('Error posting reply. Please try again.');
                } else {
                    showNotification('Error posting reply. Please try again.', 'error');
                }
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    });
});
</script>

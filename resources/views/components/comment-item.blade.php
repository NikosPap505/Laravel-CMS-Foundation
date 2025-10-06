{{-- Individual Comment Item --}}
<div class="comment-item {{ $level > 0 ? 'ml-8 border-l-2 border-border pl-4' : '' }}">
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
                    <button onclick="replyToComment({{ $comment->id }})" 
                            class="text-accent hover:underline">
                        Reply
                    </button>
                @endauth
                
                @if($comment->hasReplies())
                    <button onclick="toggleReplies({{ $comment->id }})" 
                            class="text-text-secondary hover:underline">
                        {{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}
                    </button>
                @endif
            </div>
            
            {{-- Reply Form (Hidden by default) --}}
            <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                <form class="reply-form" data-parent-id="{{ $comment->id }}">
                    @csrf
                    <div class="mb-3">
                        <textarea 
                            name="content" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                            placeholder="Write a reply..."
                            required
                        ></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" 
                                onclick="cancelReply({{ $comment->id }})"
                                class="px-4 py-2 text-text-secondary hover:text-text-primary">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors">
                            Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Replies --}}
    @if($comment->replies->count() > 0 && $level < 2)
        <div id="replies-{{ $comment->id }}" class="mt-4 space-y-4">
            @foreach($comment->replies as $reply)
                @include('components.comment-item', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>

<script>
function replyToComment(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.remove('hidden');
        replyForm.querySelector('textarea').focus();
    }
}

function cancelReply(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.add('hidden');
        replyForm.querySelector('textarea').value = '';
    }
}

function toggleReplies(commentId) {
    const replies = document.getElementById(`replies-${commentId}`);
    if (replies) {
        replies.classList.toggle('hidden');
    }
}

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
                    
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Reload comments
                    loadComments();
                } else {
                    showNotification(data.message || 'Error posting reply.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error posting reply. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    });
});
</script>

{{-- Comment Section Component --}}
<div class="mt-16 max-w-4xl mx-auto">
    <div class="bg-surface rounded-lg border border-border p-6">
        <h2 class="text-2xl font-bold text-text-primary mb-6">
            Comments ({{ $comments->count() }})
        </h2>

        {{-- Comment Form --}}
        <div class="mb-8">
            <form id="comment-form" class="space-y-4">
                @csrf
                
                {{-- Guest Comment Fields (shown when not logged in) --}}
                @guest
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="author_name" class="block text-sm font-medium text-text-primary mb-2">
                                Your Name *
                            </label>
                            <input 
                                type="text" 
                                id="author_name" 
                                name="author_name" 
                                class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                placeholder="Enter your name"
                                required
                            >
                        </div>
                        <div>
                            <label for="author_email" class="block text-sm font-medium text-text-primary mb-2">
                                Your Email *
                            </label>
                            <input 
                                type="email" 
                                id="author_email" 
                                name="author_email" 
                                class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                placeholder="Enter your email"
                                required
                            >
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Your comment will be reviewed before being published to maintain quality.
                        </p>
                    </div>
                @endguest
                
                <div>
                    <label for="content" class="block text-sm font-medium text-text-primary mb-2">
                        Leave a Comment
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Share your thoughts..."
                        required
                    ></textarea>
                </div>
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors"
                    >
                        @auth
                            Post Comment
                        @else
                            Submit Comment
                        @endauth
                    </button>
                </div>
            </form>
        </div>

        {{-- Comments List --}}
        <div id="comments-list" class="space-y-6">
            @forelse($comments as $comment)
                @include('components.comment-item', ['comment' => $comment, 'level' => 0])
            @empty
                <div class="text-center py-8">
                    <p class="text-text-secondary">No comments yet. Be the first to share your thoughts!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    const commentsList = document.getElementById('comments-list');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const content = formData.get('content');
            
            if (!content.trim()) {
                alert('Please enter a comment.');
                return;
            }
            
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
                    // Clear form
                    this.reset();
                    
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Reload comments
                    loadComments();
                } else {
                    showNotification(data.message || 'Error posting comment.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error posting comment. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    function loadComments() {
        fetch('{{ route("comments.load", $post) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update comments count
                    const countElement = document.querySelector('h2');
                    if (countElement) {
                        countElement.textContent = `Comments (${data.comments.length})`;
                    }
                    
                    // Update comments list
                    commentsList.innerHTML = '';
                    if (data.comments.length === 0) {
                        commentsList.innerHTML = '<div class="text-center py-8"><p class="text-text-secondary">No comments yet. Be the first to share your thoughts!</p></div>';
                    } else {
                        data.comments.forEach(comment => {
                            const commentElement = createCommentElement(comment, 0);
                            commentsList.appendChild(commentElement);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
            });
    }
    
    function createCommentElement(comment, level) {
        const div = document.createElement('div');
        div.className = `comment-item ${level > 0 ? 'ml-8' : ''}`;
        div.innerHTML = `
            <div class="flex space-x-3">
                <img src="${comment.user ? 'https://www.gravatar.com/avatar/' + md5(comment.user.email) + '?d=identicon&s=40' : 'https://www.gravatar.com/avatar/' + md5(comment.author_email) + '?d=identicon&s=40'}" 
                     alt="${comment.user ? comment.user.name : comment.author_name}" 
                     class="w-10 h-10 rounded-full">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-1">
                        <h4 class="font-medium text-text-primary">${comment.user ? comment.user.name : comment.author_name}</h4>
                        <span class="text-xs text-text-secondary">${new Date(comment.created_at).toLocaleDateString()}</span>
                    </div>
                    <p class="text-text-secondary">${comment.content}</p>
                    <div class="mt-2">
                        <button onclick="replyToComment(${comment.id})" class="text-sm text-accent hover:underline">
                            Reply
                        </button>
                    </div>
                </div>
            </div>
        `;
        return div;
    }
    
    function showNotification(message, type) {
        // Simple notification - you can enhance this with a proper notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Make functions globally available
    window.replyToComment = function(commentId) {
        // Implementation for reply functionality
        console.log('Reply to comment:', commentId);
    };
    
    // Simple MD5 function for Gravatar
    function md5(string) {
        // This is a simplified version - in production, use a proper MD5 library
        return btoa(string).replace(/[^a-zA-Z0-9]/g, '').substring(0, 32);
    }
});
</script>

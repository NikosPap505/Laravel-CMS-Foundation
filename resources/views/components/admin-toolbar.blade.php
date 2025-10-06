{{-- Admin Quick Actions Toolbar --}}
@auth
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor'))
        <div class="fixed bottom-4 right-4 z-50">
            <div class="bg-surface border border-border rounded-lg shadow-lg p-2 flex flex-col space-y-2">
                {{-- Quick Post --}}
                <a href="{{ route('admin.posts.create') }}" 
                   class="p-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors group"
                   title="New Post">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>

                {{-- Quick Page --}}
                <a href="{{ route('admin.pages.create') }}" 
                   class="p-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors group"
                   title="New Page">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </a>

                {{-- Media Upload --}}
                <a href="{{ route('admin.media.create') }}" 
                   class="p-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors group"
                   title="Upload Media">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </a>

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" 
                   class="p-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors group"
                   title="Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                    </svg>
                </a>

                {{-- Toggle Toolbar --}}
                <button onclick="toggleToolbar()" 
                        class="p-3 bg-accent hover:bg-accent/80 text-white rounded-lg transition-colors group"
                        title="Toggle Toolbar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <script>
            function toggleToolbar() {
                const toolbar = document.querySelector('.fixed.bottom-4.right-4');
                const buttons = toolbar.querySelectorAll('a, button:not([onclick])');
                
                buttons.forEach(button => {
                    button.style.display = button.style.display === 'none' ? 'block' : 'none';
                });
            }
        </script>
    @endif
@endauth

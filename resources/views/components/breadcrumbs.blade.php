@unless ($breadcrumbs->isEmpty())
    <nav aria-label="breadcrumb" class="mb-8">
        <div class="bg-surface/30 backdrop-blur-sm rounded-2xl border border-border/30 p-4 shadow-sm">
            <ol class="flex flex-wrap items-center space-x-1 text-sm">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!is_null($breadcrumb->url) && !$loop->last)
                        <li class="flex items-center group">
                            <a href="{{ $breadcrumb->url }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-accent/10 hover:text-accent transition-all duration-200 group-hover:scale-105">
                                @if($loop->first)
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                @endif
                                {{ $breadcrumb->title }}
                            </a>
                            <svg class="w-4 h-4 mx-2 text-text-secondary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                    @else
                        <li class="flex items-center">
                            <span class="flex items-center px-3 py-2 rounded-lg bg-accent/10 text-accent font-semibold">
                                @if($loop->last && $loop->count > 1)
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                                {{ $breadcrumb->title }}
                            </span>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
    </nav>
@endunless


@unless ($breadcrumbs->isEmpty())
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="flex items-center text-sm font-medium">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="flex items-center">
                    @if (!is_null($breadcrumb->url) && !$loop->last)
                        <a href="{{ $breadcrumb->url }}" class="flex items-center px-2 py-1 rounded-md hover:bg-accent/10 hover:text-accent transition-all duration-200 text-text-secondary hover:text-text-primary">
                            @if($loop->first)
                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                            @endif
                            <span class="whitespace-nowrap">{{ $breadcrumb->title }}</span>
                        </a>
                    @else
                        <span class="flex items-center px-2 py-1 rounded-md bg-accent/10 text-accent font-semibold whitespace-nowrap">
                            @if($loop->last && $loop->count > 1)
                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            {{ $breadcrumb->title }}
                        </span>
                    @endif
                    @if(!$loop->last)
                        <svg class="w-4 h-4 mx-1.5 text-text-secondary/40 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endunless


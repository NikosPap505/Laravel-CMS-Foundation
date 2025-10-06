@unless ($breadcrumbs->isEmpty())
    <nav aria-label="breadcrumb" class="mb-6">
        <ol class="flex flex-wrap items-center space-x-2 text-sm text-text-secondary">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!is_null($breadcrumb->url) && !$loop->last)
                    <li class="flex items-center">
                        <a href="{{ $breadcrumb->url }}" class="hover:text-accent transition-colors">
                            {{ $breadcrumb->title }}
                        </a>
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                @else
                    <li class="text-text-primary font-medium">
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless


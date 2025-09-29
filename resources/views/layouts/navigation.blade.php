<nav x-data="{ open: false }" class="bg-surface border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-accent text-xl font-bold">
                        CMS
                    </a>
                </div>
 
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @php
                        $links = [
                            'admin.pages.index'      => 'Pages',
                            'admin.menu-items.index' => 'Menus',
                            'admin.categories.index' => 'Categories',
                            'admin.posts.index'      => 'Posts',
                        ];
                        $adminLinks = ['admin.users.index' => 'Users'];
                    @endphp
 
                    @foreach ($links as $route => $label)
                        <a href="{{ route($route) }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs($route.'*') ? 'border-accent text-text-primary' : 'border-transparent text-text-secondary hover:text-text-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    @role('admin')
                        @foreach ($adminLinks as $route => $label)
                            <a href="{{ route($route) }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs($route.'*') ? 'border-accent text-text-primary' : 'border-transparent text-text-secondary hover:text-text-primary' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    @endrole
                </div>
            </div>
 
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-text-secondary bg-surface hover:text-text-primary focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 transition duration-150 ease-in-out']) }}
   :class="document.documentElement.getAttribute('data-theme') === 'dark' ? 'text-gray-300 hover:bg-gray-700 focus:bg-gray-700' : 'text-gray-700 hover:bg-gray-100 focus:bg-gray-100'">{{ $slot }}</a>

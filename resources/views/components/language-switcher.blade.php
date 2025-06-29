<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="flex items-center space-x-1 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
        @if(current_locale() == 'id')
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
            </svg>
        @else
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
            </svg>
        @endif
        <span>{{ current_locale() == 'id' ? 'ID' : 'EN' }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
        <div class="py-1">
            <a href="?lang=id" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ current_locale() == 'id' ? 'bg-gray-50 font-semibold' : '' }}">
                <img src="https://flagcdn.com/w20/id.png" alt="Indonesia" class="w-5 h-3 mr-2">
                Bahasa Indonesia
            </a>
            <a href="?lang=en" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ current_locale() == 'en' ? 'bg-gray-50 font-semibold' : '' }}">
                <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-3 mr-2">
                English
            </a>
        </div>
    </div>
</div>
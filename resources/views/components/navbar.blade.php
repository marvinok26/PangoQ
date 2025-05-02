{{-- resources/views/components/navbar.blade.php --}}

<header class="{{ Request::is('/') ? 'absolute top-0 left-0 right-0 z-20 bg-transparent' : '' }}">
    <div class="container mx-auto px-4 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="h-8 w-8 bg-secondary-600 rounded-full"></div>
                        <span class="ml-2 text-xl font-bold {{ Request::is('/') ? 'text-white' : 'text-secondary-600' }}">pangoQ</span>
                    </a>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-2">
                <a href="{{ route('features') }}" class="px-3 py-2 text-sm 
                    {{ Request::is('features*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                    {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                    Features
                </a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 text-sm 
                    {{ Request::is('pricing*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                    {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                    Pricing
                </a>
                <a href="{{ route('support') }}" class="px-3 py-2 text-sm 
                    {{ Request::is('support*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                    {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                    Support
                </a>
                <a href="{{ route('login') }}" class="ml-4 px-4 py-2 text-sm 
                    {{ Request::is('login*') ? 'bg-secondary-50 text-secondary-600 border-secondary-600' : '' }}
                    {{ Request::is('/') ? 'border border-white text-white hover:bg-white hover:text-black hover:bg-opacity-10' : 'border border-secondary-600 text-secondary-600 hover:bg-secondary-50' }} 
                    rounded-md">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="ml-4 px-4 py-2 text-sm 
                    {{ Request::is('register*') ? 'bg-secondary-50 text-secondary-600 border-secondary-600' : '' }}
                    {{ Request::is('/') ? 'border border-white text-white hover:bg-white hover:text-black hover:bg-opacity-10' : 'border border-secondary-600 text-secondary-600 hover:bg-secondary-50' }} 
                    rounded-md">
                    Sign up
                </a>
            </div>
            <button class="md:hidden {{ Request::is('/') ? 'text-white' : 'text-gray-700' }}" x-data="{}" @click="$dispatch('toggle-menu')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
    
    {{-- Mobile Menu (Hidden by default) --}}
    <div x-data="{ open: false }" @toggle-menu.window="open = !open" x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden {{ Request::is('/') ? 'bg-gray-900 bg-opacity-95' : 'bg-white shadow-md' }}" style="display: none;">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('features') }}" class="block px-3 py-2 text-base font-medium 
                {{ Request::is('features*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                Features
            </a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 text-base font-medium 
                {{ Request::is('pricing*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                Pricing
            </a>
            <a href="{{ route('support') }}" class="block px-3 py-2 text-base font-medium 
                {{ Request::is('support*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                Support
            </a>
            <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium 
                {{ Request::is('login*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                Log in
            </a>
            <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium 
                {{ Request::is('register*') ? 'text-secondary-600 font-semibold bg-secondary-50' : '' }}
                {{ Request::is('/') ? 'text-white hover:text-secondary-200' : 'text-gray-700 hover:text-secondary-600' }}">
                Sign up
            </a>
        </div>
    </div>
</header>
<header
    class="{{ request()->is('/') ? 'absolute top-0 left-0 right-0 z-20 bg-transparent' : 'bg-white/95 backdrop-blur-sm shadow-sm' }}">
    <div class="container mx-auto px-4 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="PangoQ Logo" class="h-10 w-auto">
                    </a>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-2">
                <a href="{{ route('features') }}" class="px-3 py-2 text-sm 
                    {{ request()->is('features*') ? 'text-blue-600 font-semibold bg-blue-50 bg-opacity-80' : '' }}
                    {{ request()->is('/') ? 'text-white hover:text-blue-200' : 'text-gray-700 hover:text-blue-600' }}">
                    Features
                </a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 text-sm 
                    {{ request()->is('pricing*') ? 'text-blue-600 font-semibold bg-blue-50 bg-opacity-80' : '' }}
                    {{ request()->is('/') ? 'text-white hover:text-blue-200' : 'text-gray-700 hover:text-blue-600' }}">
                    Pricing
                </a>
                <a href="{{ route('support') }}" class="px-3 py-2 text-sm 
                    {{ request()->is('support*') ? 'text-blue-600 font-semibold bg-blue-50 bg-opacity-80' : '' }}
                    {{ request()->is('/') ? 'text-white hover:text-blue-200' : 'text-gray-700 hover:text-blue-600' }}">
                    Support
                </a>
                @auth
                    <div class="relative ml-4" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center">
                            <div class="h-8 w-8 rounded-full overflow-hidden shadow-sm border border-gray-200">
                                {{-- Use the photo_url accessor instead of directly accessing profile_photo_path --}}
                                <img src="{{ Auth::user()->photo_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                            </div>
                            <span
                                class="ml-2 text-sm font-medium {{ request()->is('/') ? 'text-white' : 'text-gray-700' }}">{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 ml-1 {{ request()->is('/') ? 'text-white' : 'text-gray-500' }}"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 py-2 bg-white rounded-md shadow-lg z-50"
                            style="display: none;">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="{{ route('profile.show') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                            <a href="{{ route('trips.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Trips</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="ml-4 px-4 py-2 text-sm 
                                {{ request()->is('login*') ? 'bg-blue-50 text-blue-600 border-blue-600' : '' }}
                                {{ request()->is('/') ? 'border border-white text-white hover:bg-white hover:text-black hover:bg-opacity-10' : 'border border-blue-600 text-blue-600 hover:bg-blue-50' }} 
                                rounded-md">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="ml-4 px-4 py-2 text-sm 
                                {{ request()->is('register*') ? 'bg-blue-700' : 'bg-blue-600 hover:bg-blue-700' }}
                                text-white rounded-md">
                        Sign up
                    </a>
                @endauth
            </div>

            <button class="md:hidden {{ request()->is('/') ? 'text-white' : 'text-gray-700' }}" x-data="{}"
                @click="$dispatch('toggle-menu')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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
        class="md:hidden {{ request()->is('/') ? 'bg-gray-900 bg-opacity-90 backdrop-blur-sm' : 'bg-white/95 backdrop-blur-sm shadow-md' }}"
        style="display: none;">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('features') }}" class="block px-3 py-2 text-base font-medium 
                {{ request()->is('features*') ? 'text-blue-600 font-semibold bg-blue-50 bg-opacity-80' : '' }}
                {{ request()->is('/') ? 'text-white hover:text-blue-200' : 'text-gray-700 hover:text-blue-600' }}">
                Features
            </a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 text-base font-medium 
                {{ request()->is('pricing*') ? 'text-blue-600 font-semibold bg-blue-50 bg-opacity-80' : '' }}
                {{ request()->is('/') ? 'text-white hover:text-blue-200' : 'text-gray-700 hover:text-blue-600' }}">
                Pricing
            </a>
            <a href="{{ route('support') }}" class="block px-3 py-2 text-base font-medium 
                {{ request()->is('support*') ? 'text-blue-600 font-semibold bg-blue-50 bg-opacity-80' : '' }}
                {{ request()->is('/') ? 'text-white hover:text-blue-200' : 'text-gray-700 hover:text-blue-600' }}">
                Support
            </a>
            @auth
                <div class="mt-3 px-3 py-3 border-t border-gray-200 {{ request()->is('/') ? 'border-gray-700' : '' }}">
                    <div class="flex items-center mb-3">
                        <div class="h-10 w-10 rounded-full overflow-hidden shadow-sm border border-gray-200">
                            {{-- Use the photo_url accessor for mobile menu as well --}}
                            <img src="{{ Auth::user()->photo_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ request()->is('/') ? 'text-white' : 'text-gray-900' }}">{{ Auth::user()->name }}</p>
                            <p class="text-xs {{ request()->is('/') ? 'text-gray-300' : 'text-gray-500' }}">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium 
                            {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ request()->is('/') ? 'text-white hover:bg-gray-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium 
                            {{ request()->routeIs('profile.show') ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ request()->is('/') ? 'text-white hover:bg-gray-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            My Profile
                        </a>
                        <a href="{{ route('trips.index') }}" class="block px-3 py-2 rounded-md text-base font-medium 
                            {{ request()->routeIs('trips.index') ? 'bg-blue-50 text-blue-600' : '' }}
                            {{ request()->is('/') ? 'text-white hover:bg-gray-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            My Trips
                        </a>
                        <div class="border-t border-gray-200 {{ request()->is('/') ? 'border-gray-700' : '' }} my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium 
                                {{ request()->is('/') ? 'text-white hover:bg-gray-800' : 'text-gray-700 hover:bg-gray-100' }}">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="mt-3 px-3 pt-3 border-t border-gray-200 {{ request()->is('/') ? 'border-gray-700' : '' }}">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 rounded-md
                        {{ request()->is('login*') ? 'bg-blue-50 text-blue-600 border-blue-600' : '' }}
                        {{ request()->is('/') ? 'border border-white text-white hover:bg-white hover:text-black hover:bg-opacity-10' : 'border border-blue-600 text-blue-600 hover:bg-blue-50' }}">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="mt-2 block w-full text-center px-4 py-2 rounded-md
                        {{ request()->is('register*') ? 'bg-blue-700' : 'bg-blue-600 hover:bg-blue-700' }}
                        text-white">
                        Sign up
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
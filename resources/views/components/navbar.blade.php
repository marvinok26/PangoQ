<header class="absolute top-0 left-0 right-0 z-20 bg-transparent">
    <div class="container mx-auto px-4 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <div class="h-8 w-8 bg-secondary-600 rounded-full"></div>
                    <span class="ml-2 text-xl font-bold text-white">pangoQ</span>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-2">
                <a href="#features" class="px-3 py-2 text-sm text-white hover:text-secondary-200">Features</a>
                <a href="#" class="px-3 py-2 text-sm text-white hover:text-secondary-200">Pricing</a>
                <a href="#" class="px-3 py-2 text-sm text-white hover:text-secondary-200">Support</a>
                <a href="{{ route('login') }}" class="ml-4 px-4 py-2 text-sm border border-white text-white rounded-md hover:bg-white hover:text-black hover:bg-opacity-10">Log in</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm bg-secondary-600 text-white rounded-md hover:bg-secondary-700">Sign up</a>
            </div>
            <button class="md:hidden text-white" x-data="{}" @click="$dispatch('toggle-menu')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</header>
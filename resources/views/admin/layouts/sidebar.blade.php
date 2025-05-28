{{-- resources/views/admin/layouts/sidebar.blade.php --}}

<aside class="w-64 bg-gradient-to-br from-blue-600 via-purple-600 to-purple-700 flex flex-col" x-data="{ openDropdowns: {} }">
    <!-- Logo Section -->
    <div class="flex-shrink-0 px-4 py-6">
        <div class="flex items-center justify-center">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="{{ config('app.name') }}" 
                     class="h-12 w-auto object-contain">
            </div>
        </div>
    </div>

    <!-- Navigation Menu with Custom Scrollbar -->
    <nav class="flex-1 px-4 pb-4 space-y-1 overflow-y-auto custom-scrollbar">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 
                  {{ request()->routeIs('admin.dashboard*') 
                     ? 'bg-white/20 text-white shadow-sm' 
                     : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
            </svg>
            Dashboard
        </a>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}" 
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200
                  {{ request()->routeIs('admin.users*') 
                     ? 'bg-white/20 text-white shadow-sm' 
                     : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
            Users
        </a>

        <!-- Trips Management Dropdown -->
        <div class="space-y-1" x-data="{ 
            isOpen: @js(request()->routeIs('admin.trips*') || request()->routeIs('admin.trip-templates*') || request()->routeIs('admin.destinations*')),
            isActive: @js(request()->routeIs('admin.trips*') || request()->routeIs('admin.trip-templates*') || request()->routeIs('admin.destinations*'))
        }">
            <button @click="isOpen = !isOpen" 
                    class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="isActive ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white'">
                <div class="flex items-center">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Trips Management
                </div>
                <svg class="h-4 w-4 transition-transform duration-200" 
                     :class="{ 'rotate-180': isOpen }" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="ml-6 space-y-1">
                <a href="{{ route('admin.trips.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200
                          {{ request()->routeIs('admin.trips*') && !request()->routeIs('admin.trip-templates*')
                             ? 'bg-white/20 text-white shadow-sm border-l-2 border-white/50' 
                             : 'text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30' }}">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Monitor User Trips
                </a>
                
                <a href="{{ route('admin.trip-templates.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200
                          {{ request()->routeIs('admin.trip-templates*') 
                             ? 'bg-white/20 text-white shadow-sm border-l-2 border-white/50' 
                             : 'text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30' }}">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Trip Templates
                </a>
                
                <a href="{{ route('admin.destinations.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200
                          {{ request()->routeIs('admin.destinations*') 
                             ? 'bg-white/20 text-white shadow-sm border-l-2 border-white/50' 
                             : 'text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30' }}">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Destinations
                </a>
                
                <!-- Divider -->
                <div class="border-t border-white/20 my-2"></div>
                
                <a href="{{ route('admin.trip-templates.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200 text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Template
                </a>
                
                <a href="{{ route('admin.destinations.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200 text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Destination
                </a>
            </div>
        </div>

        <!-- Financial Management Dropdown -->
        <div class="space-y-1" x-data="{ 
            isOpen: @js(request()->routeIs('admin.wallets*') || request()->routeIs('admin.transactions*')),
            isActive: @js(request()->routeIs('admin.wallets*') || request()->routeIs('admin.transactions*'))
        }">
            <button @click="isOpen = !isOpen" 
                    class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="isActive ? 'bg-white/20 text-white shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white'">
                <div class="flex items-center">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Financial
                </div>
                <svg class="h-4 w-4 transition-transform duration-200" 
                     :class="{ 'rotate-180': isOpen }" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            
            <div x-show="isOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="ml-6 space-y-1">
                <a href="{{ route('admin.wallets.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200
                          {{ request()->routeIs('admin.wallets*') 
                             ? 'bg-white/20 text-white shadow-sm border-l-2 border-white/50' 
                             : 'text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30' }}">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Wallets
                </a>
                
                <a href="{{ route('admin.transactions.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-200
                          {{ request()->routeIs('admin.transactions*') 
                             ? 'bg-white/20 text-white shadow-sm border-l-2 border-white/50' 
                             : 'text-white/70 hover:bg-white/10 hover:text-white border-l-2 border-transparent hover:border-white/30' }}">
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Transactions
                </a>
            </div>
        </div>

        <!-- Activity Logs -->
        <a href="{{ route('admin.activities.index') }}" 
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200
                  {{ request()->routeIs('admin.activities*') 
                     ? 'bg-white/20 text-white shadow-sm' 
                     : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Activity Logs
        </a>
    </nav>

    <!-- Logout Section -->
    <div class="flex-shrink-0 px-4 py-4 border-t border-white/20">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" 
                    class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 text-white/80 hover:bg-white/10 hover:text-white">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<style>
/* Custom Professional Scrollbar for Sidebar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    transition: background-color 0.2s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.35);
}

.custom-scrollbar::-webkit-scrollbar-thumb:active {
    background: rgba(255, 255, 255, 0.5);
}

/* Hide scrollbar corner */
.custom-scrollbar::-webkit-scrollbar-corner {
    background: transparent;
}

/* Smooth scrolling behavior */
.custom-scrollbar {
    scroll-behavior: smooth;
}

/* Additional hover effect for better UX */
.custom-scrollbar:hover {
    scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
}

/* For better visual feedback on focus */
.custom-scrollbar:focus-within {
    scrollbar-color: rgba(255, 255, 255, 0.5) transparent;
}
</style>
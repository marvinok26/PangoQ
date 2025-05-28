{{-- resources/views/admin/layouts/breadcrumb.blade.php --}}

@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2">
        <!-- Dashboard Home -->
        <li>
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </div>
        </li>

        @foreach($breadcrumbs as $breadcrumb)
            <li>
                <div class="flex items-center">
                    <!-- Separator -->
                    <svg class="h-4 w-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    
                    @if($loop->last)
                        <!-- Current page (non-clickable) -->
                        <span class="text-sm font-medium text-gray-900" aria-current="page">
                            {{ $breadcrumb['title'] }}
                        </span>
                    @else
                        <!-- Clickable breadcrumb -->
                        <a href="{{ $breadcrumb['url'] }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            {{ $breadcrumb['title'] }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
@endif
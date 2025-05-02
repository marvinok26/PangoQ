{{-- resources/views/components/login-reminder.blade.php --}}
@props(['type' => 'standard', 'message' => 'Save your progress by signing in', 'showOnce' => true])

@guest
<div x-data="{ show: true }" 
     x-init="if({{ json_encode($showOnce) }} && localStorage.getItem('login_reminder_dismissed')) { show = false }"
     x-show="show"
     class="bg-white border rounded-lg shadow-sm {{ $type === 'sticky' ? 'sticky bottom-4 mx-auto max-w-md z-50' : '' }}">
    <div class="p-4 @if($type === 'banner') bg-blue-50 border-blue-100 @endif">
        <div class="flex">
            <div class="flex-shrink-0">
                <!-- User Icon -->
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-gray-900">{{ $message }}</p>
                <div class="mt-2 flex">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}" class="ml-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-secondary-600 bg-secondary-100 hover:bg-secondary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                        Create account
                    </a>
                    @if($showOnce)
                    <button type="button" @click="show = false; localStorage.setItem('login_reminder_dismissed', 'true')" class="ml-auto inline-flex text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endguest
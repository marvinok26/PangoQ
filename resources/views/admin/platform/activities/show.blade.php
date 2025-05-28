{{-- resources/views/admin/platform/activities/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Activity Details')
@section('page-title', 'Activity Log Details')

@php
    $breadcrumbs = [
        ['title' => 'Activity Logs', 'url' => route('admin.activities.index')],
        ['title' => 'Activity #' . $activity->id]
    ];
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Activity Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Activity Information Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-medium text-gray-900">Activity Information</h5>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Activity ID:</span>
                        <code class="text-sm bg-gray-100 px-2 py-1 rounded">#{{ $activity->id }}</code>
                    </div>
                    
                    <div class="flex justify-between items-start py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">User:</span>
                        <div class="text-right">
                            @if($activity->user)
                                <div class="flex items-center justify-end">
                                    <div class="mr-3">
                                        @if($activity->user->profile_photo_path)
                                            <img src="{{ $activity->user->photo_url }}" alt="{{ $activity->user->name }}" 
                                                 class="h-10 w-10 rounded-full border-2 border-white shadow-sm">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                                                {{ $activity->user->initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('admin.users.show', $activity->user) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $activity->user->name }}
                                        </a>
                                        <div class="text-xs text-gray-500">{{ $activity->user->email }}</div>
                                        @if($activity->user->isAdmin())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">Admin</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-500">System</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Action:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ get_action_badge_color($activity->action) }}-100 text-{{ get_action_badge_color($activity->action) }}-800">
                            {{ format_activity_action($activity->action) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-start py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Target:</span>
                        <div class="text-right">
                            @if($activity->model_type)
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ class_basename($activity->model_type) }}</div>
                                        @if($activity->model_id)
                                            <div class="text-xs text-gray-500">#{{ $activity->model_id }}</div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-500">General Action</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-start py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Date & Time:</span>
                        <div class="text-right">
                            <div class="text-sm text-gray-900">{{ format_admin_date($activity->created_at) }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">IP Address:</span>
                        <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $activity->ip_address ?? 'N/A' }}</code>
                    </div>
                    
                    <div class="flex justify-between items-start py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">User Agent:</span>
                        <div class="text-right max-w-md">
                            @if($activity->user_agent)
                                <div class="text-xs text-gray-500 break-all">{{ Str::limit($activity->user_agent, 100) }}</div>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-start py-3">
                        <span class="text-sm font-medium text-gray-500">URL:</span>
                        <div class="text-right max-w-md">
                            @if($activity->url)
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded break-all">{{ $activity->method ?? 'GET' }} {{ $activity->url }}</code>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Activities -->
        @if($relatedActivities && $relatedActivities->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-medium text-gray-900">Related Activities</h5>
                <p class="text-sm text-gray-500">Activities on the same model within 24 hours</p>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($relatedActivities as $relatedActivity)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ get_action_badge_color($relatedActivity->action) }}-100 text-{{ get_action_badge_color($relatedActivity->action) }}-800">
                                        {{ format_activity_action($relatedActivity->action) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($relatedActivity->user)
                                        {{ $relatedActivity->user->name }}
                                    @else
                                        <span class="text-gray-500">System</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $relatedActivity->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Changes Made Card -->
        @if($activity->changes)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-medium text-gray-900">Changes Made</h5>
            </div>
            <div class="p-6">
                @if(is_array($activity->changes))
                    <div class="space-y-3">
                        @foreach($activity->changes as $key => $value)
                        <div class="flex justify-between items-start py-2 border-b border-gray-100 last:border-b-0">
                            <span class="text-sm font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                            <div class="text-right max-w-32">
                                @if(is_array($value))
                                    <button type="button" 
                                            onclick="openValueModal('{{ $loop->index }}', '{{ ucwords(str_replace('_', ' ', $key)) }}', {{ json_encode($value) }})"
                                            class="inline-flex items-center px-2 py-1 border border-blue-300 text-xs font-medium rounded text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        View Array
                                    </button>
                                @else
                                    <span class="text-sm text-gray-900">{{ Str::limit($value, 50) }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-x-auto">{{ json_encode($activity->changes, JSON_PRETTY_PRINT) }}</pre>
                @endif
            </div>
        </div>
        @endif

        <!-- Original Data Card -->
        @if($activity->original_data)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-medium text-gray-900">Original Data</h5>
            </div>
            <div class="p-6">
                @if(is_array($activity->original_data))
                    <div class="space-y-3">
                        @foreach($activity->original_data as $key => $value)
                        <div class="flex justify-between items-start py-2 border-b border-gray-100 last:border-b-0">
                            <span class="text-sm font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                            <div class="text-right max-w-32">
                                @if(is_array($value))
                                    <button type="button" 
                                            onclick="openValueModal('original{{ $loop->index }}', 'Original {{ ucwords(str_replace('_', ' ', $key)) }}', {{ json_encode($value) }})"
                                            class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        View Array
                                    </button>
                                @else
                                    <span class="text-sm text-gray-900">{{ Str::limit($value, 50) }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <pre class="text-xs bg-gray-100 p-3 rounded overflow-x-auto">{{ json_encode($activity->original_data, JSON_PRETTY_PRINT) }}</pre>
                @endif
            </div>
        </div>
        @endif

        <!-- User's Recent Activities -->
        @if($activity->user && $userRecentActivities && $userRecentActivities->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-medium text-gray-900">User's Recent Activities</h5>
                <p class="text-sm text-gray-500">Last 10 activities by {{ $activity->user->name }}</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($userRecentActivities as $recentActivity)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ get_action_badge_color($recentActivity->action) }}-100 text-{{ get_action_badge_color($recentActivity->action) }}-800 mr-2">
                                {{ format_activity_action($recentActivity->action) }}
                            </span>
                            @if($recentActivity->model_type)
                                <span class="text-xs text-gray-500">
                                    {{ class_basename($recentActivity->model_type) }}
                                    @if($recentActivity->model_id)
                                        #{{ $recentActivity->model_id }}
                                    @endif
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">{{ $recentActivity->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Bottom Action Bar -->
<div class="mt-8 flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0">
    <a href="{{ route('admin.activities.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Activities
    </a>
    
    @if($activity->user)
        <a href="{{ route('admin.users.show', $activity->user) }}" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            View User Profile
        </a>
    @endif
</div>

<!-- Value Modal -->
<div id="valueModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 id="valueModalTitle" class="text-lg font-medium text-gray-900"></h3>
                <button type="button" onclick="closeValueModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="valueModalContent" class="max-h-96 overflow-y-auto">
                <!-- Content will be populated by JavaScript -->
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeValueModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openValueModal(id, title, value) {
    const modal = document.getElementById('valueModal');
    const modalTitle = document.getElementById('valueModalTitle');
    const modalContent = document.getElementById('valueModalContent');
    
    modalTitle.textContent = title;
    modalContent.innerHTML = `<pre class="text-sm bg-gray-100 p-4 rounded overflow-x-auto">${JSON.stringify(value, null, 2)}</pre>`;
    
    modal.classList.remove('hidden');
}

function closeValueModal() {
    document.getElementById('valueModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('valueModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeValueModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('valueModal').classList.contains('hidden')) {
        closeValueModal();
    }
});
</script>
@endpush
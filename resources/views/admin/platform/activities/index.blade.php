{{-- resources/views/admin/platform/activities/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'System Activity Logs')

@section('content')
<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 space-y-4 lg:space-y-0">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Activity Logs</h1>
        <p class="text-gray-600">Monitor system activities and user actions</p>
    </div>
    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
        <a href="{{ route('admin.activities.security') }}" class="inline-flex items-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Security Insights
        </a>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Export
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-10 mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-200">
                <div class="py-1">
                    <a href="#" onclick="exportLogs('csv')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Export as CSV</a>
                    <a href="#" onclick="exportLogs('json')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Export as JSON</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Activities</label>
            <form method="GET" action="{{ route('admin.activities.index') }}" class="flex space-x-2">
                <input type="text" 
                       name="search" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Search by action, user, IP..." 
                       value="{{ request('search') }}">
                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Filters</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <div class="flex">
                    <a href="{{ route('admin.activities.index') }}" 
                       class="flex-1 inline-flex justify-center items-center px-3 py-2 text-sm font-medium rounded-l-md border transition-colors duration-200 {{ !request()->hasAny(['action', 'model_type', 'admin_only']) ? 'border-gray-800 bg-gray-800 text-white' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
                        All Activities
                    </a>
                    <a href="{{ route('admin.activities.index', ['admin_only' => '1']) }}" 
                       class="flex-1 inline-flex justify-center items-center px-3 py-2 text-sm font-medium rounded-r-md border-t border-r border-b transition-colors duration-200 {{ request('admin_only') === '1' ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
                        Admin Actions
                    </a>
                </div>
                <div>
                    <form method="GET" action="{{ route('admin.activities.index') }}">
                        @foreach(request()->except('action') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="action" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" onchange="this.form.submit()">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                    {{ format_activity_action($action) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div>
                    <form method="GET" action="{{ route('admin.activities.index') }}">
                        @foreach(request()->except('model_type') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="model_type" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" onchange="this.form.submit()">
                            <option value="">All Models</option>
                            @foreach($modelTypes as $modelType)
                                <option value="{{ $modelType }}" {{ request('model_type') === $modelType ? 'selected' : '' }}>
                                    {{ class_basename($modelType) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="max-w-2xl">
        <form method="GET" action="{{ route('admin.activities.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            @foreach(request()->except(['date_from', 'date_to']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" value="{{ request('date_from') }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" value="{{ request('date_to') }}">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    Filter
                </button>
                <a href="{{ route('admin.activities.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
@if(isset($stats))
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-shadow duration-200">
        <h6 class="text-sm font-medium text-gray-500 mb-1">Total</h6>
        <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_activities']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-shadow duration-200">
        <h6 class="text-sm font-medium text-gray-500 mb-1">Admin</h6>
        <div class="text-2xl font-bold text-blue-500">{{ number_format($stats['admin_activities']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-shadow duration-200">
        <h6 class="text-sm font-medium text-gray-500 mb-1">Users</h6>
        <div class="text-2xl font-bold text-green-600">{{ number_format($stats['unique_users']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-shadow duration-200">
        <h6 class="text-sm font-medium text-gray-500 mb-1">IPs</h6>
        <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['unique_ips']) }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-shadow duration-200">
        <h6 class="text-sm font-medium text-gray-500 mb-1">Today</h6>
        <div class="text-2xl font-bold text-gray-600">{{ number_format($stats['today_activities']) }}</div>
    </div>
</div>
@endif

<!-- Activity Logs Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h5 class="text-lg font-medium text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Activity Logs
            </h5>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ $activities->total() ?? 0 }} total records
            </span>
        </div>
    </div>
    <div class="overflow-hidden">
        @if($activities->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($activity->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 mr-3">
                                            @if($activity->user->profile_photo_path)
                                                <img src="{{ $activity->user->photo_url }}" 
                                                     alt="{{ $activity->user->name }}" 
                                                     class="h-8 w-8 rounded-full border-2 border-white shadow-sm">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                                                    {{ $activity->user->initials }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.users.show', $activity->user) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $activity->user->name }}
                                            </a>
                                            @if($activity->user->isAdmin())
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Admin</span>
                                            @endif
                                            <div class="text-xs text-gray-500">{{ $activity->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center text-white mr-3">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-500">System</span>
                                            <div class="text-xs text-gray-500">Automated action</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ get_action_badge_color($activity->action) }}-100 text-{{ get_action_badge_color($activity->action) }}-800">
                                    {{ format_activity_action($activity->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                    <span class="text-gray-500">General</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($activity->changes)
                                    <button type="button" 
                                            onclick="openChangesModal('{{ $activity->id }}')"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Changes
                                    </button>
                                @else
                                    <span class="text-gray-500">No changes</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($activity->ip_address)
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $activity->ip_address }}</code>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $activity->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->created_at->format('M j, Y g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.activities.show', $activity) }}" 
                                   class="inline-flex items-center p-1.5 border border-blue-300 rounded text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} 
                    of {{ $activities->total() }} results
                </div>
                <div>
                    {{ $activities->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h4 class="mt-4 text-lg font-medium text-gray-900">No activity logs found</h4>
                <p class="mt-2 text-gray-600">Try adjusting your search criteria or check back later.</p>
                <a href="{{ route('admin.activities.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </a>
            </div>
        @endif
    </div>
</div>
<!-- Changes Modal -->
<div id="changesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 id="changesModalTitle" class="text-lg font-medium text-gray-900"></h3>
                <button type="button" onclick="closeChangesModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="changesContent">
                <!-- Content will be populated by JavaScript -->
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeChangesModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const activities = {
    @foreach($activities as $activity)
        {{ $activity->id }}: {
            id: {{ $activity->id }},
            action: @json(format_activity_action($activity->action)),
            model_type: @json($activity->model_type ? class_basename($activity->model_type) : 'General'),
            created_at: @json($activity->created_at->format('M j, Y g:i:s A')),
            changes: @json($activity->changes)
        }@if(!$loop->last),@endif
    @endforeach
};

function openChangesModal(activityId) {
    const activity = activities[activityId];
    if (!activity || !activity.changes) return;
    
    const modal = document.getElementById('changesModal');
    const title = document.getElementById('changesModalTitle');
    const content = document.getElementById('changesContent');
    
    title.textContent = `Activity Changes - ${activity.action}`;
    
    let changesHtml = `
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div><strong>Action:</strong> ${activity.action}</div>
                <div><strong>Target:</strong> ${activity.model_type}</div>
                <div><strong>Time:</strong> ${activity.created_at}</div>
            </div>
        </div>
        <hr class="my-4">
    `;
    
    if (typeof activity.changes === 'object' && activity.changes !== null) {
        changesHtml += `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;
        
        Object.entries(activity.changes).forEach(([key, value]) => {
            const formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            let formattedValue;
            
            if (typeof value === 'object') {
                formattedValue = `<pre class="text-xs bg-gray-100 p-2 rounded max-h-32 overflow-y-auto">${JSON.stringify(value, null, 2)}</pre>`;
            } else {
                formattedValue = `<span class="text-sm">${value}</span>`;
            }
            
            changesHtml += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${formattedKey}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${formattedValue}</td>
                </tr>
            `;
        });
        
        changesHtml += `
                    </tbody>
                </table>
            </div>
        `;
    } else {
        changesHtml += `<pre class="text-sm bg-gray-100 p-4 rounded overflow-x-auto">${JSON.stringify(activity.changes, null, 2)}</pre>`;
    }
    
    content.innerHTML = changesHtml;
    modal.classList.remove('hidden');
}

function closeChangesModal() {
    document.getElementById('changesModal').classList.add('hidden');
}

function exportLogs(format) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.activities.export") }}';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add format
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    // Add current filters
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.forEach((value, key) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Auto-refresh functionality (optional)
let autoRefresh = false;
let refreshInterval;

function toggleAutoRefresh() {
    autoRefresh = !autoRefresh;
    if (autoRefresh) {
        refreshInterval = setInterval(() => {
            window.location.reload();
        }, 30000); // Refresh every 30 seconds
    } else {
        clearInterval(refreshInterval);
    }
}

// Close modal when clicking outside
document.getElementById('changesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChangesModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('changesModal').classList.contains('hidden')) {
        closeChangesModal();
    }
});
</script>
@endpush
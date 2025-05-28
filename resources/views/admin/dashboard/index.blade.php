{{-- resources/views/admin/dashboard/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your platform today.')

@section('content')
<div class="h-full flex flex-col space-y-6">
    <!-- Stats Cards Row - Fixed Height -->
    <div class="flex-shrink-0">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Total Users Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Total Users</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_users']) }}</p>
                            <div class="flex items-center mt-2 text-blue-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                                </svg>
                                <span class="text-xs">+12%</span>
                            </div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Trips Card -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Total Trips</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_trips']) }}</p>
                            <div class="flex items-center mt-2 text-purple-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                                </svg>
                                <span class="text-xs">+8%</span>
                            </div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Wallets Card -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-cyan-100 text-xs font-medium uppercase tracking-wide">Total Wallets</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_wallets']) }}</p>
                            <div class="flex items-center mt-2 text-cyan-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                                </svg>
                                <span class="text-xs">+15%</span>
                            </div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Transactions Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-emerald-100 text-xs font-medium uppercase tracking-wide">Transactions</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_transactions']) }}</p>
                            <div class="flex items-center mt-2 text-emerald-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                                </svg>
                                <span class="text-xs">+23%</span>
                            </div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid - Flexible Height -->
    <div class="flex-1 min-h-0">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-full">
            <!-- Recent Activities - 2.5/4 columns -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col">
                    <div class="flex-shrink-0 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Admin Activities</h3>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1"></span>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 p-6 overflow-hidden">
                        @if($stats['recent_activities']->count() > 0)
                            <div class="h-full overflow-y-auto pr-2 space-y-3">
                                @foreach($stats['recent_activities']->take(12) as $activity)
                                <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 border border-gray-100">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium text-xs">
                                            {{ strtoupper(substr($activity->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $activity->user->name ?? 'System' }}
                                            </p>
                                            <p class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                                            </span>
                                            <span class="text-xs text-gray-500 truncate">
                                                {{ $activity->model_type ? class_basename($activity->model_type) : 'System' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No activities yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Admin activities will appear here.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar - 1.5/4 columns -->
            <div class="lg:col-span-1">
                <div class="h-full flex flex-col space-y-4">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-shrink-0">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">Quick Stats</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Active Users</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($stats['active_users']) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Admin Users</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ number_format($stats['admin_users']) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Active Trips</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ number_format($stats['active_trips']) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Flagged Trips</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ number_format($stats['flagged_trips']) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Featured Trips</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($stats['featured_trips']) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-gray-600">Flagged Wallets</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ number_format($stats['flagged_wallets']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-shrink-0">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-2">
                                <a href="{{ route('admin.users.index') }}" 
                                   class="flex items-center justify-between w-full px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 group">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-200">
                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                        </div>
                                        <span class="ml-2">Manage Users</span>
                                    </div>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                <a href="{{ route('admin.trips.index') }}" 
                                   class="flex items-center justify-between w-full px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 group">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-6 h-6 bg-purple-100 rounded-md flex items-center justify-center group-hover:bg-purple-200 transition-colors duration-200">
                                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                        </div>
                                        <span class="ml-2">Review Trips</span>
                                    </div>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                <a href="{{ route('admin.activities.index') }}" 
                                   class="flex items-center justify-between w-full px-3 py-2 text-xs font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-md hover:bg-gray-100 hover:border-gray-300 transition-all duration-200 group">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center group-hover:bg-gray-200 transition-colors duration-200">
                                            <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <span class="ml-2">Activity Logs</span>
                                    </div>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-1">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">System Status</h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        <span class="text-xs font-medium text-gray-600">Database</span>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">Online</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        <span class="text-xs font-medium text-gray-600">API Services</span>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">Online</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                        <span class="text-xs font-medium text-gray-600">Email Service</span>
                                    </div>
                                    <span class="text-xs text-yellow-600 font-medium">Degraded</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                        <span class="text-xs font-medium text-gray-600">Payment Gateway</span>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
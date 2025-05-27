<?php

if (!function_exists('admin_route')) {
    /**
     * Generate admin route URL
     */
    function admin_route(string $name, array $params = []): string
    {
        return route('admin.' . $name, $params);
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin
     */
    function is_admin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

if (!function_exists('admin_user')) {
    /**
     * Get current admin user
     */
    function admin_user(): ?\App\Models\User
    {
        return auth()->check() && auth()->user()->isAdmin() ? auth()->user() : null;
    }
}

if (!function_exists('can_manage_users')) {
    /**
     * Check if user can manage other users  
     */
    function can_manage_users(): bool
    {
        return auth()->check() && auth()->user()->canManageUsers();
    }
}

if (!function_exists('can_manage_trips')) {
    /**
     * Check if user can manage trips
     */
    function can_manage_trips(): bool
    {
        return auth()->check() && auth()->user()->canManageTrips();
    }
}

if (!function_exists('can_manage_financials')) {
    /**
     * Check if user can manage financial operations
     */
    function can_manage_financials(): bool
    {
        return auth()->check() && auth()->user()->canManageFinancials();
    }
}

if (!function_exists('format_admin_date')) {
    /**
     * Format date for admin display
     */
    function format_admin_date($date, string $format = 'M j, Y g:i A'): string
    {
        if (!$date) return 'N/A';
        return $date instanceof \Carbon\Carbon ? $date->format($format) : \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('admin_status_badge')) {
    /**
     * Get Bootstrap badge class for admin status
     */
    function admin_status_badge(string $status): string
    {
        return match($status) {
            'approved' => 'badge bg-success',
            'under_review' => 'badge bg-warning',
            'flagged' => 'badge bg-danger',
            'restricted' => 'badge bg-dark',
            'active' => 'badge bg-success',
            'inactive' => 'badge bg-secondary',
            'suspended' => 'badge bg-danger',
            'pending' => 'badge bg-warning',
            'completed' => 'badge bg-success',
            'failed' => 'badge bg-danger',
            default => 'badge bg-secondary'
        };
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency for admin display - FIXED
     */
    function format_currency(float $amount, string $currency = 'USD'): string
    {
        switch ($currency) {
            case 'USD':
                return '$' . number_format($amount, 2);
            case 'KES':
                return 'KSh ' . number_format($amount, 2);
            case 'EUR':
                return '€' . number_format($amount, 2);
            case 'GBP':
                return '£' . number_format($amount, 2);
            default:
                return $currency . ' ' . number_format($amount, 2);
        }
    }
}

if (!function_exists('admin_breadcrumb')) {
    /**
     * Generate breadcrumb for admin pages
     */
    function admin_breadcrumb(array $items): string
    {
        $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        
        foreach ($items as $key => $item) {
            if ($key === array_key_last($items)) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">' . $item . '</li>';
            } else {
                if (is_array($item)) {
                    $html .= '<li class="breadcrumb-item"><a href="' . $item['url'] . '">' . $item['label'] . '</a></li>';
                } else {
                    $html .= '<li class="breadcrumb-item">' . $item . '</li>';
                }
            }
        }
        
        $html .= '</ol></nav>';
        return $html;
    }
}

// Activity Log Helpers
if (!function_exists('get_action_badge_color')) {
    /**
     * Get Bootstrap badge color for activity actions
     */
    function get_action_badge_color(string $action): string
    {
        $colors = [
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            'login' => 'primary',
            'logout' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'flagged' => 'warning',
            'admin_login' => 'info',
            'admin_logout' => 'secondary',
            'failed' => 'danger',
            'blocked' => 'dark',
            'suspended' => 'warning',
            'restored' => 'success',
            'exported' => 'info',
            'imported' => 'primary',
        ];
        
        foreach ($colors as $keyword => $color) {
            if (str_contains(strtolower($action), $keyword)) {
                return $color;
            }
        }
        
        return 'secondary';
    }
}

if (!function_exists('format_activity_action')) {
    /**
     * Format activity action for display
     */
    function format_activity_action(string $action): string
    {
        return ucwords(str_replace('_', ' ', $action));
    }
}

if (!function_exists('get_model_icon')) {
    /**
     * Get icon for model type
     */
    function get_model_icon(string $modelType): string
    {
        $icons = [
            'User' => 'bi-person',
            'TripTemplate' => 'bi-map',
            'Destination' => 'bi-geo-alt',
            'TemplateActivity' => 'bi-list-check',
            'Trip' => 'bi-airplane',
            'Booking' => 'bi-calendar-check',
            'Payment' => 'bi-credit-card',
            'Review' => 'bi-star',
            'Message' => 'bi-chat',
            'ActivityLog' => 'bi-activity',
        ];
        
        $basename = class_basename($modelType);
        return $icons[$basename] ?? 'bi-file';
    }
}

// NEW HELPERS FOR TRIP TEMPLATE MANAGEMENT

if (!function_exists('activity_time_badge')) {
    /**
     * Get Bootstrap badge class for activity time of day
     */
    function activity_time_badge(string $timeOfDay): string
    {
        return match($timeOfDay) {
            'morning' => 'badge bg-info',
            'afternoon' => 'badge bg-warning',
            'evening' => 'badge bg-dark',
            default => 'badge bg-secondary'
        };
    }
}

if (!function_exists('difficulty_badge')) {
    /**
     * Get Bootstrap badge class for difficulty level
     */
    function difficulty_badge(string $level): string
    {
        return match($level) {
            'easy' => 'badge bg-success',
            'moderate' => 'badge bg-warning',
            'challenging' => 'badge bg-danger',
            default => 'badge bg-secondary'
        };
    }
}

if (!function_exists('trip_style_badge')) {
    /**
     * Get Bootstrap badge class for trip style
     */
    function trip_style_badge(string $style): string
    {
        return match(strtolower($style)) {
            'safari' => 'badge bg-success',
            'cultural' => 'badge bg-info',
            'adventure' => 'badge bg-danger',
            'beach' => 'badge bg-primary',
            'luxury' => 'badge bg-warning',
            'budget' => 'badge bg-secondary',
            default => 'badge bg-light text-dark'
        };
    }
}

if (!function_exists('template_completeness_badge')) {
    /**
     * Get Bootstrap badge class for template completeness score
     */
    function template_completeness_badge(int $score): string
    {
        return match(true) {
            $score >= 90 => 'badge bg-success',
            $score >= 70 => 'badge bg-warning',
            $score >= 50 => 'badge bg-danger',
            default => 'badge bg-dark'
        };
    }
}

if (!function_exists('activity_category_icon')) {
    /**
     * Get Bootstrap icon for activity category
     */
    function activity_category_icon(string $category): string
    {
        return match(strtolower($category)) {
            'safari', 'wildlife' => 'bi-binoculars',
            'cultural', 'culture' => 'bi-building',
            'adventure', 'sports' => 'bi-activity',
            'food', 'dining' => 'bi-cup-hot',
            'beach', 'water' => 'bi-water',
            'shopping' => 'bi-bag',
            'transport', 'transfer' => 'bi-car-front',
            'accommodation' => 'bi-house',
            'entertainment' => 'bi-music-note',
            'nature' => 'bi-tree',
            'historical' => 'bi-clock-history',
            'religious' => 'bi-brightness-high',
            default => 'bi-calendar-event'
        };
    }
}

if (!function_exists('format_duration')) {
    /**
     * Format duration from minutes to human readable
     */
    function format_duration(int $minutes): string
    {
        if ($minutes < 60) {
            return "{$minutes} min";
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes === 0) {
            return "{$hours} hr" . ($hours > 1 ? 's' : '');
        }
        
        return "{$hours}h {$remainingMinutes}m";
    }
}

if (!function_exists('calculate_activity_duration')) {
    /**
     * Calculate duration between start and end time
     */
    function calculate_activity_duration(string $startTime, string $endTime): int
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);
        
        return $start->diffInMinutes($end);
    }
}

if (!function_exists('get_time_of_day_from_time')) {
    /**
     * Determine time of day from time string
     */
    function get_time_of_day_from_time(string $time): string
    {
        $hour = (int) \Carbon\Carbon::parse($time)->format('H');
        
        return match(true) {
            $hour >= 5 && $hour < 12 => 'morning',
            $hour >= 12 && $hour < 18 => 'afternoon',
            default => 'evening'
        };
    }
}

if (!function_exists('format_time_range')) {
    /**
     * Format time range for display
     */
    function format_time_range(string $startTime, string $endTime): string
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);
        
        return $start->format('g:i A') . ' - ' . $end->format('g:i A');
    }
}

if (!function_exists('get_trip_template_status_badge')) {
    /**
     * Get status badge for trip template based on completeness
     */
    function get_trip_template_status_badge(\App\Models\TripTemplate $template): string
    {
        $activitiesCount = $template->activities()->count();
        
        if ($activitiesCount === 0) {
            return '<span class="badge bg-danger">Incomplete</span>';
        }
        
        if ($template->is_featured) {
            return '<span class="badge bg-warning"><i class="bi bi-star-fill"></i> Featured</span>';
        }
        
        return '<span class="badge bg-success">Active</span>';
    }
}
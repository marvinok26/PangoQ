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
     * Format currency for admin display
     */
    function format_currency(float $amount, string $currency = 'USD'): string
    {
        return match($currency) {
            'USD' => '$' . number_format($amount, 2),
            'KES' => 'KSh ' . number_format($amount, 2),
            'EUR' => '€' . number_format($amount, 2),
            'GBP' => '£' . number_format($amount, 2),
            default => $currency . ' ' . number_format($amount, 2)
        };
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
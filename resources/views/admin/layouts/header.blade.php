{{-- Update admin/layouts/header.blade.php --}}

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <small class="text-muted">Last login: {{ format_admin_date(auth()->user()->updated_at) }}</small>
        </div>
    </div>
</div>
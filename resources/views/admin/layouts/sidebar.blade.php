{{-- resources/views/admin/layouts/sidebar.blade.php --}}

<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <div class="logo-container mb-2">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="{{ config('app.name') }}" 
                     class="admin-logo img-fluid">
            </div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2"></i>Users
                </a>
            </li>
            
            <!-- Trips Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.trips*') || request()->routeIs('admin.trip-templates*') || request()->routeIs('admin.destinations*') ? 'active' : '' }}" 
                   href="#" 
                   role="button" 
                   data-bs-toggle="dropdown" 
                   aria-expanded="{{ request()->routeIs('admin.trips*') || request()->routeIs('admin.trip-templates*') || request()->routeIs('admin.destinations*') ? 'true' : 'false' }}">
                    <i class="bi bi-map me-2"></i>Trips Management
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('admin.trips*') ? 'active' : '' }}" 
                           href="{{ route('admin.trips.index') }}">
                            <i class="bi bi-eye me-2"></i>Monitor User Trips
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('admin.trip-templates*') ? 'active' : '' }}" 
                           href="{{ route('admin.trip-templates.index') }}">
                            <i class="bi bi-collection me-2"></i>Trip Templates
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('admin.destinations*') ? 'active' : '' }}" 
                           href="{{ route('admin.destinations.index') }}">
                            <i class="bi bi-geo-alt me-2"></i>Destinations
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" 
                           href="{{ route('admin.trip-templates.create') }}">
                            <i class="bi bi-plus-circle me-2"></i>Create Template
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                           href="{{ route('admin.destinations.create') }}">
                            <i class="bi bi-plus-square me-2"></i>Add Destination
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Financial Management -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.wallets*') || request()->routeIs('admin.transactions*') ? 'active' : '' }}" 
                   href="#" 
                   role="button" 
                   data-bs-toggle="dropdown" 
                   aria-expanded="{{ request()->routeIs('admin.wallets*') || request()->routeIs('admin.transactions*') ? 'true' : 'false' }}">
                    <i class="bi bi-currency-dollar me-2"></i>Financial
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('admin.wallets*') ? 'active' : '' }}" 
                           href="{{ route('admin.wallets.index') }}">
                            <i class="bi bi-wallet2 me-2"></i>Wallets
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}" 
                           href="{{ route('admin.transactions.index') }}">
                            <i class="bi bi-credit-card me-2"></i>Transactions
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.activities*') ? 'active' : '' }}" 
                   href="{{ route('admin.activities.index') }}">
                    <i class="bi bi-activity me-2"></i>Activity Logs
                </a>
            </li>
            
            <hr class="text-white-50">
            <li class="nav-item">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<style>
.admin-logo {
    max-height: 60px;
    max-width: 120px;
    height: auto;
    width: auto;
    object-fit: contain;
}

.logo-container {
    padding: 0.5rem;
}

/* Logo container styling */
.logo-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
}

/* Sidebar dropdown styling - Custom implementation */
.sidebar .nav-item.dropdown {
    position: relative;
}

.sidebar .dropdown-menu {
    position: static !important;
    transform: none !important;
    display: none;
    width: 100%;
    margin: 0;
    padding: 0;
    background-color: rgba(0, 0, 0, 0.15);
    border: none;
    border-radius: 0;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}

.sidebar .dropdown.show .dropdown-menu {
    display: block;
    max-height: 300px; /* Adjust based on content */
    overflow: visible;
}

.sidebar .dropdown-item {
    color: rgba(255, 255, 255, 0.8);
    padding: 0.5rem 1.5rem;
    font-size: 0.85rem;
    border: none;
    background: none;
    text-decoration: none;
    display: block;
    width: 100%;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.sidebar .dropdown-item:hover,
.sidebar .dropdown-item:focus {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
    border-left-color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
}

.sidebar .dropdown-item.active {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.2);
    border-left-color: #fff;
    font-weight: 500;
}

.sidebar .nav-link.dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.sidebar .nav-link.dropdown-toggle::after {
    display: none; /* Hide default Bootstrap caret */
}

.sidebar .nav-link.dropdown-toggle .bi-chevron-down {
    transition: transform 0.2s ease-in-out;
    font-size: 0.8rem;
    opacity: 0.7;
}

.sidebar .dropdown.show .nav-link.dropdown-toggle .bi-chevron-down {
    transform: rotate(180deg);
    opacity: 1;
}

/* Dropdown divider styling */
.sidebar .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.15);
    margin: 0.25rem 0;
}

/* Active state for parent dropdown */
.sidebar .nav-link.dropdown-toggle.active {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
}

/* Ensure proper stacking */
.sidebar {
    z-index: 1020;
}

.sidebar .dropdown-menu {
    z-index: 1021;
}

/* Responsive behavior */
@media (max-width: 767.98px) {
    .sidebar .dropdown-menu {
        background-color: rgba(0, 0, 0, 0.25);
        margin-left: 0.5rem;
    }
    
    .sidebar .dropdown-item {
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
// Custom dropdown functionality for sidebar (without Bootstrap dropdown JS)
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.sidebar .dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            const menu = dropdown.querySelector('.dropdown-menu');
            const isCurrentlyOpen = dropdown.classList.contains('show');
            
            // Close all other dropdowns first
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    const otherDropdown = otherToggle.closest('.dropdown');
                    otherDropdown.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            if (isCurrentlyOpen) {
                dropdown.classList.remove('show');
            } else {
                dropdown.classList.add('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.sidebar .dropdown')) {
            dropdownToggles.forEach(toggle => {
                const dropdown = toggle.closest('.dropdown');
                dropdown.classList.remove('show');
            });
        }
    });
    
    // Auto-expand active dropdown on page load
    function expandActiveDropdown() {
        // Check for active dropdown items
        const activeDropdownItem = document.querySelector('.sidebar .dropdown-item.active');
        if (activeDropdownItem) {
            const parentDropdown = activeDropdownItem.closest('.dropdown');
            if (parentDropdown) {
                parentDropdown.classList.add('show');
            }
        }
        
        // Check for active dropdown toggles
        const activeDropdownToggle = document.querySelector('.sidebar .nav-link.dropdown-toggle.active');
        if (activeDropdownToggle) {
            const parentDropdown = activeDropdownToggle.closest('.dropdown');
            if (parentDropdown && !document.querySelector('.sidebar .dropdown-item.active')) {
                parentDropdown.classList.add('show');
            }
        }
    }
    
    // Initialize active states
    expandActiveDropdown();
});
</script>
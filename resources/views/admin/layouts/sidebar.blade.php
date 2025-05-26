<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <h4 class="text-white">{{ config('app.name') }}</h4>
            <small class="text-white-50">{{ auth()->user()->name }}</small>
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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.trips*') ? 'active' : '' }}" 
                   href="{{ route('admin.trips.index') }}">
                    <i class="bi bi-map me-2"></i>Trips
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.wallets*') ? 'active' : '' }}" 
                   href="{{ route('admin.wallets.index') }}">
                    <i class="bi bi-wallet2 me-2"></i>Wallets
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}" 
                   href="{{ route('admin.transactions.index') }}">
                    <i class="bi bi-credit-card me-2"></i>Transactions
                </a>
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
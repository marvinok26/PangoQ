{{-- resources/views/admin/auth/login.blade.php --}}

@extends('admin.layouts.auth')

@section('title', 'Admin Login')
@section('auth-title', 'Admin Login')

@section('content')
<form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    
    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label text-white">Email Address</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
               name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label text-white">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
               name="password" required autocomplete="current-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label text-white" for="remember">
            Remember me
        </label>
    </div>

    <!-- Submit Button -->
    <div class="d-grid">
        <button type="submit" class="btn btn-light btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Admin Panel
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <small class="text-white-50">
        Admin access only. Unauthorized access is prohibited.
    </small>
</div>
@endsection
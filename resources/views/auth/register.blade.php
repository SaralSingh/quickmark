@extends('layouts.guest')

@section('title', 'Register | QuickMark')

@push('head')
<style>
    main {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 2rem 0;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-center">
        <div class="auth-card p-4 p-md-5">
            
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="text-dark text-decoration-none fs-4 fw-bold">
                    <i class="fa-solid fa-check-double text-primary me-2"></i> QuickMark
                </a>
                <p class="text-secondary mt-2 mb-0">Create your account to start tracking.</p>
            </div>

            <div id="general-error" class="alert alert-danger d-none text-center small" role="alert"></div>

            <form id="registerForm">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" placeholder="John Doe">
                    <label for="name">Full Name</label>
                    <div class="invalid-feedback" id="error-name"></div>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com">
                    <label for="email">Email Address</label>
                    <div class="invalid-feedback" id="error-email"></div>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Password">
                    <label for="password">Password</label>
                    <div class="invalid-feedback" id="error-password"></div>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password">
                    <label for="password_confirmation">Confirm Password</label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                        Create Account
                    </button>
                </div>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <p class="small text-secondary mb-0">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-dark">Log in</a>
                </p>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/inlog.css') }}">
@endpush

@section('title', 'Inloggen')

@section('content')
    <div class="main-content">
        <div class="login-container">
            <h1>Inloggen</h1>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" required placeholder="naam@voorbeeld.nl" value="{{ old('email') }}">
                    @error('email')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                    @error('password')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                @if($errors->any())
                    <div class="alert alert-error" style="background: var(--color-error-bg); color: var(--color-error-text); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit" id="loginBtn">Inloggen</button>
            </form>

            <div class="register-link">
                <a href="{{ route('register.form') }}">Nog geen account? Registreer hier</a>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script>
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('loginBtn');
            
            form.addEventListener('submit', () => {
                LoadingState.setButtonLoading(submitBtn, true);
            });
            
            @if(session('success'))
                Toast.success("{{ session('success') }}");
            @endif
            
            @if(session('error'))
                Toast.error("{{ session('error') }}");
            @endif
        </script>
    @endpush
@endsection

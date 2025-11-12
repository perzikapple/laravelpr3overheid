@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('title', 'Registreren')

@section('content')
    <div class="auth-wrapper">
        <div class="card">
            <h1>Maak een account</h1>

            {{-- Validatiefouten weergeven --}}
            @if ($errors->any())
                <div class="errors" style="background: var(--color-error-bg); color: var(--color-error-text); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <strong>⚠️ Let op:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" id="registerForm">
                @csrf

                <div class="form-group">
                    <label for="name">Naam</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus placeholder="Volledige naam" />
                    @error('name')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="naam@voorbeeld.nl" />
                    @error('email')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input id="password" name="password" type="password" minlength="8" required placeholder="Minimaal 8 tekens" />
                    @error('password')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                    <small style="color: var(--color-text-muted); display: block; margin-top: 0.25rem;">Minimaal 8 tekens</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Bevestig wachtwoord</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required placeholder="Herhaal wachtwoord" />
                </div>

                <button type="submit" class="btn" id="registerBtn">Registreren</button>
            </form>

            <p class="muted" style="text-align: center; margin-top: 1.5rem; color: var(--color-text-secondary);">
                Heb je al een account? <a href="{{ route('login') }}" style="color: var(--color-primary); text-decoration: none;">Inloggen</a>
            </p>
        </div>
    </div>
    
    @push('scripts')
        <script>
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('registerBtn');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirmation');
            
            form.addEventListener('submit', (e) => {
                if (password.value !== passwordConfirm.value) {
                    e.preventDefault();
                    Toast.error('Wachtwoorden komen niet overeen');
                    return;
                }
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

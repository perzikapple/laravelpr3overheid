@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'Registreren - Gemeente Rotterdam')
@section('breadcrumb', 'Account aanmaken')

@section('content')
    <div class="gov-auth-container">
        <h1>Account aanmaken</h1>
        <p>Maak een account aan om meldingen te doen en de status te volgen.</p>

        {{-- Validatiefouten weergeven --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>⚠️ Let op:</strong>
                <ul>
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
                <small>Minimaal 8 tekens</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Bevestig wachtwoord</label>
                <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required placeholder="Herhaal wachtwoord" />
            </div>

            <button type="submit" class="btn" id="registerBtn">Registreren</button>
        </form>

        <p class="auth-footer">
            Heb je al een account? <a href="{{ route('login') }}">Inloggen</a>
        </p>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('registerBtn');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        
        form.addEventListener('submit', (e) => {
            if (password.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Wachtwoorden komen niet overeen');
                return;
            }
        });
    </script>
@endpush

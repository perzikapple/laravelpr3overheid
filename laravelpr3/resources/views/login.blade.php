@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'Inloggen - Gemeente Rotterdam')
@section('breadcrumb', 'Inloggen')

@section('content')
    <div class="gov-auth-container">
        <h1>Inloggen</h1>
        <p>Log in om uw meldingen te bekijken en te beheren.</p>

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
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="btn" id="loginBtn">Inloggen</button>
        </form>

        <p class="auth-footer">
            Nog geen account? <a href="{{ route('register.form') }}">Registreer hier</a>
        </p>
    </div>
@endsection

@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('title', 'Inloggen')

@section('content')
    <div class="main-content">
        <div class="login-container">
            <h1>Inloggen</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Inloggen</button>
            </form>

            <div class="register-link">
                <a href="{{ route('register.form') }}">Nog geen account? Registreer hier</a>
            </div>
        </div>
    </div>
@endsection

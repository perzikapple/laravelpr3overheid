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
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <label for="name">Naam</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />

                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required />

                <label for="password">Wachtwoord</label>
                <input id="password" name="password" type="password" minlength="8" required />

                <label for="password_confirmation">Bevestig wachtwoord</label>
                <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required />

                <button type="submit" class="btn">Registreren</button>
            </form>

            <p class="muted">
                Heb je al een account? <a href="{{ route('login') }}">Inloggen</a>
            </p>
        </div>
    </div>
@endsection

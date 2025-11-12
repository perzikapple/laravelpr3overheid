@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bedankt.css') }}">
@endpush

@section('title', 'Bedankt')

@section('content')
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 4rem auto; padding: 3rem; background: var(--color-white); border-radius: 16px; box-shadow: var(--shadow-lg);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h1 style="color: var(--color-success); margin-bottom: 1rem;">Bedankt voor je melding!</h1>
            <p style="color: var(--color-text-secondary); font-size: 1.125rem; margin-bottom: 2rem;">
                We hebben je melding goed ontvangen en gaan ermee aan de slag. Je ontvangt een bevestiging als we updates hebben.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <form action="{{ route('home') }}" style="display: inline;">
                    <button type="submit" style="background: var(--color-primary); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; transition: all 0.2s;">
                        ➕ Nog een melding maken
                    </button>
                </form>
                
                <a href="{{ route('home') }}" style="display: inline-block; padding: 0.75rem 1.5rem; border: 2px solid var(--color-border); border-radius: 8px; text-decoration: none; color: var(--color-text-primary); transition: all 0.2s;">
                    🏠 Terug naar home
                </a>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script>
            // Auto-show success toast
            Toast.success('Melding succesvol verstuurd!');
        </script>
    @endpush
@endsection

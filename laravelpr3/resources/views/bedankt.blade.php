@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bedankt.css') }}">
@endpush

@section('title', 'Bedankt')

@section('content')
    <div class="container">
        <h1>Bedankt voor je melding!</h1>
        <p>We hebben je melding goed ontvangen en gaan ermee aan de slag.</p>

        <form action="{{ route('home') }}">
            <button type="submit">Nog een melding maken?</button>
        </form>
    </div>
@endsection

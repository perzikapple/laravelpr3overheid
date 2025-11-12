@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@section('title', 'Melding doen')

    @section('content')
        <div class="container">
            <h2>Doe een melding</h2>

            <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
                @csrf

                <label for="description">Beschrijving</label>
                <textarea name="description" required></textarea>

                <label for="email">E-mail (optioneel)</label>
                <input type="email" name="email">

                <label for="phone">Telefoonnummer (optioneel)</label>
                <input type="text" name="phone">

                <label for="photo">Foto (optioneel)</label>
                <input type="file" name="photo" accept="image/*">

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <button type="button" id="locateBtn">Gebruik mijn locatie</button>
                <button type="submit">Verstuur Melding</button>
            </form>

            <div id="map" style="height: 350px; margin-top: 24px; border-radius: 8px;"></div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="{{ asset('js/home.js') }}"></script>
    @endpush

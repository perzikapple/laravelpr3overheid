<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Melding doen</title>

    <link rel="stylesheet" href="/css/homepage.css">


</head>
<body>

<div class="navbar">
    <a href="{{ route('login') }}">Login</a>
    @auth
        @if (Auth::user()->admin)
            <a href="{{ route('admin') }}">Adminpagina</a>
        @endif
    @endauth
</div>

<div class="container">
    <h2>Doe een melding</h2>

    <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
        @csrf

        <textarea name="description" required></textarea>

        <input type="file" name="photo" accept="image/*">

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <button type="button" id="locateBtn">Use my location</button>
        <button type="submit">Verstuur melding</button>
    </form>


    <div id="map" style="height: 350px; margin-top: 24px; border-radius: 8px;"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="/js/home.js"></script>

</body>
</html>

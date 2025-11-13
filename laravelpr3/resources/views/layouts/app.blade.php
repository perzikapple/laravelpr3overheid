<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Gemeente Rotterdam - Meldingen')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    @stack('styles')
</head>
<body>

<!-- Rijksoverheid-stijl header balk -->
<div class="top-bar">
    <div class="top-bar-content">
        <span class="top-bar-text">Officiële website van Gemeente Rotterdam</span>
        <div class="top-bar-links">
            <a href="https://www.rotterdam.nl/contact" class="top-bar-link">Contact</a>
            <a href="https://www.rotterdam.nl/help" class="top-bar-link">Help</a>
        </div>
    </div>
</div>

<nav class="main-nav">
    <div class="nav-content">
        <a href="{{ route('home') }}" class="logo-section">
            <img src="{{ asset('share-image-rotterdam.jpg') }}" alt="Logo Gemeente Rotterdam" class="logo-img">
            <div class="logo-divider">
                <span class="logo-title">Gemeente Rotterdam</span>
                <span class="logo-subtitle">Meldingen openbare ruimte</span>
            </div>
        </a>

        <div class="nav-links">
            @auth
                @if (Auth::user()->admin == 1 || Auth::user()->admin === true)
                    <a href="{{ route('admin') }}" class="nav-link">Beheer</a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Uitloggen</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Inloggen</a>
                <a href="{{ route('register.form') }}" class="nav-link btn-style">Registreren</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="breadcrumb-content">
        <nav class="breadcrumb-nav">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">@yield('breadcrumb', 'Melding maken')</span>
        </nav>
    </div>
</div>

<main class="main-content">
    @yield('content')
</main>

<!-- Footer -->
<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-grid">
            <div class="footer-section">
                <h4>Contact</h4>
                <p>
                    <strong>14 010</strong><br>
                    Bereikbaar ma-vr 08:00-18:00
                </p>
            </div>
            <div class="footer-section">
                <h4>Informatie</h4>
                <ul class="footer-list">
                    <li><a href="#" class="footer-link">Over deze website</a></li>
                    <li><a href="#" class="footer-link">Privacy</a></li>
                    <li><a href="#" class="footer-link">Toegankelijkheid</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Volg ons</h4>
                <ul class="footer-list">
                    <li><a href="https://x.com/rotterdam" class="footer-link">Twitter</a></li>
                    <li><a href="https://www.facebook.com/gem.Rotterdam" class="footer-link">Facebook</a></li>
                    <li><a href="https://www.instagram.com/gemeenterotterdam/?hl=en" class="footer-link">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            © {{ date('Y') }} Gemeente Rotterdam - Alle rechten voorbehouden
        </div>
    </div>
</footer>

<script src="{{ asset('js/ui-helpers.js') }}"></script>

@stack('scripts')

</body>
</html>

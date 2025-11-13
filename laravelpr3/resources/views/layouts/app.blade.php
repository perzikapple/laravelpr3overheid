<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Mijn Website')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="bg-gray-100 font-sans" style="background-color: #f8f9fa;">


<nav style="background-color: #00811f;" class="text-white p-4 flex justify-between items-center fixed top-0 left-0 w-full shadow-md z-50">
    <a href="{{ route('home') }}" class="font-bold text-lg"><img src="{{ asset('share-image-rotterdam.jpg') }}" alt="Logo" class="h-16"></a>

    <div class="flex items-center space-x-4">
        @auth
            @if (Auth::user()->admin == 1 || Auth::user()->admin === true)
                <a href="{{ route('admin') }}" class="hover:underline">Admin</a>
            @endif

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:underline">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="hover:underline">Login</a>
            <a href="{{ route('register.form') }}" class="hover:underline">Registreren</a>
        @endauth
    </div>
</nav>

<main class="p-6 pt-24">
    @yield('content')
</main>

<script src="{{ asset('js/ui-helpers.js') }}"></script>

@stack('scripts')

</body>
</html>

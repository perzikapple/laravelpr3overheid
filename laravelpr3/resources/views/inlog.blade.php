<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/inlog.css">
    <title>Inloggen</title>
</head>
<body>

<div class="navbar">
    <a href="{{ route('home') }}">Home</a>
</div>

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

        <a href="{{ route('register.form') }}">Registreer hier</a>

    </div>
</div>
</body>
</html>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inloggen</title>

    <link rel="stylesheet" href="../css/inlog.css">
</head>
<body>

<div class="login-container">
    <h1>Inloggen</h1>

    <form method="POST" action="{{ route('login') }}">

        <div class="form-group">
            <label for="email">E-mailadres</label>
            <input type="email" id="email" name="email" placeholder="Voer je e-mail in" required>
        </div>

        <div class="form-group">
            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" placeholder="Voer je wachtwoord in" required>
        </div>

        <button type="submit">Inloggen</button>

    </form>
</div>


<script src="{{ asset('../js/home.js') }}"></script>
</body>
</html>

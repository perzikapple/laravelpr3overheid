<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/bedankt.css">
    <title>Bedankt</title>
</head>
<body>

<div class="navbar">
    <a href="{{ route('login') }}">Login</a>
</div>

<div class="container">
    <h1>Bedankt voor je melding!</h1>
    <p>We hebben je melding goed ontvangen en gaan ermee aan de slag.</p>

    <form action="{{ route('home') }}">
        <button>Nog een melding maken?</button>
    </form>

</div>

</body>
</html>

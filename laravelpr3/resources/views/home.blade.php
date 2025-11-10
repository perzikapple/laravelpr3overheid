<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Report Submission</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <div class="navbar">
@auth
    @if (Auth::user()->admin)
        <a href="../views/adminpage.blade.php">Adminpagina</a>
    @endif
@endauth


    </div>
</head>
<body>
<div class="container">
    <h2>Submit a Report</h2>
    <form method="POST" action="../views/bedankt.blade.php" enctype="multipart/form-data">
        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="photo">Photo</label>
        <input type="file" id="photo" name="photo" accept="image/*">

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <button type="button" id="locateBtn">Use my location</button>
        <form action="../views/bedankt.blade.php" method="POST">
            <button type="submit">Verstuur melding</button>
        </form>

    </form>
    <div id="map" style="height: 350px; margin-top: 24px; border-radius: 8px;"></div>
</div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="../js/home.js"></script>
</body>
</html>

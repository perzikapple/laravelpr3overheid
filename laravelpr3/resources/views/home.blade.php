<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Report Submission</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
<div class="navbar">
    <a href="/login">Login</a>
</div>
<div class="container">
    <div id="popupMessage" style="display:none;position:fixed;top:24px;left:50%;transform:translateX(-50%);z-index:9999;padding:16px 32px;border-radius:8px;font-weight:bold;"></div>
    <h2>Submit a Report</h2>
    <form method="POST" action="/home" enctype="multipart/form-data">
        @csrf
        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="photo">Photo</label>
        <input type="file" id="photo" name="photo" accept="image/*">

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <button type="button" id="locateBtn">Use my location</button>
        <button type="submit">Send Report</button>
    </form>
    <div id="map" style="height: 350px; margin-top: 24px; border-radius: 8px;"></div>
</div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="../js/home.js"></script>
<script>
    @if(session('success'))
    showPopup("{{ session('success') }}", "#d4edda", "#155724");
    @elseif(session('error'))
    showPopup("{{ session('error') }}", "#f8d7da", "#721c24");
    @endif

    function showPopup(msg, bg, color) {
        var popup = document.getElementById('popupMessage');
        popup.textContent = msg;
        popup.style.background = bg;
        popup.style.color = color;
        popup.style.display = 'block';
        setTimeout(function(){ popup.style.display = 'none'; }, 3000);
    }
</script>
</body>
</html>

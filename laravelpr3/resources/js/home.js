
var map = L.map('map').setView([52.2, 5.3], 7);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

var marker;

document.getElementById('locateBtn').addEventListener('click', function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            map.setView([lat, lng], 15);
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map).bindPopup('You are here').openPopup();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }, function() {
            document.getElementById('map').innerHTML = 'Unable to get location';
        });
    } else {
        document.getElementById('map').innerHTML = 'Geolocation not supported';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    
});


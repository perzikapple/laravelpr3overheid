let map = L.map('map', {
    minZoom: 11,
    maxZoom: 18,
    maxBounds: [
        [51.8, 4.3],   // Zuidwest hoek van Rotterdam
        [52.0, 4.65]    // Noordoost hoek van Rotterdam
    ]
});
let marker;

// PDOK Kaart van Nederland (gebruikt door Nederlandse gemeenten)
L.tileLayer('https://service.pdok.nl/brt/achtergrondkaart/wmts/v2_0/standaard/EPSG:3857/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: 'Kaartgegevens &copy; <a href="https://www.kadaster.nl">Kadaster</a>'
}).addTo(map);

// Start altijd op Rotterdam centrum
map.setView([51.9225, 4.47917], 13);

// PROBEER DIRECT LOCATIE TE VINDEN BIJ LADEN
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {

        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        // Check of locatie in Rotterdam is
        if (lat >= 51.8 && lat <= 52.0 && lng >= 4.3 && lng <= 4.65) {
            // Kaart naar echte locatie zetten
            map.setView([lat, lng], 15);

            marker = L.marker([lat, lng]).addTo(map).bindPopup("Jouw locatie");

            // Vul inputvelden
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        } else {
            // Locatie buiten Rotterdam - blijf op centrum
            alert('Je locatie ligt buiten Rotterdam. Plaats de pin handmatig op de kaart.');
        }

    }, function() {
        // Als gebruiker weigert → fallback
        map.setView([51.9225, 4.47917], 13);
    });
} else {
    map.setView([51.9225, 4.47917], 13);
}


// HANDMATIG LOCATIE KNOP
document.getElementById('locateBtn').addEventListener('click', function() {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            // Check of locatie in Rotterdam is
            if (lat >= 51.8 && lat <= 52.0 && lng >= 4.3 && lng <= 4.65) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                if (marker) map.removeLayer(marker);

                marker = L.marker([lat, lng]).addTo(map).bindPopup("Jouw locatie");
                map.setView([lat, lng], 15);
            } else {
                alert('Je locatie ligt buiten Rotterdam. Gebruik de kaart om een locatie in Rotterdam te kiezen.');
            }

        }, function() {
            alert("Kon locatie niet ophalen.");
        });
    } else {
        alert("GPS niet beschikbaar.");
    }
});

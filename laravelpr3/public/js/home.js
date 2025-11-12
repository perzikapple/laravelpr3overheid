let map = L.map('map');
let marker;

// TEGELLAAG
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

// PROBEER DIRECT LOCATIE TE VINDEN BIJ LADEN
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {

        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        // Kaart naar echte locatie zetten
        map.setView([lat, lng], 15);

        marker = L.marker([lat, lng]).addTo(map).bindPopup("Jouw locatie");

        // Vul inputvelden
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

    }, function() {
        // Als gebruiker weigert → fallback
        map.setView([52.3702, 4.8952], 12);
    });
} else {
    map.setView([52.3702, 4.8952], 12);
}


// HANDMATIG LOCATIE KNOP
document.getElementById('locateBtn').addEventListener('click', function() {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) map.removeLayer(marker);

            marker = L.marker([lat, lng]).addTo(map).bindPopup("Jouw locatie");
            map.setView([lat, lng], 15);

        }, function() {
            alert("Kon locatie niet ophalen.");
        });
    } else {
        alert("GPS niet beschikbaar.");
    }
});

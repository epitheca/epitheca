 	//Ajout des couches
var OSM = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	});

// https: also suppported.
var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
	});

    //Ajout du marqueur latitude et longitude sont donnés par PHP
var marker = L.marker([latitude, longitude],{draggable: true}); 
   
   //Ajout du rectangle pour les données prises en compte
var latlngs = [
	[51.237159, 11.074219],
    [51.237159, -6.458496],
    [40.520481, -6.458496],
    [40.520481, 11.074219],];
var polygon = L.polygon(latlngs, {color: '#5078DC', fillColor: 'transparent',});
  
var map = L.map('mapid', {
							center: [46.668972, -1.433574],
							zoom: 17,
							layers: [OSM, marker, polygon]
							});

	//Fonction pour le clic sur la carte
map.on('click', function (e) {
marker.setLatLng(e.latlng);
updateLatLng(marker.getLatLng().lat, marker.getLatLng().lng);
});

	//Fonction pour déplacement du marqueur
marker.on('dragend', function (e) {
updateLatLng(marker.getLatLng().lat.toFixed(6), marker.getLatLng().lng.toFixed(6));
});

	//Fonction pour la saisie manuelle des coordonnées
function updateLatLng(lat,lng,reverse) {
if(reverse) {
marker.setLatLng([lat,lng]);
map.panTo([lat,lng]);
} else {
document.getElementById('latitude').value = marker.getLatLng().lat.toFixed(6);
document.getElementById('longitude').value = marker.getLatLng().lng.toFixed(6);
map.panTo([lat,lng]);
}
}

var base = {
    "Carte": OSM,
    "Photo": Esri_WorldImagery
};

L.control.layers(base).addTo(map);
map.panTo(new L.LatLng(latitude, longitude));

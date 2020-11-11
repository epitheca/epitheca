var x = document.getElementById("demo");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "La géolocalisation n'est pas supportée par votre navigateur.";
    }
}

function showPosition(position) {
document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
updateLatLng(document.getElementById('latitude').value,document.getElementById('longitude').value,1);
}

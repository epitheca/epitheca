<?php
session_start ();
require("Util.php");



// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

//Récupération des valeurs et passage en javascript
?>
<script src="leaflet/leaflet.js"></script>
<script>
var latitude = "<?php echo $_GET ['lat'];?>";
var longitude = "<?php echo $_GET ['long'];?>";
</script>

<div id="mapid"></div>
<script src="leaflet/leaflet_popup.js"></script>
<?php

?>

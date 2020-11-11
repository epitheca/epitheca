<?php
session_start ();
require ("Util.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);
entete ("Déconnexion", "Déconnexion", "1", $bd);
$session = ChercheSession (session_id(), $bd);



 

if (is_object($session))
{
	?>
	<!--ouverture du bloc page-->
	<div id="bloc-page">
		<!--ouverture d'un 960 moyen-->
		<div class="bloc-960-moyen">
<?php
	//Destruction de la session
	session_destroy();
	//Récupération du nom de la personne
	$requete  = "DELETE FROM sessionweb WHERE id_session='$session->id_session'";
	$resultat = $bd->execRequete ($requete);
  ?>
	<script>
	document.location.href="https://epitheca.fr";
	</script>
  
<?php

}
PiedDePage($session, "1", $bd);
?>

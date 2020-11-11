<?php
session_start ();
require("Util.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

$session = ControleAcces ("Stats_admin.php", $_POST, session_id(), $bd);
 if (SessionValide ($session, $bd))
    {
   $observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
	$code_obs = "$observateur->code_obs";
Entete ("epitheca.fr", "Statistiques", $code_obs, $bd);
	 }
	    //Vérification des droits d'administration
		//Récupération des droits d'administration
		$administration = administration ($code_obs, $bd); 	  
		//Refusé
		if ($administration=="non") echo "<div class='bloc-avertissement-100pc'>Vous n'avez pas la permission d'afficher cette page.</div>";

		//Accepté
		else
		{
?>	 
<div class="bloc-page">
<div class="bloc-50pc-gauche">
	<p class="titre">Nombre de données</p>
<?php include("Stats_fonction_nbr_donnees.php"); ?>
</div>
<div class="bloc-50pc-droit">
	<p class="titre">Nombre de connexions</p>
<?php  include("Stats_fonction_nbr_connexion.php"); ?>
</div>
<div class="bloc-50pc-gauche">
	<p class="titre">Nombre de comptes</p>
<?php include("Stats_fonction_nbr_compte.php"); ?>
</div>

</div>
<?php
}
PiedDePage($session, $code_obs, $bd);
?>

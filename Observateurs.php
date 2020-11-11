<?php
session_start();
require("Util.php");
require_once ("Tableau.class.php");
include ("Listes.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

$session = ControleAcces ("Observateurs.php", $_POST, session_id(), $bd);
if (SessionValide ($session, $bd))
{
// Production de l'entête
$observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
$code_obs = "$observateur->code_obs";
Entete ("epitheca.fr", "6", $code_obs, $bd);
}

 //Vérification des droits d'administration
		//Récupération des droits d'administration
		$administration = administration ($code_obs, $bd); 	  
		//Refusé
		if ($administration=="non") echo "<div class='bloc-avertissement-100pc'>Vous n'avez pas la permission d'afficher cette page.</div>";

		//Accepté
		else
		{

//Capture du mode POST
if (isset($_POST['mode'])) $mode=$_POST['mode'];
else $mode="";

//Capture d'un GET
if (isset($_GET['mode'])) $mode=$_GET['mode'];

//Supression d'un observateur
if ($mode=="sup")
{
	$code_obs=$_GET['code_obs'];
	
	//Lancement de la requete
	$supcompte = "DELETE FROM observateurs WHERE code_obs='$code_obs'";
	$res = $bd->execRequete ($supcompte);
}

//Supression de droits d'un observateur
if ($mode=="sup-droit")
{
	$code_obs=$_POST['code_obs'];
	$groupe=$_POST['groupe'];
	
	//Lancement de la requete
	$supdroit = "UPDATE observateurs_coordinateur SET $groupe='non' WHERE code_obs='$code_obs'";
	$res = $bd->execRequete ($supdroit);
	
}

?>
<div id="bloc-page">
	<div class="demi-gauche">
		<div class="bloc-50%-clair-droit">
		</div>
	<div class="bloc-50%-clair-gauche">
		<div class="titre"></div>
	</div>
		</div>
		
	<div class="demi-droit">		
		
		
		<div class="bloc-50%-clair-droit">
		<!--Formulaire pour les observateurs-->			
		<div class="titre">Observateurs</div>
		<?php 
		//Module d'ajout d'observateur
		$insert= Ancre_renomme ("Observateurs_maj_admin.php?mode=INSERTION", "Insérer un nouvel observateur");
		echo "<center>$insert</center><br><br>";
				
		//ouverture de la requête
		$resultat = $bd->execRequete ("SELECT * FROM observateurs WHERE code_obs <> 1 ORDER BY nom"); 
		while ($bo = $bd->objetSuivant ($resultat))
		{
		$code=urlEncode ($bo->code_obs);
	   $mode="MAJ";
	   $texte = Ancre_renomme ("Observateurs_maj_admin.php?mode=$mode&codeobsamaj=$code", "$bo->prenom $bo->nom");
	  	   
	   //Calcul des chiffres
	  $nbrtotal = Calcdontotal ($bo->code_obs, '', $bd);
      
	  //Ajout du mot données
	  if ($nbrtotal==1) $nbrtotal .=" donnée";
	  if ($nbrtotal==0) $nbrtotal="<a href='Observateurs.php?mode=sup&code_obs=$code'>Supprimer</a>";
	  if ($nbrtotal>1) $nbrtotal .=" données";
      	   
	   // Recherche du nombre de connexion
		$resultatobs = $bd->execRequete ("SELECT COUNT(*) AS connexions FROM connexions WHERE code_obs LIKE '$bo->code_obs'"); 
     	while ($bobs = $bd->objetSuivant ($resultatobs))
     	$nombre="$bobs->connexions";
     	//Ajout du mot connexions
	  if ($nombre==1) $nombre .=" connexion";
	  if ($nombre==0) $nombre="";
	  if ($nombre>1) $nombre .=" connexions";
     		   
	  	?>
	  	<div class="tableau-observateurs-1"><?php echo $texte;?></div>
		<div class="tableau-observateurs-2"><?php echo $nbrtotal;?></div>
		<div class="tableau-observateurs-3"><?php echo $nombre;?></div>
      <div class="spacer"></div>
      <?php
  }
      ?>
		</div>
	</div>
</div>
<?php
}
// Affichage du pied de page
PiedDePage($session, $code_obs, $bd);
?>

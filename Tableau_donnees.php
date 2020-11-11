<?php
// Tableau pour données existantes

function Tabdonnees ($date, $latitude, $longitude, $observateur, $corine_1, $corine_2, $corine_3, $corine_4, $vent, $meteo, $temperature, $riviere, $route, $bd)
{
	?>
	<fieldset>
	<legend></legend>
	<?php
	//Réinitialisation de la valeur i
	$i=0;
	//Test de la requête
$resultat = $bd->execRequete ("SELECT * FROM donnees WHERE latitude='$latitude' AND longitude='$longitude'AND obs_1='$observateur' AND date='$date'");
while ($bo = $bd->objetSuivant ($resultat))
{$i=1;}

if ($i==0) $pasdedonnees="Il n'existe actuellement aucune donnée sur cette station à cette date et pour cet observateur."; 
else $pasdedonnees="Voici les données attachées à ce lieu pour cette date et cet observateur:";
 echo "<p class='titre'>$pasdedonnees</p><br>";

//Entête de tableau
if ($i<>0)
{
	?>

<div class="tableau-donnees-1">
Nom de l'espèce
</div>
<div class="tableau-donnees-2">
Effectif
</div>
<div class="tableau-donnees-3">
Sexe
</div>
<div class="tableau-donnees-4">
Stade
</div>
<div class="tableau-donnees-5">
Comportement
</div>
<div class="tableau-donnees-6">
Modification
</div>
<div class="tableau-donnees-7">
Suppression
</div>
<div class="tableau-donnees-8">
</div>
<?php
}

//Extraction des données
$resultat = $bd->execRequete ("SELECT * FROM donnees WHERE latitude='$latitude' AND longitude='$longitude'AND obs_1='$observateur' AND date='$date'");
while ($bo = $bd->objetSuivant ($resultat))

{
	//Recherche des données spéciales
	list($formulairec, $texte, $donnees) = Cherche_form_spec_obs ($bo->numero, $bd, $format=FORMAT_OBJET);
	
	//Recherche du nom de l'espèce
	$espece= ChercheSpavecCode ($bo->espece, $bd, FORMAT_OBJET);
	$nom=$espece->NOM_VALIDE;
    
    	//Recherche de l'abondance
	$abondance=$bo->abondance;
	
	//Recherche du sexe
	$sexe=$bd->execRequete ("SELECT sexe FROM sexe WHERE code_sexe='$bo->sexe'");
	while ($sexee=$bd->objetSuivant ($sexe))
    $typesexe=$sexee->sexe;
    
    //Recherche du stade
    $infoe=$bd->execRequete ("SELECT information FROM information WHERE code='$bo->info_1'");
	while ($infoee=$bd->objetSuivant ($infoe))
    $stade= $infoee->information;
    
    //Recherche du comportement
    $infol  = $bd->execRequete ("SELECT information FROM information_2 WHERE code='$bo->info_2'");
	while ($infol_2 = $bd->objetSuivant ($infol))	
	$comportement= $infol_2->information;
	
	//modification	
	$numeroobs="$bo->numero";	
	$modemaj="MAJ";
	if ($bo->valide_le ==NULL) $textemaj = Ancre_renomme ("Ajout.php?mode=$modemaj&numeroobs=$numeroobs", "Possible");
	else $textemaj = "<a href=mail_demande_modification.php?numero=$numeroobs target=wclose 
	onclick=window.open('popup-mail.php?numero=$numeroobs','wclose','width=500,height=400,toolbar=no,status=no,scrollbars=yes,left=20,top=30')>Demander</A>";
	
	//suppression
	$modesup="SUP";
	if ($bo->valide_le ==NULL) $textesup = Ancre_renomme ("Ajout.php?mode=$modesup&numeroobs=$numeroobs", "Possible");	
	else $textesup="Impossible";
   
?>

<div class="tableau-donnees-1">
<?php echo $nom;?>
</div>
<div class="tableau-donnees-2">
<?php echo $abondance;?>
</div>
<div class="tableau-donnees-3">
<?php echo $typesexe; ?>
</div>
<div class="tableau-donnees-4">
<?php echo $stade; ?>
</div>
<div class="tableau-donnees-5">
<?php echo $comportement; ?>
</div>
<div class="tableau-donnees-6">
<?php echo $textemaj; ?>
</div>
<div class="tableau-donnees-7">
<?php echo $textesup; ?>
</div>
<div class="tableau-donnees-8">
<span class='info-gauche' href='#'><?php echo $texte;?><span><?php echo $donnees;?></span></span>
</div>
<div class="spacer"></div>

<?php
}
echo "</fieldset>";
}
?>

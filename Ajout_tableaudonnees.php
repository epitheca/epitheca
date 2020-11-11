<!--
Copyright Mathieu MONCOMBLE (contact@epitheca.fr) 2009-2020

This file is part of epitheca.

    epitheca is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License.

    epitheca is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with epitheca.  If not, see <https://www.gnu.org/licenses/>.
-->

<?php
// Tableau pour données existantes

function Tabdonnees ($date, $latitude, $longitude, $observateur, $corine_1, $corine_2, $corine_3, $corine_4, $vent, $meteo, $temperature, $riviere, $route, $bd)
{
	
	//Transformation en Anglais
$dateen=datesitetoserver ($date);
	?>
	
	<fieldset>
	<legend></legend>
	<?php
	//Réinitialisation de la valeur i
	$i=0;
	//Test de la requête
$resultat = $bd->execRequete ("SELECT * FROM donnees WHERE latitude='$latitude' AND longitude='$longitude'AND obs_1='$observateur' AND date='$dateen'");
while ($bo = $bd->objetSuivant ($resultat))
{$i=1;}

//Transformation en Français
$dateen=datesitetoserver ($date);

if ($i==0) $pasdedonnees="<center>Il n'existe actuellement aucune donnée pour ce lieu à cette date et pour cet observateur</center>"; 
else $pasdedonnees="Voici les données attachées à ce lieu le $date :";
 echo "<span class='titre'>$pasdedonnees</span><br>";

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
$resultat = $bd->execRequete ("SELECT * FROM donnees WHERE latitude='$latitude' AND longitude='$longitude'AND obs_1='$observateur' AND date='$dateen'");
while ($bo = $bd->objetSuivant ($resultat))

{
	//Recherche des données spéciales
	list($formulairec, $texte, $donnees) = Cherche_form_spec_obs ($bo->numero, $bd, $format=FORMAT_OBJET);
	
	//on récupère le code de l'espèce
	$nom_espece = ChercheSpavecCode ($bo->espece, $bd, FORMAT_OBJET);
	$nom_espece = $nom_espece->NOM_VALIDE;
    
	//Recherche de l'abondance
	$abondance=$bo->abondance;
	
	//Recherche du sexe
	if (is_null ($bo->sexe) ) $typesexe="?";
    else
    {
	$sexe=$bd->execRequete ("SELECT sexe FROM sexe WHERE code_sexe='$bo->sexe'");
	while ($sexee=$bd->objetSuivant ($sexe))
    $typesexe=$sexee->sexe;
}
    //Recherche du stade    
    if (is_null ($bo->info_1) ) $stade="Non précisé";
    else
    {
	$infoe=$bd->execRequete ("SELECT information FROM information WHERE code='$bo->info_1'");
	while ($infoee=$bd->objetSuivant ($infoe))
	$stade=$infoee->information;
	}
    //Recherche du comportement
    if (is_null ($bo->info_2) ) $comportement="Non précisé";
    else
    {
    $infol  = $bd->execRequete ("SELECT information FROM information_2 WHERE code='$bo->info_2'");
	while ($infol_2 = $bd->objetSuivant ($infol))	
	$comportement= $infol_2->information;
}

	//modification	
	$numeroobs="$bo->numero";	
	$modemaj="MAJ";
	$textemaj = Ancre_renomme ("Ajout.php?mode=$modemaj&numeroobs=$numeroobs", "Modifier");
	
	//suppression
	$modesup="SUP";
	$textesup = Ancre_renomme ("Ajout.php?mode=$modesup&numeroobs=$numeroobs", "Supprimer");	
	   
?>

<div class="tableau-donnees-1">
<?php echo $nom_espece;?>
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

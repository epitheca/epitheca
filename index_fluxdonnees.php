<?php
//Calcul du nombre total
$nbrtotal = Calcdontotal ($code_obs, '', $bd);

if ($nbrtotal<>0)
{
    ?>
    <div class='titre'>Vos dernières données saisies</div><br>
<?php
//Extraction des deux lettres du groupe
	$resultat  = $bd->execRequete ("SELECT * FROM donnees ORDER BY numero DESC LIMIT 0, 6 "); 
  while ($bo = $bd->objetSuivant ($resultat))
   {
	   ?>  
		<b class="tl"><b class="tr"></b></b>
		<?php
	//Modification de la date	
   $datefr= dateservertosite ($bo->date);
   $date=$bo->date;
   //Recherche de l'espèce
   $nom_espece = ChercheSpavecCode ($bo->espece, $bd);
	$obs1=Chercheobservateursaveccode ($bo->obs_1, $bd, $format=FORMAT_OBJET);
	$prenom = $obs1->prenom;
	$nom = $obs1->nom;
    
//Affichage  de la date 
    echo '<br><div class="flux_donnees_gauche">';
    list($year, $month, $day) = explode("-", $date);
    $months = array("janvier", "février", "mars", "avril", "mai", "juin",
    "juillet", "août", "septembre", "octobre", "novembre", "décembre");
    echo $lastmodified = "Le $day ".$months[$month-1]." $year";
    $url = "Ajout.php?mode=MAJ&numeroobs=$bo->numero";
    echo "<br><a href=$url >$nom_espece->NOM_VALIDE (donnée n° $bo->numero) </a></div>";
	
}}
  ?>

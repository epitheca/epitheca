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
session_start ();
require("Util.php");
require_once ("CSV.php");
require_once ("Carte_obs_mini.php");
include ("Listes.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

$session = ControleAcces ("Consultation.php", $_POST, session_id(), $bd);
 if (SessionValide ($session, $bd))
    {
   $observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
	$code_obs = "$observateur->code_obs";
Entete ("epitheca.fr", "3", $code_obs, $bd);
	}

//Affichage multi page 
	if(!isset($_POST['suivant']) || (!isset ($_POST['precedent']))) $page=1;
	if(isset($_POST['precedent'])) $page=$_POST['page']-1;
	if(isset($_POST['suivant'])) $page=$_POST['page']+1;
	$nombre_resultats = 15;
	$debut = ($page-1)*$nombre_resultats;

//Récupération des valeurs du POST
	if (isset ($_POST['datefrmin'])) $datefrmin= $_POST['datefrmin'];
	else $datefrmin= "01/01/2000";
	if (isset ($_POST['datefrmax'])) $datefrmax= $_POST['datefrmax'];
	else
	{
	//Capture des dates
	$aujourdhui= date("d/m/Y");
	$datefrmax= $aujourdhui;
	}

	if (isset ($_POST['classe_ordre'])) $groupe=$_POST['classe_ordre'];
	else $groupe="tous";

	if (isset ($_POST['latitude_X'])) $latitude_X=$_POST['latitude_X'];
	else $latitude_X="51.237159";
	if (isset ($_POST['longitude_X'])) $longitude_X=$_POST['longitude_X'];
	else $longitude_X="-6.458496";
	if (isset ($_POST['latitude_Y'])) $latitude_Y=$_POST['latitude_Y'];
	else $latitude_Y="40.520481";
	if (isset ($_POST['longitude_Y'])) $longitude_Y=$_POST['longitude_Y'];
	else $longitude_Y="11.074219";
	if (isset ($_POST['route'])) $route=$_POST['route'];
	else $route="X";
	if (isset ($_POST['riviere'])) $riviere=$_POST['riviere'];
	else $riviere="X";
	if (isset ($_POST['corine'])) $corine=$_POST['corine'];
	else $corine="X";
				if (isset ($_POST['sps'])) $sps=$_POST['sps'];
	else $sps="";
	
//Traduction des dates
	$dateenmax= datesitetoserver ($datefrmax);
	$dateenmin= datesitetoserver ($datefrmin);
	
//Controle des données
	if (isset ($_POST['filtre']))
	$message = ControleFiltre($_POST) ;



// Erreur de saisie détectée: on affiche le message en alerte en fenêtre Modal  
	if (!empty($message))
		{	  
			fenetre_modal ("Problème dans votre saisie","$message");
	  
			//Requête groupée
			$requete_groupee = "SELECT * 
			FROM donnees
			WHERE 
			(obs_1= '$code_obs' 
			OR obs_2= '$code_obs' 
			OR obs_3= '$code_obs')";
			$filtre="";
		}
//Pas d'erreur de saisie : on continue
else
	{
	//Mise à 0 du filtre
    $filtre ="";
    
	//Remise du compteur à 0
	$i=0;

	//Choix du taxon
		//Autre choix que "Tous les groupes"
		if ($groupe=="tous") $filtre .="AND espece LIKE '%'";
		 
	//Route
	if ($route<>"X") $filtre .="AND route= '$route' "; 

	//Rivière
	if ($riviere<>"X") $filtre .="AND riviere= '$riviere' "; 		

	//Corine
	if ($corine<>"X") $filtre .="AND (corine_1= '$corine' 
	OR corine_2= '$corine' 
	OR corine_3= '$corine' 
	OR corine_4= '$corine') ";
		
	//Requête groupée
	$requete_groupee = "SELECT info_1, info_2, obs_1, obs_2, obs_3, origineDonnee, numero, remarques, type_donnees, sexe, abondance, espece, obs_1, date, longitude, latitude, corine_1, corine_2, corine_3, corine_4, vent, meteo, temperature, riviere, route 
	FROM donnees
	WHERE 
	(obs_1= '$code_obs' 
	OR obs_2= '$code_obs' 
	OR obs_3= '$code_obs') 
	AND date BETWEEN '$dateenmin' AND '$dateenmax'
	AND latitude BETWEEN '$latitude_Y' AND '$latitude_X' 
	AND longitude BETWEEN '$longitude_X' AND '$longitude_Y'
	$filtre";
}

//Agrégation de la requête
 $requete="$requete_groupee 
GROUP BY info_1, info_2, obs_1, obs_2, obs_3,origineDonnee, numero, remarques, valide_le, valide_par, type_donnees, sexe, abondance, espece, obs_1, date, longitude, latitude, corine_1, corine_2, corine_3, corine_4, vent, meteo, temperature, riviere, route 
ORDER by date DESC, longitude, latitude, Date DESC LIMIT $debut, $nombre_resultats";

//Calcul du nombre de fiches
$num_rows = $bd->nbResultats ($bd->execRequete("$requete_groupee 
GROUP BY obs_1, obs_2, obs_3,origineDonnee, date, longitude, latitude, corine_1, corine_2, corine_3, corine_4, vent, meteo, temperature, riviere, route 
ORDER by date DESC, longitude, latitude"));

//Calcul du nombre de données
$num_donnees = $bd->nbResultats ($bd->execRequete ($requete_groupee));
include ("Consultation_affichage_fiches.php");
include ("Consultation_affichage_extraction.php");
include ("Consultation_formulaire.php");

echo "</div>";
// Affichage du pied de page
PiedDePage($session, $code_obs, $bd);
?>

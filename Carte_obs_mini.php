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
function carte_obs_mini ($requete, $code_obs, $bd)

{
//Execution des requêtes
$resultat_valide = $bd->execRequete ($requete);

//Comptage des données
$nbr_valide = mysqli_num_rows($bd->execRequete($requete));

// Il existe au moins une donnée
if ($nbr_valide<>0)
{
	//Création de la petite carte
	//Définition de la carte de la nouvelle carte
	$nomdelacarte="cartes/carte_$code_obs.png";
	$xpx=1500;
	$ypx=917;
	
	//Copie de la carte d'origine
	copy ( "images/France.png" , $nomdelacarte);
	
	//Création de l'image
	$image = imagecreatefrompng($nomdelacarte);

	//Il existe au moins une donnée
	if ($nbr_valide<>0)
	{
		// Création de la liste des coordonnées
	$i=0;
	while ($statGenre = $bd->objetSuivant ($resultat_valide))
	{  
	  $longi_valide[$i] = $statGenre->longitude;
	  $lati_valide[$i++] = $statGenre->latitude;  
	}
	//Création du point bleu
	$point_valide = imagecreatefrompng("images/carte_point_5_valide.png");

	//Création des rectangles pour les données validées
	for ($j=0; $j<=$nbr_valide-1;$j++)
		{
			$x=($xpx*($longi_valide[$j]+6.458496)/17.532715)-2;
			$y=($ypx*(51.237159-$lati_valide[$j])/10.716678)-2;
			imagecopy($image, $point_valide, $x ,$y, 0, 0, 15, 15);
		}
	}

	//Enregistrement de la carte
	$file = "cartes/mini_carte_$code_obs.png";
	imagepng($image, $file);
}

if ($nbr_valide==0)
{
	copy(CHEMIN."cartes/France.png", CHEMIN."cartes/mini_carte_$code_obs.png");
}}
?>

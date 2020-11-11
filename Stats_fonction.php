<?php

function stats ($code_obs, $bd)
{ 
	
// Recherche des données entre 2010 et 2012
$requete = "SELECT COUNT( numero ) AS nbr, MONTH( date ) as Mois , YEAR( date ) 
FROM donnees
WHERE SUBSTR(Date, 1, 7) BETWEEN '2010-01' AND '2012-12'
GROUP BY MONTH( Date )";
$resultat = $bd->execRequete ($requete);

// On crée deux tableaux indicés, l'un avec les données, 
// l'autre avec la légende
while ($statGenre = $bd->objetSuivant ($resultat))
{
echo $statGenre->nbr/3;
$stat = "UPDATE stat SET Moyenne=$statGenre->nbr WHERE Mois=$statGenre->Mois";
$res = $bd->execRequete ($stat); 
echo "<br>";
}	
	
// Recherche des données pour 2013
$requete = "SELECT COUNT( numero ) AS nbr, MONTH( date ) as Mois , YEAR( date ) 
FROM donnees
WHERE SUBSTR(Date, 1, 7) BETWEEN '2013-01' AND '2013-12'
GROUP BY YEAR( Date ) , MONTH( Date )";
$resultat = $bd->execRequete ($requete);

// On crée deux tableaux indicés, l'un avec les données, 
// l'autre avec la légende
while ($statGenre = $bd->objetSuivant ($resultat))
{
$stat = "UPDATE stat SET treize=$statGenre->nbr WHERE Mois=$statGenre->Mois";
$res = $bd->execRequete ($stat); 
}}

function stats_netoyage ($bd)
{
	$i=1;
while($i<13)
{
$stat = "UPDATE stat SET Moyenne='0' WHERE Mois=$i";
$res = $bd->execRequete ($stat);
$i++;
}
}
?>

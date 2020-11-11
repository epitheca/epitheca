<?php
// Cette fonction supprime tout échappement automatique
// des données HTTP dans un tableau de dimension quelconque
// Attention: il faudrait aussi "nettoyer" les clés ... ou
// éviter de mettre des clés avec des apostrophes

function NormalisationHTTP($tableau)
{
  //Si on est en échappement automatique, on rectifie...
  foreach ($tableau as $cle => $valeur) 
    {
      if (!is_array($valeur)) // On agit
	$tableau[$cle] = stripSlashes($valeur);
      else  // On appelle récursivement
	$tableau[$cle] = NormalisationHTTP($valeur);
    }
  return $tableau;
}
?>

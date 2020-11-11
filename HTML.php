<?php
// Fonctions produisant des conteneurs HTML
function Ancre_renomme ($url, $libelle, $classe=-1)
{
  $optionClasse = "";
  if ($classe != -1) $optionClasse = " CLASS='$classe'";
  return "<A HREF='$url'$optionClasse>$libelle</A>";   
}

function Image_renomme ($url, $largeur=-1, $hauteur=-1, $bordure=0)
{
  $attrLargeur = $attrHauteur = "";
  if ($largeur != -1) $attrLargeur = " WIDTH  = '$largeur' ";
  if ($hauteur != -1) $attrHauteur = " HEIGHT = '$hauteur' ";

  return "<IMG SRC='$url' $attrLargeur  $attrHauteur BORDER='$bordure' alt='image manquante'>\n";   
}
?>

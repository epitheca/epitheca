<?php
// Définition d'un gestionnaire d'erreurs. 
// Elle affiche en français le message.
function GestionErreurs ($niveau_erreur, $message, 
             $script, $no_ligne, $contexte=array())
{
  // Regardons quel est le niveau de l'erreur
  switch ($niveau_erreur)
    {
      // Les erreurs critiques
      case E_ERROR: 
      case E_PARSE: 
      case E_CORE_ERROR:
      case E_CORE_WARNING:
      case E_COMPILE_ERROR:
      case E_COMPILE_WARNING:
        echo "Ca ne doit jamais arriver !!";
        exit;
      
      case E_WARNING: 
        $typeErreur = "Avertissement PHP";
        break;

      case E_NOTICE: 
        $typeErreur = "Remarque PHP";
        break;

      // REMPLACEMENT DE E_STRICT PAR E_DEPRECATED
      case E_DEPRECATED:
      case E_USER_DEPRECATED:
        $typeErreur = "Fonctionnalité obsolète (PHP 8+)";
        break;

      case E_USER_ERROR: 
      case E_USER_WARNING: 
        $typeErreur = "Avertissement de l'application";
        break;

      case E_USER_NOTICE: 
        $typeErreur = "Remarque de l'application";
        break;

      default:
        $typeErreur = "Erreur ($niveau_erreur)";
    }

  // Affichage
  $message_final = "$typeErreur : $message. Ligne $no_ligne du script $script";

  echo "<CENTER><FONT COLOR='red'><B>" . htmlspecialchars($message_final) . "</B></font></CENTER>";

  if ($niveau_erreur == E_USER_ERROR) exit;
}
?>

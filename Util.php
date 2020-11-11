<?php
// Inclusion des utilitaires du site NV

// Les constantes -> chemin en dur
require_once ("");


// Les fonctions g�n�rales
require_once (CHEMIN."HTML.php");  

// Modules et classes
require_once (CHEMIN."Tableau.class.php");
require_once (CHEMIN."BDMySQL.class.php");

// Fonctions du site
require_once (CHEMIN."Design.php");

// Fonctions diverses et vari�es
require_once (CHEMIN."NormalisationHTTP.php"); 
require_once (CHEMIN."fonctions.php"); 
require_once (CHEMIN."Session.php");
require_once (CHEMIN."GestionErreurs.php");
require_once (CHEMIN."GestionExceptions.php");

// Si on est en �chappement automatique, on rectifie...
// NB: on peut optimiser en utilisant des r�f�rences si n�cessaire.

if (get_magic_quotes_gpc())
{
  $_POST = NormalisationHTTP($_POST);
  $_GET = NormalisationHTTP($_GET);
  $_REQUEST = NormalisationHTTP($_REQUEST);
  $_COOKIE = NormalisationHTTP($_COOKIE);
}

// R�glage du niveau d'erreur
error_reporting(E_ALL | E_STRICT);

// Gestionnaire d'erreurs personnalis�. Voir GestionErreurs.php.
set_error_handler("GestionErreurs");

// Gestionnaire d'exceptions personnalis�. Voir GestionExceptions.php.
set_exception_handler("GestionExceptions");
?>

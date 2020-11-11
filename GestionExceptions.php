<?php
// Définition d'un gestionnaire d'exceptions. On fait
// simplement appel au gestionnaire d'erreurs 

function GestionExceptions ($exception)
{
  // On transforme donc l'exception en erreur
  GestionErreurs (E_USER_ERROR,
		  $exception->getMessage(),
		  $exception->getFile(),
		  $exception->getLine());
}
?>

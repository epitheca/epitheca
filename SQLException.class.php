<?php
// Sous-classe de la classe exception, spécialisée pour
// les erreurs soulevées par un SGBD

class SQLException extends Exception
{
  // Propriétés
  private $sgbd; // nom du SGBD utilisé
  private $code_erreur; // code d'erreur du SGBD

  // Constructeur
  function SQLException_new ($message, $sgbd, $code_erreur=0)
  {
    // Appel du constructeur de la classe parente
    parent::__construct($message);

    // Affectation aux propriétés de la sous-classe
    $this->sgbd = $sgbd;
    $this->code_erreur = $code_erreur;
  }
  
  // Méthode renvoyant le SGBD qui a levé l'erreur
  public function getSGBD()
  {
    return $this->sgbd;
  }

  // Méthode renvoyant le code d'erreur du SGBD
  public function getCodeErreur()
  {
    return $this->code_erreur;
  }
}
?>

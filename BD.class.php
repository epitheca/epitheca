<!--
Copyright Mathieu MONCOMBLE (contact@epitheca.fr) 2009-2022

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
// Classe abstraite définissant une interface générique d'accès
// à une base de données. Version complète

// Exceptions SQL
require_once ("SQLException.class.php");

abstract class BD
{
  // ----   Partie privée : les propriétés
  protected $sgbd, $connexion, $nom_base;
  protected $code_erreur, $message_erreur;
  // Constructeur de la classe
  function __construct ($login, $mot_de_passe, $base, $serveur)
  {
    // Initialisations
    $this->nom_base = $base;
    $this->code_erreur = 0;
    $this->message_erreur = "";
    $this->sgbd = "Inconnu?";

    // Connexion au serveur par appel à une méthode privée
    $this->connexion = $this->connect($serveur, $login, $mot_de_passe, 
				      $base);

    // Lancé d'exception en cas d'erreur
    //if ($this->erreur == 0) 
    //throw new SQLException ("Erreur de connexion au SGBD",
	//		   $this->sgbd, $this->code_erreur);

    // Fin du constructeur
  }

  // Méthodes privées
  abstract protected function connect ($login, $mot_de_passe, $base, $serveur);
  abstract protected function exec ($requete);

  // Méthodes publiques

  // Méthode d'exécution d'une requête
  public function execRequete ($requete)
  {
    if (!$resultat = $this->exec ($requete)) 
      throw new SQLException 
	("Problème dans l'exécution de la requête : $requete.<br> "
	 . $this->messageSGBD(), $this->sgbd, $this->code_erreur);

    return $resultat;
  }

  // Méthodes abstraites
  // Accès à la ligne suivante, sous forme d'objet
  abstract public function objetSuivant ($resultat);
  // Accès à la ligne suivante, sous forme de tableau associatif
  abstract public function ligneSuivante ($resultat);
  // Accès à la ligne suivante, sous forme de tableau indicé
  abstract public function tableauSuivant ($resultat);

  // Echappement des apostrophes et autres préparations à l'insertion
  abstract public function prepareChaine($chaine);

  // Génération d'un identifiant
  abstract public function genereID($nom_sequence);

  // Méthode indiquant le nombre d'attributs dans le résultat
  abstract function nbAttributs ($res);

// Méthode indiquant le nombre de résultat
  abstract function nbResultats ($res);

  // Méthode donnant le nom d'un attribut dans un résultat
  abstract function nomAttribut ($res, $position);

  // Retour du message d'erreur
  abstract public function messageSGBD ();

  // Nom du SGBD
  public function getSGBD()
  {    return $this->sgbd;  }
  protected function setSGBD($sgbd)
  { $this->sgbd = $sgbd;  }

  // Fin de la classe
}
?>

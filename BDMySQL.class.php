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
// Sous-classe de la classe abstraite BD, implantant l'accès à MySQL

require_once("BD.class.php");

class BDMySQL extends BD
{
  // Pas de propriétés: elles sont héritées de la classe BD
  // Pas de constructeur: lui aussi est hérité

  // Méthode connect: connexion à MySQL
  protected function connect ($serveur, $login, $mot_de_passe, $base)
  {
    // Connexion au serveur MySQL 
    if ($this->connexion = mysqli_connect("$serveur","$login","$mot_de_passe","$base"))
    {
		mysqli_set_charset ($this->connexion,'UTF8');
    // Si la connexion a reussi, rien ne se passe.
}
else // Mais si elle rate...
{
    echo 'Erreur'; // On affiche un message d'erreur.
}
    $this->setSGBD("MySQL");
	
    return $this->connexion;
  }

  // Méthode d'exécution d'une requête. 
  protected function exec ($requete) 
    {return mysqli_query ($this->connexion, $requete);  }

  // Partie publique: implantation des méthodes abstraites
  // Accès à la ligne suivante, sous forme d'objet
  public function objetSuivant ($resultat)
    {
		return  mysqli_fetch_object ($resultat);    } 
  // Accès à la ligne suivante, sous forme de tableau associatif
  public function ligneSuivante ($resultat)
    {   return  mysqli_fetch_assoc ($resultat);  }
  // Accès à la ligne suivante, sous forme de tableau indicé
  public function tableauSuivant ($resultat)
    {   return  mysqli_fetch_row ($resultat);  }
 
  // Echappement des apostrophes et autres préparation à l'insertion
  public function prepareChaine($chaine)
  {return mysqli_real_escape_string($this->connexion, $chaine);  }
  
  // Génération d'un identifiant
  public function genereID($nom_sequence)
  {
    // Insertion d'un ligne pour obtenir l'auto-incrémentation
    $this->execRequete("INSERT INTO $nom_sequence VALUES()");

    // Si quelque chose s'est mal passé, on a levé une exception,
    // sinon on retourne l'identifiant
    return mysql_insert_id();
  }

  // Retour du message d'erreur
  public function messageSGBD ()
    { return mysqli_error($this->connexion);}

  // Méthode ajoutée: renvoie le schéma d'une table
  public function schemaTable($nom_table)
  {
    // Recherche de la liste des attributs de la table
    $listeAttr = @mysql_list_fields($this->nom_base, 
				    $nom_table, $this->connexion);
    
    if (!$listeAttr) throw new Exception ("Pb d'analyse de $nom_table"); 
    
    // Recherche des attributs et stockage dans le tableau
    for ($i = 0; $i < mysql_num_fields($listeAttr); $i++) {
	$nom =  mysql_field_name($listeAttr, $i);
	$schema[$nom]['longueur'] = mysql_field_len($listeAttr, $i);
	$schema[$nom]['type'] = mysql_field_type($listeAttr, $i);
	$schema[$nom]['clePrimaire'] = 
	  substr_count(mysql_field_flags($listeAttr, $i), "primary_key");
	$schema[$nom]['notNull'] = 
	  substr_count(mysql_field_flags($listeAttr, $i), "not_null");
      }
    return $schema; 
  }

  // Fonctions décrivant le résultat d'une requête
  public function nbAttributs($resultat)  {
    return mysql_num_fields($resultat);
  }
  
  public function nbResultats($resultat)  {
	  return mysqli_num_rows($resultat);
	  }
  
  public function nomAttribut($resultat, $pos)  {
    return mysql_field_name ($resultat, $pos);
  }

  // Destructeur de la classe: on se déconnecte
  function __destruct ()
  {if ($this->connexion)  mysqli_close ($this->connexion);    }
  // Fin de la classe
}
?>

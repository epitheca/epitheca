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
  // Méthode connect: connexion à MySQL
  protected function connect ($serveur, $login, $mot_de_passe, $base)
  {
    // Connexion au serveur MySQL (suppression des guillemets inutiles)
    $this->connexion = mysqli_connect($serveur, $login, $mot_de_passe, $base);
    
    if ($this->connexion) {
        mysqli_set_charset($this->connexion, 'UTF8');
    } else {
        // En production, il vaut mieux loguer l'erreur que de l'afficher brutalement
        error_log("Erreur de connexion MySQL : " . mysqli_connect_error());
        echo 'Erreur de connexion à la base de données.'; 
    }
    
    $this->setSGBD("MySQL");
    return $this->connexion;
  }

  // Méthode d'exécution d'une requête. 
  protected function exec ($requete) 
    { return mysqli_query($this->connexion, $requete); }

  public function objetSuivant ($resultat)
    { return mysqli_fetch_object($resultat); } 

  public function ligneSuivante ($resultat)
    { return mysqli_fetch_assoc($resultat); }

  public function tableauSuivant ($resultat)
    { return mysqli_fetch_row($resultat); }
 
  // IMPORTANT : Pour la sécurité de vos formulaires
  public function prepareChaine($chaine)
  { return mysqli_real_escape_string($this->connexion, $chaine); }
  
  public function genereID($nom_sequence)
  {
    $this->execRequete("INSERT INTO $nom_sequence VALUES()");
    return mysqli_insert_id($this->connexion);
  }

  public function messageSGBD ()
    { return mysqli_error($this->connexion); }

  // Version corrigée pour mysqli (PHP 8)
  public function schemaTable($nom_table)
  {
    $schema = array();
    $resultat = mysqli_query($this->connexion, "SHOW COLUMNS FROM $nom_table");
    
    if (!$resultat) throw new Exception("Pb d'analyse de $nom_table : " . mysqli_error($this->connexion)); 
    
    while ($ligne = mysqli_fetch_assoc($resultat)) {
        $nom = $ligne['Field'];
        $schema[$nom]['type'] = $ligne['Type'];
        $schema[$nom]['clePrimaire'] = ($ligne['Key'] == 'PRI');
        $schema[$nom]['notNull'] = ($ligne['Null'] == 'NO');
    }
    return $schema; 
  }

  public function nbAttributs($resultat)  {
    return mysqli_num_fields($resultat);
  }
  
  public function nbResultats($resultat)  {
    return mysqli_num_rows($resultat);
  }
  
  public function nomAttribut($resultat, $pos)  {
    $info = mysqli_fetch_field_direct($resultat, $pos);
    return $info ? $info->name : false;
  }

  function __destruct ()
  { if ($this->connexion) mysqli_close($this->connexion); }
}
?>

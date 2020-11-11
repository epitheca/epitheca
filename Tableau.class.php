<?php
// Classe pour la production de tableaux HTML

class Tableau_renomme
{
  // ----   Partie privée : les constantes et les variables
  private $nb_dimensions;
  // Tableau des valeurs à afficher
  private $tableau_valeurs;
  // Tableaux des entêtes
  private $entetes, $options_lig, $options_col;
  // Options de présentation pour la table. A compléter.
  private $options_tables, $couleur_paire, $couleur_impaire, 
    $csg, $affiche_entete, $repetition_ligne=array(),
    $option_dim=array(); 
  // Constante pour remplir les cellules vides
  const VAL_DEFAUT="&nbsp;";

  // Constructeur
  function Tableau ($nb_dimensions=2, $tab_attrs=array())
  {
    // Initialisation des variables privées
    $this->tableau_valeurs = array();
    $this->options_tables=$this->couleur_paire=$this->couleur_impaire="";

    // Initialisation de la dimension. Quelques tests s'imposent...
    $this->nb_dimensions=$nb_dimensions;

    // Initialisation des tableaux d'entêtes pour chaque dimension
    for ($dim=1; $dim <= $this->nb_dimensions; $dim++)
      {
	$this->entetes[$dim] = array();
	$this->affiche_entete[$dim] = TRUE;
      }
    // Attributs de la balise <TABLE>
    $this->ajoutAttributsTable($tab_attrs);
  }
			
  // Méthode ajoutant des attributs HTML pris dans un tableau		       
  public function ajoutAttributsTable($tab_attrs=array())
  {
    foreach ($tab_attrs as $nom_attr => $val_attr)
      $this->options_tables .= " $nom_attr='$val_attr' ";
  }

  // Méthodes définissant les couleurs paire et impaire
  public function setCouleurPaire($couleur)  {
    $this->couleur_paire = $couleur;
  }
  public function setCouleurImpaire($couleur)  {
    $this->couleur_impaire = $couleur;
  }
  
  // Méthode permettant d'afficher ou non un entête
  public function setAfficheEntete($dim, $bool)  {
    $this->affiche_entete[$dim] = $bool;
  }

  // Méthode permettant de répéter n fois l'affichage d'une ligne
  public function setRepetitionLigne($dim, $cle, $nb_repetitions)  {
    $this->repetition_ligne[$dim][$cle] = $nb_repetitions;
  }

  // Méthode indiquant des options pour une ligne ou une colonne
  public function setOption ($dim, $cle, $options=array())  {
    foreach ($options as $option => $valeur)
      $this->options[$dim][$cle][$option] = $valeur;
  }

  // Méthode permettant d'afficher le coin supérieur gauche
  public function setCoinSuperieurGauche($texte)  {
    $this->csg = $texte;
  }

  // Méthode définissant des attributs pour les entêtes, lignes,
  // colonnes: à faire!!
  public function ajoutAttributsEntete($options) {}
  public function ajoutAttributsLigne($cle_ligne, $options) {}
  public function ajoutAttributsColonne($cle_colonne, $options) {}

  // TABLEAU A DEUX DIMENSIONS: ajout d'une valeur dans une cellule
  public function ajoutValeur($cle_ligne, $cle_colonne, $valeur)
  {
    // Maintenance des entêtes
    if (!array_key_exists($cle_ligne, $this->entetes[1])) 
      $this->entetes[1][$cle_ligne] = $cle_ligne;
    if (!array_key_exists($cle_colonne, $this->entetes[2])) 
      $this->entetes[2][$cle_colonne] = $cle_colonne;

    // Stockage de la valeur
    $this->tableau_valeurs[$cle_ligne][$cle_colonne] = $valeur;
  }

  // TABLEAU A N DIMENSIONS: ajout d'une valeur dans une cellule
  // Le premier paramètre est un tableau qui contient les clés.
  // Par exemple: array(1=>"Cle1", 2=>"Cle2", 3=>"Cle3")
  public function ajoutValeurDimN($position, $valeur)
  {
    // On construit les coordonnées au fur et à mesure
    $coord  = "";

    for ($dim=1; $dim <= $this->nb_dimensions; $dim++)
      {
	$cle = $position[$dim];
	// Par défaut, les entêtes valent la clé (si elles n'existent pas)
	if (!array_key_exists($cle, $this->entetes[$dim])) 
	  $this->entetes[$dim][$cle] = $cle;
	
	$coord .= "['$cle']";
      }
    // On construit la commande et on l'exécute
    eval ("\$this->tableau_valeurs$coord='$valeur';");
  }

  // Méthode définissant un entête, avec texte
  public function ajoutEntete($dimension, $cle, $texte)
  {
    // Stockage de la chaîne servant d'entête
    $this->entetes[$dimension][$cle] = $texte;
  }

  // Production du tableau HTML: ne marche qu'en dimension 2!!
  // A FAIRE: généraliser cette fonction pour obtenir les
  // tableaux A, B, C, D présentés dans le livre
  public function tableauHTML()
  {
    $chaine = $ligne = "";
    /* Pour afficher les tableaux d'entêtes. 
    print_r ($this->entetes[1]);
    print_r ($this->entetes[2]);  */

    // Est-ce qu'on affiche le coin supérieur gauche?
    if ($this->affiche_entete[1]) $ligne = "<th>$this->csg</th>";

    // Création des entêtes de colonnes (dimension 2)
    if ($this->affiche_entete[2])
      {
	foreach ($this->entetes[2] as $cle => $texte) 
	  $ligne .= "<TH>$texte</TH>";
	
	// Ligne des entêtes.
	$chaine = "<TR>$ligne</TR>\n";
      }

    $i=0;
    // Boucles imbriquées sur les deux tableaux de clés
    foreach ($this->entetes[1] as $cle_lig => $enteteLig) // Lignes
      {
	if ($this->affiche_entete[1])
	  $ligne = "<TH>$enteteLig</TH>";
	else
	  $ligne = "";

	$i++;

	foreach ($this->entetes[2] as $cle_col => $enteteCol) // Colonnes
	  {
	    // On prend la valeur si elle existe, sinon le défaut
	    if (isSet($this->tableau_valeurs[$cle_lig][$cle_col]))
	      $valeur = $this->tableau_valeurs[$cle_lig][$cle_col];
	    else
	      $valeur = self::VAL_DEFAUT;

	    // On place la valeur dans une cellule
	    $ligne .= "<TD>$valeur</TD>\n";
	  }
	// Eventuellement on tient compte de la couleur
	if ($i % 2 == 0 and !empty($this->couleur_paire))
	  $options_lig = " BGCOLOR='$this->couleur_paire' ";
	else if ($i % 2 == 1 and !empty($this->couleur_impaire))
	  $options_lig = " BGCOLOR='$this->couleur_impaire' ";
	else $options_lig = "";

	// Doit-on appliquer une option?
	if (isSet($this->options[1][$cle_lig]))
	  foreach ($this->options[1][$cle_lig] as $option => $valeur)
	    $options_lig .= " $option='$valeur' ";
	$ligne = "<TR$options_lig>$ligne</TR>\n";

	// Prise en compte de la demande de répétition d'une ligne
	if (isSet($this->repetition_ligne[1][$cle_lig]))
	  {
	    $rligne = "";
	    for ($i=0; $i < $this->repetition_ligne[1][$cle_lig]; $i++)
	      $rligne .= $ligne;
	    $ligne = $rligne;
	  }
	// On ajoute la ligne à la chaîne
	$chaine .= $ligne; 
      }
    // Placement dans la balise TABLE,  et retour
    return  "<TABLE $this->options_tables>\n$chaine</TABLE>\n";
  }
}			
?>

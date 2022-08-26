<?php

function csv_obs ($requete, $code_obs, $bd)
{
		
//Création du nom du fichier
$nom_fichier="$code_obs.csv";
$url=CHEMIN."telechargements/$nom_fichier";

// sup. du fichier s'il existe
if (file_exists($url)) unlink ($url);

//Création du fichier
$fp = fopen($url, 'w');
chmod ($url, 0777);

//Ajout du titre des colonnes
$liste=array('numero', 'date','longitude','latitude','espece','effectif','sexe','Stade et développement','Condition observation','Observateur 1','Observateur 2','Observateur 3','Observateur 4', 'Remarque sur la donnée','Code corine 1','Code corine 1','Code corine 1','Code corine 1','route','riviere','vent','conditions climatiques','température','remarques','responsable');
fputcsv ($fp , $liste, ';');

//Comptage des données
$nbr = mysqli_num_rows($bd->execRequete($requete));

//Ouverture du script pour la barre de progression
echo "<script>";
echo "document.getElementById('contenant').style.display = \"block\";";
echo "</script>";
ob_start();
ob_flush();
flush();
ob_flush();
flush(); 

 $i=0;
//Lancement de la requête
$resultat = $bd->execRequete ($requete."LIMIT 0,5000");
while ($bo = $bd->objetSuivant ($resultat))
{ 
$i++;
$indice = ( ($i+1)*100 ) / $nbr;
progression($indice);

	//Transformation de la date
	$date=dateservertosite ($bo->date);
	
    //Extraction de la date
    $espece=ChercheSpavecCode($bo->espece, $bd, FORMAT_OBJET);
    $espece=$espece->NOM_VALIDE;
	
	//Recherche de l'abondance
	$abondance=$bo->abondance;
	
	//Recherche du sexe
	if (is_null($bo->sexe)) $sexe="";
	else
	{
	$sexee=$bd->execRequete ("SELECT sexe FROM sexe WHERE code_sexe='$bo->sexe'");
	while ($sexeee=$bd->objetSuivant ($sexee))
     $sexe= $sexeee->sexe;
 }
     //Recherche info 1
     if (is_null($bo->info_1)) $complement_1="";
	{
	 $infoe1=$bd->execRequete ("SELECT information, code FROM information WHERE code='$bo->info_1'");
	 while ($infoee=$bd->objetSuivant ($infoe1))
     $complement_1=$infoee->information;
}

	//Recherche info 2
	if (is_null($bo->info_2)) $complement_2="";
	{
	$infoe2=$bd->execRequete ("SELECT information, code FROM information_2 WHERE code='$bo->info_2'");
	while ($infoeee=$bd->objetSuivant ($infoe2))
    $complement_2=$infoeee->information;
}

    //Transformation des codes corines
    if (is_null($bo->corine_1)) $code_cor1="";
    else
    {
    $code_cor_1 = Cherchecorine ($bo->corine_1, $bd, $format=FORMAT_OBJET);
    $code_cor1=$code_cor_1->LB_HAB_FR;
	}

	if (is_null($bo->corine_2)) $code_cor2="";
	else
	{
	$code_cor_2 = Cherchecorine ($bo->corine_2, $bd, $format=FORMAT_OBJET);
	$code_cor2=$code_cor_2->LB_HAB_FR;
	}
	if (is_null($bo->corine_3)) $code_cor3="";
	else
	{
	$code_cor_3 = Cherchecorine ($bo->corine_3, $bd, $format=FORMAT_OBJET);
	$code_cor3=$code_cor_3->LB_HAB_FR;
	}
	if (is_null($bo->corine_4)) $code_cor4="";
	else
	{
	$code_cor_4 = Cherchecorine ($bo->corine_4, $bd, $format=FORMAT_OBJET);
	$code_cor4=$code_cor_4->LB_HAB_FR;
}

	//Transformation des codes observateurs
	if(is_null($bo->obs_1)) $obs1="";
	else
	{
	$obs_1 = Chercheobservateursaveccode ($bo->obs_1, $bd, $format=FORMAT_OBJET);
	$obs1="$obs_1->prenom $obs_1->nom";
}
	if(is_null($bo->obs_2)) $obs2="";
	else
	{
	$obs_2 = Chercheobservateursaveccode ($bo->obs_2, $bd, $format=FORMAT_OBJET);
	$obs2="$obs_2->prenom $obs_2->nom";
}
		if(is_null($bo->obs_3)) $obs3="";
	else
	{
	$obs_3 = Chercheobservateursaveccode ($bo->obs_3, $bd, $format=FORMAT_OBJET);
	$obs3="$obs_3->prenom $obs_3->nom";
}
	$obs4=$bo->origineDonnee; 
	 
	//Transformation des conditions climatiques
	 if(is_null($bo->meteo)) $conditions_climatiques="";
    else {
	$cond_climb=$bd->execRequete ("SELECT * FROM conditions_climatiques WHERE code_conditions_climatiques='$bo->meteo'");
	while ($cond_clima=$bd->objetSuivant ($cond_climb))
    $conditions_climatiques=$cond_clima->conditions_climatiques;
 }
 
    //Transformation vent
    if(is_null($bo->vent)) $vent="";
    else {
	$ventb=$bd->execRequete ("SELECT * FROM vent WHERE code_vent='$bo->vent'");
	while ($venta=$bd->objetSuivant ($ventb))
    $vent=$venta->vent;
}

    //Transformation des routes
    if(is_null($bo->route)) $route="";
    else {
	$routeb=$bd->execRequete ("SELECT * FROM routes WHERE code_route='$bo->route'");
	while ($routea=$bd->objetSuivant ($routeb))
    $route=$routea->nom;
}
    //Transformation des rivières
    if(is_null($bo->riviere)) $riviere="";
    else {
	$riviereb=$bd->execRequete ("SELECT * FROM rivieres WHERE code_riviere='$bo->riviere'");
	while ($rivierea=$bd->objetSuivant ($riviereb))
    $riviere=$rivierea->nom;
}
  
    //Traitemant des apostrophes
    $temp=$bo->temperature;
	$remarques=$bo->remarques;
	if ($bo->remarques=='') $remarques='NULL';
		else $remarques=str_replace(array("\r\n", "\n", "\r"), ' ', $remarques);
		
		//Recherche du type de données
	if ($bo->type_donnees <> "CLASSIQUE")
	{
		if ($bo->type_donnees == "DETERMINATEUR") $remarques_type_donnee="Le déterminateur est $obs_1->prenom $obs_1->nom, le collecteur est $obs_2->prenom $obs_2->nom";
		if ($bo->type_donnees == "COLLECTEUR") $remarques_type_donnee="Le collecteur est $obs_1->prenom $obs_1->nom, le déterminateur est $obs_2->prenom $obs_2->nom";
		if ($bo->type_donnees == "RAPPORTEUR") $remarques_type_donnee="Le rapporteur est $obs_1->prenom $obs_1->nom";
	}
else $remarques_type_donnee="";

$liste=array($bo->numero,$date,$bo->longitude,$bo->latitude,$espece,$abondance,$sexe,$complement_1,$complement_2,$obs1,$obs2,$obs3,$obs4, $remarques_type_donnee, $code_cor1,$code_cor2,$code_cor3,$code_cor4,$route,$riviere,$vent,$conditions_climatiques,$temp,$remarques);
fputcsv ( $fp , $liste, ';');
}

fclose ($fp);

if ($nbr>=5000)
{
//Création du nom du second fichier
$nom_fichier="02_$nom_fichier";
$url2=CHEMIN."/telechargements/$nom_fichier";

//Sup du fichier
if (file_exists($url2)) unlink ($url2);

//Création du fichier
$fp = fopen($url2, 'w');
chmod ($url2, 0777);


//Ajout du titre des colonnes
$liste=array('numero','date','longitude','latitude','espece','effectif','sexe','Stade et développement','Condition observation','Observateur 1','Observateur 2','Observateur 3','Observateur 4', 'remarque sur la données','Code corine 1','Code corine 1','Code corine 1','Code corine 1','route','riviere','vent','conditions climatiques','température','remarques','responsable');
fputcsv ($fp , $liste, ';');
	
$resultat = $bd->execRequete ($requete."LIMIT 5000,10000");
while ($bo = $bd->objetSuivant ($resultat))
{ 	
	$i++;
$indice = ( ($i+1)*100 ) / $nbr;
progression($indice);
	
	//Transformation de la date
	$date=ChangeFormatDate ($bo->date,'fr');
	
	//Recherche de l'espèce
	$code_classe_ordre = substr($bo->espece,0,2);
		//Extraction du code
		$cl=$bd->execRequete ("SELECT Code_classe_ordre FROM classe_ordre WHERE Abrevclasseordre='$code_classe_ordre'");
		while ($classe_ordre_extr=$bd->objetSuivant ($cl))
		$classe_ordre = $classe_ordre_extr->Code_classe_ordre;
		//Extraction de l'espèce
		$sp=$bd->execRequete ("SELECT nom, principal FROM $classe_ordre WHERE principal = 'oui' AND code_espece='$bo->espece' ");
		while ($spscien=$bd->objetSuivant ($sp))
		$espece=$spscien->nom;
	
	//Recherche de l'abondance
	$abondance=$bo->abondance;
	
	//Recherche du sexe
	$sexee=$bd->execRequete ("SELECT sexe FROM sexe WHERE code_sexe='$bo->sexe'");
	while ($sexeee=$bd->objetSuivant ($sexee))
     $sexe= $sexeee->sexe;
     
     //Recherche info 1
	 $infoe1=$bd->execRequete ("SELECT information, code FROM information WHERE code='$bo->info_1'");
	 while ($infoee=$bd->objetSuivant ($infoe1))
     $complement_1=$infoee->information;

	//Recherche info 2
	$infoe2=$bd->execRequete ("SELECT information, code FROM information_2 WHERE code='$bo->info_2'");
	while ($infoeee=$bd->objetSuivant ($infoe2))
    $complement_2=$infoeee->information;
    
    //Transformation des codes corines
	$code_cor_1 = Cherchecorine ($bo->corine_1, $bd, $format=FORMAT_OBJET);
	$code_cor_2 = Cherchecorine ($bo->corine_2, $bd, $format=FORMAT_OBJET);
	$code_cor_3 = Cherchecorine ($bo->corine_3, $bd, $format=FORMAT_OBJET);
	$code_cor_4 = Cherchecorine ($bo->corine_4, $bd, $format=FORMAT_OBJET);

	//Transformation des codes observateurs
	$obs_1 = Chercheobservateursaveccode ($bo->obs_1, $bd, $format=FORMAT_OBJET);
	$obs_2 = Chercheobservateursaveccode ($bo->obs_2, $bd, $format=FORMAT_OBJET);
	$obs_3 = Chercheobservateursaveccode ($bo->obs_3, $bd, $format=FORMAT_OBJET);
	$obs4=$bo->obs_4; 
	 
	//Transformation des conditions climatiques
	$cond_climb=$bd->execRequete ("SELECT * FROM conditions_climatiques WHERE code_conditions_climatiques='$bo->meteo'");
	while ($cond_clima=$bd->objetSuivant ($cond_climb))
    $conditions_climatiques=$cond_clima->conditions_climatiques;
 
    //Transformation vent
	$ventb=$bd->execRequete ("SELECT * FROM vent WHERE code_vent='$bo->vent'");
	while ($venta=$bd->objetSuivant ($ventb))
    $vent=$venta->vent;
    
    //Transformation des routes
	$routeb=$bd->execRequete ("SELECT * FROM routes WHERE code_route='$bo->route'");
	while ($routea=$bd->objetSuivant ($routeb))
    $route=$routea->nom;
    
    //Transformation des routes
	$riviereb=$bd->execRequete ("SELECT * FROM rivieres WHERE code_riviere='$bo->riviere'");
	while ($rivierea=$bd->objetSuivant ($riviereb))
    $riviere=$rivierea->nom;
 
    //Traitemant des apostrophes
    $temp=$bo->temperature;
	$remarques=$bo->remarques;
	$remarques=str_replace(array("\r\n", "\n", "\r"), ' ', $remarques);
	$code_cor1=$code_cor_1->nom;
	$code_cor2=$code_cor_2->nom;
	$code_cor3=$code_cor_3->nom;
	$code_cor4=$code_cor_4->nom;
	
	//Recherche des responsables
	if ($bo->valide_par <> "")
	{
	$responsable = Chercheobservateursaveccode ($bo->valide_par, $bd, $format=FORMAT_OBJET);
	$responsable = "$responsable->prenom $responsable->nom";
}
	else $responsable=utf8_decode ("Non validée");
	
	//Recherche du type de données
	if ($bo->type_donnees <> "CLASSIQUE")
	{
		if ($bo->type_donnees == "DETERMINATEUR") $remarques_type_donnee="Le déterminateur est $obs_1->prenom $obs_1->nom, le collecteur est $obs_2->prenom $obs_2->nom";
		if ($bo->type_donnees == "COLLECTEUR") $remarques_type_donnee="Le collecteur est $obs_1->prenom $obs_1->nom, le déterminateur est $obs_2->prenom $obs_2->nom";
		if ($bo->type_donnees == "RAPPORTEUR") $remarques_type_donnee="Le rapporteur est $obs_1->prenom $obs_1->nom";
	}
else $remarques_type_donnee="";	
	
$liste=array($bo->numero,$date,$bo->longitude,$bo->latitude,$espece,$abondance,$sexe,$complement_1,$complement_2,"$obs_1->prenom $obs_1->nom","$obs_2->prenom $obs_2->nom","$obs_3->prenom $obs_3->nom",$obs4, $remarques_type_donnee, $code_cor1,$code_cor2,$code_cor3,$code_cor4,$route,$riviere,$vent,$conditions_climatiques,$temp,$remarques,$responsable);
$liste=toUTF8($liste);
fputcsv ( $fp , $liste, ';');
}
fclose ($fp);

//Création du nom du zip	
$urlzip=CHEMIN."/telechargements/$code_obs.zip";

//Suppression du zip s'il existe
if (file_exists($urlzip)) unlink ($urlzip);

$zip = new ZipArchive(); 
if($zip->open($urlzip) == TRUE)
if($zip->open($urlzip, ZipArchive::CREATE) === true)
{
$zip->addFile($url,'fichier_1.csv');
$zip->addFile($url2,'fichier_2.csv');
$zip->close();

//Modification des droits du zip
chmod ($urlzip, 0777);
}

}

//Fermeture de la barre
echo "<script>";
echo "document.getElementById('contenant').style.display = \"none\";";
echo "</script>";
}

?>

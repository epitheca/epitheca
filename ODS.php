<?php
function csv_obs($requete, $code_obs, $bd)
{
    // Configuration du fichier .xls (XML SpreadsheetML)
    $nom_fichier = "$code_obs.xls";
    $url = CHEMIN . "telechargements/$nom_fichier";

    if (file_exists($url)) unlink($url);

    $fp = fopen($url, 'w');
    if (!$fp) return;

    // Signature UTF-8 pour la compatibilité Excel/Calc
    fputs($fp, "\xEF\xBB\xBF"); 
    
    // 1. Entête XML Tableur
    $header = '<?xml version="1.0" encoding="UTF-8"?>
    <?mso-application progid="Excel.Sheet"?>
    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
     xmlns:o="urn:schemas-microsoft-com:office:office"
     xmlns:x="urn:schemas-microsoft-com:office:excel"
     xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
     xmlns:html="http://www.w3.org/TR/REC-html40">
     <Worksheet ss:Name="Donnees_Epitheca">
     <Table>';
    fputs($fp, $header);

    // 2. Titres des colonnes (basé sur CSV.php)
    $titres = array('numéro', 'date','longitude','latitude','espèce','effectif','sexe','Stade et développement','Condition observation','Observateur 1','Observateur 2','Observateur 3','Observateur 4', 'Remarque sur la donnée','Code corine 1','Code corine 2','Code corine 3','Code corine 4','route','rivière','vent','conditions climatiques','température','remarques','responsable');
    
    fputs($fp, '<Row ss:StyleID="bold">');
    foreach($titres as $titre) {
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($titre) . '</Data></Cell>');
    }
    fputs($fp, '</Row>');

    // Barre de progression
    $nbr_res = $bd->execRequete($requete);
    $nbr = mysqli_num_rows($nbr_res);
    echo "<script>document.getElementById('contenant').style.display = 'block';</script>";

    // 3. Boucle de données (Logique extraite de CSV.php)
    $i = 0;
    $resultat = $bd->execRequete($requete . " LIMIT 0,5000");
    while ($bo = $bd->objetSuivant($resultat)) {
        $i++;
        $indice = (($i + 1) * 100) / $nbr;
        progression($indice);

        // --- TRAITEMENTS DES DONNÉES (Identiques à CSV.php) ---
        $date = dateservertosite($bo->date);
        
        $sp_obj = ChercheSpavecCode($bo->espece, $bd, FORMAT_OBJET);
        $espece_nom = $sp_obj->NOM_VALIDE ?? 'Inconnue';

        // Sexe
        $sexe = "";
        if (!is_null($bo->sexe)) {
            $sexee = $bd->execRequete("SELECT sexe FROM sexe WHERE code_sexe='$bo->sexe'");
            if ($sexeee = $bd->objetSuivant($sexee)) $sexe = $sexeee->sexe;
        }

        // Compléments 1 & 2
        $complement_1 = "";
        if (!is_null($bo->info_1)) {
            $infoe1 = $bd->execRequete("SELECT information FROM information WHERE code='$bo->info_1'");
            if ($infoee = $bd->objetSuivant($infoe1)) $complement_1 = $infoee->information;
        }
        $complement_2 = "";
        if (!is_null($bo->info_2)) {
            $infoe2 = $bd->execRequete("SELECT information FROM information_2 WHERE code='$bo->info_2'");
            if ($infoeee = $bd->objetSuivant($infoe2)) $complement_2 = $infoeee->information;
        }

        // Corine
        $c1 = (!is_null($bo->corine_1)) ? Cherchecorine($bo->corine_1, $bd, FORMAT_OBJET)->LB_HAB_FR : "";
        $c2 = (!is_null($bo->corine_2)) ? Cherchecorine($bo->corine_2, $bd, FORMAT_OBJET)->LB_HAB_FR : "";
        $c3 = (!is_null($bo->corine_3)) ? Cherchecorine($bo->corine_3, $bd, FORMAT_OBJET)->LB_HAB_FR : "";
        $c4 = (!is_null($bo->corine_4)) ? Cherchecorine($bo->corine_4, $bd, FORMAT_OBJET)->LB_HAB_FR : "";

        // Observateurs
        $obs1 = ""; if(!is_null($bo->obs_1)) { $o1 = Chercheobservateursaveccode($bo->obs_1, $bd, FORMAT_OBJET); $obs1 = "$o1->prenom $o1->nom"; }
        $obs2 = ""; if(!is_null($bo->obs_2)) { $o2 = Chercheobservateursaveccode($bo->obs_2, $bd, FORMAT_OBJET); $obs2 = "$o2->prenom $o2->nom"; }
        $obs3 = ""; if(!is_null($bo->obs_3)) { $o3 = Chercheobservateursaveccode($bo->obs_3, $bd, FORMAT_OBJET); $obs3 = "$o3->prenom $o3->nom"; }
        $obs4 = $bo->origineDonnee ?? '';

        // Conditions, Routes, Rivières
        $meteo = ""; if(!is_null($bo->meteo)) { $res = $bd->execRequete("SELECT conditions_climatiques FROM conditions_climatiques WHERE code_conditions_climatiques='$bo->meteo'"); if($r = $bd->objetSuivant($res)) $meteo = $r->conditions_climatiques; }
        $vent = ""; if(!is_null($bo->vent)) { $res = $bd->execRequete("SELECT vent FROM vent WHERE code_vent='$bo->vent'"); if($r = $bd->objetSuivant($res)) $vent = $r->vent; }
        $route = ""; if(!is_null($bo->route)) { $res = $bd->execRequete("SELECT nom FROM routes WHERE code_route='$bo->route'"); if($r = $bd->objetSuivant($res)) $route = $r->nom; }
        $riviere = ""; if(!is_null($bo->riviere)) { $res = $bd->execRequete("SELECT nom FROM rivieres WHERE code_riviere='$bo->riviere'"); if($r = $bd->objetSuivant($res)) $riviere = $r->nom; }

        // Responsable validation
        $responsable = "Non validée";
        if (!empty($bo->valide_par)) {
            $resp = Chercheobservateursaveccode($bo->valide_par, $bd, FORMAT_OBJET);
            $responsable = "$resp->prenom $resp->nom";
        }

        // Remarques de type de données
        $remarque_donnee = "";
        if ($bo->type_donnees <> "CLASSIQUE") {
            if ($bo->type_donnees == "DETERMINATEUR") $remarque_donnee = "Déterminateur : $obs1, Collecteur : $obs2";
            else if ($bo->type_donnees == "COLLECTEUR") $remarque_donnee = "Collecteur : $obs1, Déterminateur : $obs2";
            else if ($bo->type_donnees == "RAPPORTEUR") $remarque_donnee = "Rapporteur : $obs1";
        }
        
        // Nettoyage remarques (Fix PHP 8+)
        $remarques_finales = str_replace(array("\r\n", "\n", "\r"), ' ', $bo->remarques ?? '');

        // 4. Écriture de la ligne XML
        fputs($fp, '<Row>');
        fputs($fp, '<Cell><Data ss:Type="Number">' . $bo->numero . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($date) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="Number">' . ($bo->longitude ?? 0) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="Number">' . ($bo->latitude ?? 0) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($espece_nom) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($bo->abondance ?? '') . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($sexe) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($complement_1) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($complement_2) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($obs1) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($obs2) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($obs3) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($obs4) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($remarque_donnee) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($c1) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($c2) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($c3) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($c4) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($route) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($riviere) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($vent) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($meteo) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($bo->temperature ?? '') . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($remarques_finales) . '</Data></Cell>');
        fputs($fp, '<Cell><Data ss:Type="String">' . htmlspecialchars($responsable) . '</Data></Cell>');
        fputs($fp, '</Row>');
    }

    // 5. Fermeture
    fputs($fp, '</Table></Worksheet></Workbook>');
    fclose($fp);
    echo "<script>document.getElementById('contenant').style.display = 'none';</script>";
}
?>

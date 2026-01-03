<?php
require("Util.php");
$bd = Connexion(NOM, PASSE, BASE, SERVEUR);

// Initialisation des variables
$email = $_GET['m'] ?? '';
$u_param = $_GET['u'] ?? '';
$secret = (strlen($u_param) > 3) ? substr($u_param, 3) : '';

// Nettoyage pour la requête SQL
$email_sql = $bd->prepareChaine($email);
$secret_sql = $bd->prepareChaine($secret);

$control = "Votre demande n'est pas sécurisée, le lien que vous avez suivi n'est pas valable.";

if (!empty($email) && !empty($secret)) {
    // Vérification de la légitimité
    $select = "SELECT * FROM observateurs_temporaire WHERE mail='$email_sql' AND secret='$secret_sql'";
    $resultat = $bd->execRequete($select);
    
    if ($bo = $bd->objetSuivant($resultat)) {
        // Vérification du temps (30 minutes = 1800 secondes)
        $timestamp_demande = strtotime($bo->timestamp);
        $timestamp_actuel = time();
        
        if (($timestamp_demande + 1800) < $timestamp_actuel) {
            $control = "La demande a expiré (valable 30 minutes).";
        } else {
            $control = "yes";
        }
    }
}
?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" > 
<link rel="stylesheet" media="screen and (min-width: 1025px)" href="<?php echo CHEMIN_URL;?>Css_largescreen.css" type="text/css" />
<link rel="stylesheet" media="screen and (max-width: 1024px)" href="<?php echo CHEMIN_URL;?>Css_smallscreen.css" type="text/css" />
<link rel='stylesheet' HREF='<?php echo CHEMIN_URL;?>Css.css' TYPE='text/css'>
<link rel='stylesheet' HREF='<?php echo CHEMIN_URL;?>Css_fenetre_nodal.css' TYPE='text/css'>

<link rel="icon" href="images/favicon.ico" />
<TITLE>epitheca.fr - vos données naturalistes sous votre contrôle</TITLE>
</HEAD>
<BODY>

    <div class="connexion-gauche">
            <IMG SRC="images/logo.png" ALT="Logo Epitheca" width="90%" >
            <p style="font-size:1vw">Vos données naturalistes sous votre contrôle</p>
    </div>
    
    <div class="connexion-droit">
        <span class="titre-connexion">Création de votre compte</span>
        
        <div class="connexion-texte">
            <?php 
            if ($control !== "yes") {
                echo "<p style='color:red;'>" . $control . "</p>";
            } else {
            ?>
        </div>
        
        <br><br>
        
        <form id="nouvelobs" action="Session_creation_compte_5_validation.php" method="post" autocomplete="off" >
            <input type="text" class="session" required placeholder="Saisissez votre NOM" name="nom"><br>
            <input type="text" class="session" required placeholder="Saisissez votre Prénom" name="prenom"><br>
            
            <input type="password" class="session" name="pass" id="pass" required 
                   placeholder="Choisissez un mot de passe" 
                   pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" 
                   title="8 caractères minimum : Chiffres, Majuscules, Minuscules et un caractère spécial.">
            <br>
            
            <input type="hidden" name="mail" value="<?php echo htmlspecialchars($email); ?>" />
            
            <input type="submit" id="button" name="valider" value="Finaliser l'inscription" class="vert" />
        </form>
        
        <?php } ?>
    </div>
    
</BODY>
</HTML>


<?php
require("Util.php");
// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);
//Capture des valeurs
if (isset($_GET['m'])) $email = $_GET['m'];
if (isset($_GET['u']))  $secret = substr($_GET['u'],3);

 $control="Votre demande n'est pas sécurisée, le lien que vous avez suivi n'est pas valable.";
 
 if (isset($email))
 {
 //Vérification de la légitimité de la requête;
$select  = "SELECT * FROM observateurs_temporaire WHERE mail='$email' AND secret='$secret'";
			$resultat = $bd->execRequete ($select);
			while ($bo = $bd->objetSuivant ($resultat))
				{
				//Vérification du temps
				$timestamp_demande=strtotime($bo->timestamp);
				$timestamp_actuel=time();
				if ($timestamp_demande+1800<$timestamp_actuel) $control="La demande a expiré.";
				else $control="yes";
				}
}
if ($control=="yes")
{
?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" > 
<link rel="stylesheet" media="screen and (min-width: 1025px)" href="<?php echo CHEMIN_URL;?>Css_largescreen.css" type="text/css" />
<link rel="stylesheet" media="screen and (max-width: 1024px)" href="<?php echo CHEMIN_URL;?>Css_smallscreen.css" type="text/css" />
<link rel='stylesheet' HREF='<?php echo CHEMIN_URL;?>Css.css' TYPE='text/css'>
<link rel='stylesheet' HREF='<?php echo CHEMIN_URL;?>Css_fenetre_nodal.css' TYPE='text/css'>

<!--Captcha -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $CLE_reCAPTCHA_site; ?>"></script>

<script>
        grecaptcha.ready(function () {
            grecaptcha.execute('<?php echo $CLE_reCAPTCHA_site; ?>', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
</script>
     
<!-- Insertion de l'icone-->
<link rel="icon" href="images/favicon.ico" />
<TITLE>epitheca.fr - vos données naturalistes sous votre contrôle</TITLE>
</HEAD>
<BODY>

    <div class="connexion-gauche">
			<IMG SRC="images/logo.png" ALT="Chargement..." width="90%" >
			<p style="font-size:1vw">Vos données naturalistes sous votre contrôle</p>
	</div>
    <div class="connexion-droit">
        <span class="titre-connexion">Modification de votre mot de passe</span>
        <div class="connexion-texte">
			<?php 
			if ($control<>"yes")
			{
		echo $control;
		}
		else
		{
		?>
    </div><br><br>
    <form id="nouvelobs" action="Session_creation_compte_mot_de_passe_5.php" method="post" >
    <input type="password" class="session" name="pass" id="pass" required placeholder="Choisissez un mot de passe" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Veuillez saisir un identifiant de 8 caractères avec des chiffres, des MAJUSCULES, des minuscules et un caractère spécial.">
    <br>
    <input type="hidden" name="token" id="recaptchaResponse" />
    <input type="hidden" name="mail" value=<?php echo $email; ?> />
    <input type="submit" id="button" name="valider" value="Modifier le mot de passe"/>
    </form>
       <?php
   }
   ?>
    </div>
    
    </body>
</html>
<?php
}
?>


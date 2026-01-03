<?php
require("Util.php");
?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" > 
<link rel="stylesheet" media="screen and (min-width: 1025px)" href="<?php echo CHEMIN_URL;?>Css_largescreen.css" type="text/css" />
<link rel="stylesheet" media="screen and (max-width: 1024px)" href="<?php echo CHEMIN_URL;?>Css_smallscreen.css" type="text/css" />
<link rel='stylesheet' HREF='<?php echo CHEMIN_URL;?>Css.css' TYPE='text/css'>
<link rel='stylesheet' HREF='<?php echo CHEMIN_URL;?>Css_fenetre_nodal.css' TYPE='text/css'>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<!-- Insertion de l'icone-->
<link rel="icon" href="images/favicon.ico" />
<TITLE>epitheca.fr - vos données naturalistes sous votre controle</TITLE>
</HEAD>
<BODY>
	      
    <div class="connexion-gauche">
			<a href="https://epitheca.fr"><IMG SRC="images/logo.png" ALT="Chargement..." width="90%" ></a>
			<p style="font-size:1vw">Vos données naturalistes sous votre controle</p>
	</div>
    <div class="connexion-droit">
        <span class="titre-connexion">Créer un compte</span>
        <div class="connexion-texte">
       La création de compte est gratuite et libre. Demander l'ouverture d'un compte suppose que vous avez <a href=Charte.php>lu, compris et accepté la charte d'utilisation.</a>
       </div><br><br>
    
    <form id="nouvelobs" action="Session_creation_compte_2_verification.php" method="post" >
    <input type="email" class="session" name="email" id="email" placeholder="Votre adresse de courriel">
	
	<div class="cf-turnstile" data-sitekey="<?php echo CL_TURNSTILE_SITEKEY; ?>"></div>
    
    <input type="submit" class="vert" id="button" name="valider" value="Ouvrir un compte"/>
    </form>
       <br><br><br><br>
    </div>
    
    </body>
</html>

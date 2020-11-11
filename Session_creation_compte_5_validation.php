<?php
require("Util.php");
// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);
//Capture des valeurs
    $email   = $_POST['mail'];
    $pass = $_POST['pass'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

//Supression de la table temporaire
$requete  = "DELETE FROM observateurs_temporaire WHERE mail='$email'";
$req = $bd->execRequete ($requete);   

//Ajout de l'observetur dans la base
// On va quand même vérifier que cet email n'est pas déjà inséré
      $controleemail=Chercheobservateurs($email, $bd, FORMAT_OBJET);
      if (isset($controleemail))
      {
      if ($email == $controleemail->email)
	{
		?>
	<script type="text/javascript">
			<!--
			window.alert("<?php echo "Un observateur avec cet email existe déjà." ?>");
			window.location.replace("Observateurs_maj_admin.php?mode=MAJ&codeobsamaj=<?php echo $controleemail->code_obs;?>");
			//-->
			</script>
		<?php
	}}
	   else
	{
 $motCrypte = md5 ($pass);
	  $requete  = "INSERT INTO observateurs (nom, prenom, email, "
	    . "mot_de_passe) "
	    . "VALUES ('$nom', '$prenom', "
	    . "'$email', '$motCrypte')";
	  $req = $bd->execRequete ($requete);
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

<!--Captcha -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js?render=<?php CLE_reCAPTCHA_site ?>"></script>

<script>
        grecaptcha.ready(function () {
            grecaptcha.execute('<?php CLE_reCAPTCHA_site ?>', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
</script>
     
<!-- Insertion de l'icone-->
<link rel="icon" href="images/favicon.ico" />
<TITLE>epitheca.fr - vos données naturalistes sous votre controle</TITLE>
</HEAD>
<BODY>

    <div class="connexion-gauche">
			<IMG SRC="images/logo.png" ALT="Chargement..." width="90%" >
			<p style="font-size:1vw">Vos données naturalistes sous votre controle</p>
	</div>
    <div class="connexion-droit">
        <span class="titre-connexion">Création de votre compte</span>
       Votre compte est bien créé !<br>
       <form method="post" action="index.php">
		<input type="submit" value="C'est parti ! Je me connecte !" class="vert"><br><br>
		</form>
       <?php
   //Création du mail  
$subject="epitheca.fr - Bienvenue !";

$message="
<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' > 
</HEAD>
<BODY>
<center>Base de données naturalistes epitheca.fr<br>
<img src=\"https://epitheca.fr/images/logo200pt.png\"></center><br>

Bonjour,<br>
Bienvenue sur la base de données naturalistes qui respecte votre liberté !
<br>
<a href='https://epitheca.fr'>epitheca.fr</a> est une base de données naturalistes basée sur du code libre qui utilise les standards nationaux définis pour la gestion de la nature.
<br>
Nous vous invitons à prendre connaissance de <a hef='https://epitheca.fr/Charte.php'>la charte d'utilisation.</a>

Je reste à votre disposition.<br>
<br><br>

Mathieu MONCOMBLE
<br>
</BODY>
</HTML>	
";

$to = $email;
	
// Version MINE
$headers = "MIME-Version: 1.0\n";
 
// en-têtes expéditeur
$headers .= "From : $mail_administrateur\n";
 
// en-têtes adresse de retour
$headers .= "Reply-to : $mail_administrateur\n";
 
// personnes en copie
$headers .= "Bcc : $mail_administrateur\n";
 
// priorité urgente
$headers .= "X-Priority : 3\n";
 
// type de contenu HTML
$headers .= "Content-type: text/html; charset=utf-8\n";
 
// code de transportage
$headers .= "Content-Transfer-Encoding: 8bit\n";
 
 mail($to,$subject,$message, $headers);

   ?>
    </div>
    </body>
</html>



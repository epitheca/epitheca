<?php
require("Util.php");
$bd = Connexion(NOM, PASSE, BASE, SERVEUR);

// 1. Capture et nettoyage immédiat des valeurs (Protection injection SQL)
$email  = $bd->prepareChaine($_POST['mail']);
$pass   = $_POST['pass']; // On ne nettoie pas le pass ici car on va le hasher
$nom    = $bd->prepareChaine($_POST['nom']);
$prenom = $bd->prepareChaine($_POST['prenom']);

// 2. Suppression de la table temporaire
$requete = "DELETE FROM observateurs_temporaire WHERE mail='$email'";
$bd->execRequete($requete);   

// 3. Vérification de l'existence de l'email
$controleemail = Chercheobservateurs($email, $bd, FORMAT_OBJET);

if (isset($controleemail) && $email == $controleemail->email) {
    ?>
    <script type="text/javascript">
        alert("Un observateur avec cet email existe déjà.");
        window.location.replace("https://epitheca.fr");
    </script>
    <?php
    exit; // On arrête le script ici
} else {
    // 4. Hachage sécurisé du mot de passe (PHP 8+)
    $motCrypte = password_hash($pass, PASSWORD_DEFAULT);
    
    $requete = "INSERT INTO observateurs (nom, prenom, email, mot_de_passe) "
             . "VALUES ('$nom', '$prenom', '$email', '$motCrypte')";
    $bd->execRequete($requete);
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
<TITLE>epitheca.fr - Inscription réussie</TITLE>
</HEAD>
<BODY>

    <div class="connexion-gauche">
            <IMG SRC="images/logo.png" ALT="Logo" width="90%" >
            <p style="font-size:1vw">Vos données naturalistes sous votre contrôle</p>
    </div>
    
    <div class="connexion-droit">
        <span class="titre-connexion">Félicitations !</span>
        <p>Votre compte est bien créé !</p>
        <form method="post" action="index.php">
            <input type="submit" value="C'est parti ! Je me connecte !" class="vert"><br><br>
        </form>

<?php
// 5. Envoi du mail de bienvenue (Headers corrigés avec \r\n et constantes)
$subject = "epitheca.fr - Bienvenue !";
$message = "
<html>
<body>
    <div style='text-align:center;'>
        <p>Base de données naturalistes epitheca.fr</p>
        <img src='https://epitheca.fr/images/logo200pt.png'>
    </div>
    <p>Bonjour,</p>
    <p>Bienvenue sur la base de données naturalistes qui respecte votre liberté !</p>
    <p><a href='https://epitheca.fr'>epitheca.fr</a> utilise des standards nationaux et du code libre.</p>
    <p>Consultez la <a href='https://epitheca.fr/Charte.php'>charte d'utilisation</a>.</p>
    <p>À bientôt,<br>Mathieu MONCOMBLE</p>
</body>
</html>";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "From: " . MAIL_ADMIN . "\r\n";
$headers .= "Reply-To: " . MAIL_ADMIN . "\r\n";
$headers .= "Bcc: " . MAIL_ADMIN . "\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";

mail($email, $subject, $message, $headers);
?>
    </div>
</BODY>
</HTML>



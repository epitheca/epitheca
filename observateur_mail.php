<?php
session_start();

require("Util.php");
require_once ("index_fonction.php");
// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

// Contrôle de la session
$session = ControleAcces ("index.php", $_POST, session_id(), $bd);
if (SessionValide ($session, $bd))
{
$observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
$code_obs = "$observateur->code_obs";
}
$pass=$_GET['pass'];
$login=$_GET['email'];

$objet="Votre accès à la base de données naturalistes epitheca.fr";
	
$message="
<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' > 
</HEAD>
<BODY>
<center>Base de données naturalistes epitheca.fr<br>
<img src=\"https://epitheca.fr/images/logo200pt.png\"></center><br>

Bonjour,<br>
Je vous remercie de trouver dans ce courriel vos identifiants vous permettant d'accéder à la base de données naturalistes 
<em>Epitheca.fr</em> : <a href='https://epitheca.fr'>https://epitheca.fr</a><br>
Votre identifiant : <strong>$login</strong><br>
Votre nouveau mot de passe : <strong>$pass</strong> (La casse est importante)<br>
Je vous invite à personnaliser votre mot de passe dès votre première connexion.<br><br>
Je reste à votre disposition pour plus de renseignements.<br>
<br>
$observateur->prenom $observateur->nom
</BODY>
</HTML>	
";


	$to = $login;
	$headers = 'From: ' . $session->email . "\r\n" .
            'Reply-To: ' . $session->email . "\r\n" .
            'Bcc: ' . $session->email . "\r\n" .
            'Content-type: text/html; charset= iso-8859-1' ."\r\n";

mail ($to, $objet, $message, $headers);
?>
<script type="text/javascript">
			<!--
						window.location.replace("Observateurs.php");
			//-->
			</script> 
<?php
?>

<?php
require("Util.php");
// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);
//Capture des valeurs
    $email   = $_POST['mail'];
    $pass = $_POST['pass'];

//Supression de la table temporaire
$requete  = "DELETE FROM observateurs_temporaire WHERE mail='$email'";
$req = $bd->execRequete ($requete);   

//Mise à jour du mot de passe
// On va quand même vérifier que cet email n'est pas déjà inséré
      $controleemail=Chercheobservateurs($email, $bd, FORMAT_OBJET);
      if (isset($controleemail))
      {
      if ($email == $controleemail->email)
	{
		$motCrypte = md5 ($pass);
	  $requete  = "UPDATE observateurs SET mot_de_passe='$motCrypte' WHERE email='$email'"; 
	  $req = $bd->execRequete ($requete);
			
		?>
	<script>
			<!--
			window.alert("Votre mot de passe a bien été mis à jour.");
			window.location.replace("https://epitheca.fr");
			//-->
			</script>
		<?php
	}}
	   else
	{
		?>
<script>
			<!--
			window.alert("Une erreur c'est produite.");
			window.location.replace("https://epitheca.fr");
			//-->
			</script>
<?php
}
?>



<?php

//Vérification de l'existence d'une demande précédente non aboutie
$requete  = "DELETE FROM observateurs_temporaire WHERE mail='$email'";
$req = $bd->execRequete ($requete);

//Création du mot de passe
// on declare une chaine de caractères
$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
//nombre de caractères dans le mot de passe
$nb_caract = 25;

//Création de la variable 
$pass = "";

//on fait une boucle
for($u = 1; $u <= $nb_caract; $u++) {
    
//on compte le nombre de caractères présents dans notre chaine
    $nb = strlen($chaine);
    
// on choisie un nombre au hasard entre 0 et le nombre de caractères de la chaine
    $nb = mt_rand(0,($nb-1));
    
// on ajoute la lettre a la valeur de $pass
    $pass.=$chaine[$nb];
}

		//Definition du timestamp
	  $requete  = "INSERT INTO observateurs_temporaire (mail, secret) VALUES ('$email', '$pass')";
	 $req = $bd->execRequete ($requete);
	  
// grain de sel pour le futur pass
$pass = "EPI$pass";
	  
$subject="epitheca.fr : Votre mot de passe";

$message="
<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' > 
</HEAD>
<BODY>
<center>Base de données naturalistes epitheca.fr<br>
<img src=\"https://epitheca.fr/images/logo200pt.png\"></center><br>

Bonjour,<br>
Vous avez indiqué avoir perdu votre mot de passe pour la base de donnée epitheca.fr.
<em>epitheca.fr</em><br><br>
<a href='https://epitheca.fr/Session_creation_compte_mot_de_passe_4.php?m=$email&u=$pass'>Cliquez sur ce lien pour créér un nouveau mot de passe. </a>
<br><br>
Attention, ce lien est valable 30 minutes.
<br><br>
En cas de problème, vous pouvez copier ce lien dans votre navigateur : <br><br>https://epitheca.fr/Session_creation_compte_mot_de_passe_4.php?m=$email&u=$pass
<br><br>
Je reste à votre disposition pour plus de renseignements.<br>

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
$headers .= "Bcc : mathieu.moncomble@epitheca.fr\n";
 
// priorité urgente
$headers .= "X-Priority : 3\n";
 
// type de contenu HTML
$headers .= "Content-type: text/html; charset=utf-8\n";
 
// code de transportage
$headers .= "Content-Transfer-Encoding: 8bit\n";
 
 mail($to,$subject,$message, $headers);
 
 //Inclusion de la partie droite
 include ("Session_entete.html");
 
 ?>
 <div class="connexion-droit">
	 <br><br>
			<span class="titre-connexion">Un courriel vous a été envoyé<br>
			Ce courriel contient le lien d'activation valable 30 minutes.</span>
		 </div>            
		</div>
	</div>	

</BODY>
</HTML>
<?php
?>

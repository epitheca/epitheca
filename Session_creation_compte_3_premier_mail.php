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
	  
$subject="epitheca.fr : Votre compte";

$message="
<center>Base de données naturalistes epitheca.fr<br>
<img src=\"https://epitheca.fr/images/logo200pt.png\"></center><br>
<p style='text-align='center''Ceci est un message automatique.</p><br>
Bonjour,<br>
Vous avez demandé la création d'un compte sur la base de données naturalistes
<em>epitheca.fr</em><br><br>
<div style='width='100%', background-color='#B7F57C',text-align='center''>
<a href='https://epitheca.fr/Session_creation_compte_4_confirmation.php?m=$email&u=$pass'>Cliquez sur ce lien pour finaliser votre inscription. </a>
</div>
<br>
Attention, ce lien est valable 30 minutes.
<br><br>
En cas de problème, vous pouvez copier ce lien dans votre navigateur : <br>https://epitheca.fr/Session_creation_compte_4_confirmation.php?m=$email&u=$pass
<br><br>
Je reste à votre disposition pour plus de renseignements.<br>

Mathieu MONCOMBLE
<br>
";

$to = $email;
	
// Version MINE
$headers = "MIME-Version: 1.0\n";
 
// en-têtes expéditeur
$headers .= "From : $mail_administrateur\n";
 
// en-têtes adresse de retour
$headers .= "Reply-to : $mail_administrateur\n";
 
// priorité urgente
$headers .= "X-Priority : 3\n";
 
// type de contenu HTML
$headers .= "Content-type: text/html; charset=utf-8\n";
 
// code de transportage
$headers .= "Content-Transfer-Encoding: 8bit\n";
 
 mail($to,$subject,$message, $headers);
  
 //Mail pour les administrateurs
			//Quels sont les administrateurs ?
			$to_admin="";
			$i=0;
 			$res=$bd->execRequete ("SELECT * FROM observateurs WHERE administrateur LIKE 'oui'");
			while ($bo = $bd->objetSuivant ($res))
			{
				$i++;
				if ($i==1) 	$to_admin=$bo->email;
				else $to_admin.=", $bo->email";
			}
			
			//Sujet pour les administrateurs
			$subject_admin="Une nouvelle demande de compte"; 
			
			//message pour les administrateurs
 $message_admin="
<HTML>
<HEAD>
<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' > 
</HEAD>
<BODY>
<center>Base de données naturalistes epitheca.fr<br>
<img src=\"https://epitheca.fr/images/logo200pt.png\"></center><br>

Ceci est un message automatique.<br>

<span style='text-align='center''>Bonne nouvelle ! $email a demandé l'ouverture d'un compte</span><br><br>

<br>
</BODY>
</HTML>
";

mail($to_admin,$subject_admin,$message_admin, $headers);
 
 //Inclusion de la partie droite
 include ("Session_entete.html");
 
 ?>
 <div class="connexion-droit">
	 <br><br>
			<span class="titre-connexion">Un courriel vous a été envoyé<br>
			Ce courriel contient le lien d'activation valable 30 minutes.</span>
			Pensez à vérifier vos spams. ;-)
		 </div>            
		</div>
	</div>	

</BODY>
</HTML>
<?php
?>

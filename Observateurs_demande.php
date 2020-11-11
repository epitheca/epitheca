<?php
session_start();
require("Util.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);
$session = ControleAcces ("Observateurs_demande.php", $_POST, session_id(), $bd);
if (SessionValide ($session, $bd))
{
// Production de l'entête
$observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
$code_obs = "$observateur->code_obs";
Entete ("epitheca.fr", "6", $code_obs, $bd);
}

if (isset($_POST['envoyer']));
{
?>
<BODY onLoad=""setTimeout(window.close, 10000)"">
<?PHP
}


if (isset($_POST['tester']))
{
	//Récupération des valeurs
	$mail=$_POST['email'];
	$obs=$_POST['code_obs'];
	
	//Génération d'un code aléatoire
	// Initialisation des caractères utilisables
    $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
	$i=0;
	$password="";
	
    for($i=0;$i<50;$i++)
    {
        $password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
    }
    
    $password_url_accepter="https://epitheca.fr/Observateurs_association.php?cle=$password&accept=yes";
    $password_url_refuser="https://epitheca.fr/Observateurs_association.php?cle=$password&accept=no";
	
	//L'adresse mail est-elle associé à un observateur de la base ?
	$existe=Chercheobservateurs ($mail, $bd, $format=FORMAT_OBJET);
	
	if (isset($existe->nom))
	{
		?>
		<div class="bloc-50%-clair-gauche">			
			<div class="titre">Demande d'association</div>
			Un mail vient d'être envoyé pour demander à <?php echo "$existe->prenom $existe->nom";?> de pouvoir être associé à vos données.
			<br><br>
		</div>		

			<?php
								
			//Extraction des renseignements sur l'observateur faisant la demande
			$obs=Chercheobservateursaveccode ($obs, $bd, $format=FORMAT_OBJET); 
			$mail_obs=$obs->email;
			$code_obs=$obs->code_obs;
			$prenom_obs=$obs->prenom;
			$nom_obs=$obs->nom;
			
			//Vérification de l'existence de la demande précédente
			$select  = "SELECT * FROM observateurs_demande WHERE code_obs_demande='$existe->code_obs' AND code_obs='$code_obs'";
			$resultat = $bd->execRequete ($select);
			while ($bo = $bd->objetSuivant ($resultat))
				{
				$requete  = "DELETE FROM observateurs_demande WHERE id_demande='$bo->id_demande'";
				$resultat = $bd->execRequete ($requete);
				}
			
			//Insertion dans la table des demandes
			//Calcul de la date dans une semaine
			$timestamp= date ("Y-m-d H:i:s", strtotime('+1 week'));
			 $ins_demande = "INSERT INTO observateurs_demande (id_demande, code_obs_demande, code_obs, timestamp) "
       . "VALUES ('$password', '$existe->code_obs', '$code_obs', '$timestamp') ";
			$res = $bd->execRequete ($ins_demande); 	
					
			
			$ancre_accepter = Ancre_renomme ($password_url_accepter, 'accepter d\'être associé à cet observateur');
			$ancre_refuser = Ancre_renomme ($password_url_refuser,' refuser d\'être associé à cet observateur');
	$from =  $mail_obs;
	$to = $mail;
	$headers = 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'Content-type: text/html; charset= utf-8' ."\r\n";
	$message= "
	<head>
       <title>epitheca.fr</title>
      </head>
      <body>
      <table>
        <tr>
         <th></th><th></th>
        </tr>
        <tr>
         <td><img src=\"https://epitheca.fr/images/logo200pt.png\"></td><td><H3>Base de données naturalistes Epitheca.fr</H3></td>
        </tr>
        </table>
      <p>Bonjour ce message est généré à la demande de $prenom_obs $nom_obs qui souhaite pouvoir vous associer à ses données.</p>
	
	 <p>Vous pouvez $ancre_accepter ou $ancre_refuser .</p>
	 <br><br>
	 Cette demande n'est valable qu'une semaine.
	 <br><br>
	 <p>En cas de problème, vous pouvez copier/coller cette adresse pour accepter : $password_url_accepter 
	 <br>
	 ou celle-ci pour refuser : $password_url_refuser
	 </p> ";
	 
mail ($to, "Demande d'association sur la base Epitheca.fr", $message, $headers);
		}
			
	else 
	{
			?>
		<div class="bloc-50%-clair-gauche">			
			<div class="titre">Demande d'association</div>
			Aucun observateur n'est associé à cette adresse mail.
			<br><br>
		</div>		

			<?php

		}
	
}
// Affichage du pied de page
PiedDePage($session, $code_obs, $bd);
  
?>

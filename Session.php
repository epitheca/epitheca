<?php

// Recherche d'une session
function ChercheSession ($id_session, $bd) 
{
  $requete =  "SELECT * FROM sessionweb WHERE id_session = '$id_session' " ;
  $resultat = $bd->execRequete ($requete);
  return $bd->objetSuivant ($resultat);
}

// Vérification qu'une session est valide
function SessionValide ($session, $bd)
{
  // Vérifions que le temps limite n'est pas dépassé
  date_default_timezone_set('GMT');
  $maintenant = date ("U");
  if ($session->temps_limite < $maintenant)
    {
      // Destruction de la session
      session_destroy();
      $requete  = "DELETE FROM sessionweb "
	. "WHERE id_session='$session->id_session'";
      $resultat = $bd->execRequete ($requete);
	        return FALSE;
    }
  else // C'est bon !
    return TRUE;
}

// Tentative de création d'une session
function CreerSession ($bd, $email, $mot_de_passe, $duree, $id_session)
{
  $observateurs = Chercheobservateurs ($email, $bd);

  // L'observateurs existe-t-il ?
  if (is_object($observateurs))
    {
      // Vérification du mot de passe
      if ($observateurs->mot_de_passe == md5($mot_de_passe))
	{
		// On insère dans la table SessionWeb, pour 1 heure 
		date_default_timezone_set('GMT');
		$maintenant =date("U");
		$temps_limite = $maintenant + $duree;
		$email = $bd->prepareChaine($email);
		$nom = $bd->prepareChaine($observateurs->nom);
		$prenom = $bd->prepareChaine($observateurs->prenom);

		//Destruction de la session précédente
		$requete  = "DELETE FROM sessionweb "
		. "WHERE email='$email'";
		$resultat = $bd->execRequete ($requete);

		//Création d'une nouvelle session
		$insSession = "INSERT INTO sessionweb (id_session, email, nom, "
	    . "prenom, temps_limite) VALUES ('$id_session', "
	    . "'$email','$nom','$prenom', '$temps_limite')";       
		$resultat = $bd->execRequete ($insSession);
	  
		//insertion pour les stats
		$code_obs= Chercheobservateurs($email,$bd);
		$code_obs=$code_obs->code_obs;
		$insConnexions = "INSERT INTO connexions (code_obs) VALUES ('$code_obs')";       
		$resultat = $bd->execRequete ($insConnexions);

		return TRUE;
	}   

	return FALSE;
    }      
  else
    {
    return FALSE;
    }}

// Fonction de contrôle d'accès
function ControleAcces ($nom_script, $info_login, $id_session, $bd)
{
  // Recherche de la session
  $session_courante = ChercheSession ($id_session, $bd);

  // Cas 1: Vérification de la session courante
  if (is_object($session_courante))
    {
      // La session existe. Est-elle valide ?
      if (SessionValide ($session_courante, $bd))
	   	{
	  // On renvoie l'objet session
	  return $session_courante;
	 
	}
      else 
fenetre_modal ("Erreur","Votre session est expirée"); 

    }
 
  // Cas 2.a: pas de session mais email et mot de passe 
  if (isSet($info_login['email']))
    {
      // Une paire email/mot de passe existe. Est-elle correcte ?
      if (CreerSession ($bd, $info_login['email'], 
			$info_login['mot_de_passe'], $info_login['duree'], $id_session))
	{
	  // On renvoie l'objet session 
	  return ChercheSession ($id_session, $bd);
	  }
  
      else
      {   
		  ?>   
<script type="text/javascript">
			<!--
			window.alert("<?php echo "Votre connexion a échoué" ?>");
			//-->
			</script>
<?php
}}

  // Cas 2.b : il faut afficher le formulaire, en proposant
  // l'email comme valeur par défaut.   
  if (isSet($info_login['email']))
    $email = $info_login['email'];
  else
    $email = "";
  FormIdentification($nom_script, $email, $bd);
}

//Inclusion du formulaire de connexion
include("Session_formulaire.php");
?>

<?php
require("Util.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

if ($_POST['valider']) {
    $email   = $_POST['email'];
    
    // On va quand même vérifier que ce compte existe
      $controleemail=Chercheobservateurs($email, $bd, FORMAT_OBJET);
      if (isset($controleemail))
      {
      if ($email == $controleemail->email)
	{
		//L'observateur existe bien...
	$token  = $_POST['token'];
    $action = "contact";
   
    $curlData = array(
        'secret' => $CLE_reCAPTCHA_secrete,
        'response' => $token
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curlResponse = curl_exec($ch);
    
    $captchaResponse = json_decode($curlResponse, true);
        
    if ($captchaResponse['success'] == '1' && $captchaResponse['action'] == $action && $captchaResponse['score'] >= 0.5 && $captchaResponse['hostname'] == $_SERVER['SERVER_NAME']) {
		include ("Session_creation_compte_mot_de_passe_3.php");
	}
	else {
		//redirection en dehors du site
        ?>
		<SCRIPT LANGUAGE="JavaScript">
			alert("L'agorythme de reCaptcha indique que vous êtes un robot, on vous sort du site...");
			document.location.href="https://fr.wikipedia.org/wiki/ReCAPTCHA"
		</SCRIPT>
				<?php
    }
	}
	//L'observateur n'existe pas
	else
	{
		?>
		<SCRIPT>
			alert("Cet email ne correspond à aucun compte.");
			document.location.href="https://epitheca.fr"
		</SCRIPT>
				<?php
	}
		
	
  
}}
?>

<?php
require("Util.php");

// 1. Vérification du Captcha Turnstile en premier
$turnstileToken = $_POST['cf-turnstile-response'] ?? '';
$isValidCaptcha = false;

if (!empty($turnstileToken)) {
    $curlData = array(
        'secret'   => CL_TURNSTILE_SECRETKEY, // Utilisation de votre constante
        'response' => $turnstileToken,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://challenges.cloudflare.com/turnstile/v0/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curlResponse = curl_exec($ch);
    curl_close($ch);

    $captchaResult = json_decode($curlResponse, true);
    if ($captchaResult['success']) {
        $isValidCaptcha = true;
    }
}

// 2. Traitement du formulaire si le captcha est valide
if ($isValidCaptcha) {
    if (isset($_POST['valider'])) {
        $bd = Connexion(NOM, PASSE, BASE, SERVEUR);
        $email = $bd->prepareChaine($_POST['email']);
        
        // On vérifie si cet email existe déjà
        $controleemail = Chercheobservateurs($email, $bd, FORMAT_OBJET);
        
        if (isset($controleemail) && $email == $controleemail->email) {
            ?>
            <script type="text/javascript">
                alert("Un observateur avec cet email existe déjà.");
                window.location.replace("https://epitheca.fr");
            </script>
            <?php
        } else {
            // C'est bon, on envoie le mail de création
            include("Session_creation_compte_3_premier_mail.php");
        }
    }
} else {
    // Échec du captcha : redirection
    ?>
    <script type="text/javascript">
        alert("La vérification de sécurité a échoué (Turnstile). Veuillez réessayer.");
        document.location.href="https://epitheca.fr";
    </script>
    <?php
}
?>

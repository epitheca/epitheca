<?php
// On utilise $email qui a été "préparé" dans le fichier précédent

// 1. Nettoyage des demandes précédentes
$requete = "DELETE FROM observateurs_temporaire WHERE mail='$email'";
$bd->execRequete($requete);

// 2. Création du jeton (secret)
$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$nb_caract = 25;
$pass = "";
for($u = 1; $u <= $nb_caract; $u++) {
    $nb = mt_rand(0, (strlen($chaine) - 1));
    $pass .= $chaine[$nb];
}

// 3. Insertion en base
$requete = "INSERT INTO observateurs_temporaire (mail, secret) VALUES ('$email', '$pass')";
$bd->execRequete($requete);

// On ajoute le préfixe pour le lien
$pass_lien = "EPI$pass";
$subject = "epitheca.fr : Votre compte";

// Construction du message avec HTML propre
// Note : urlencode($email) est crucial pour la validité du lien
$lien_activation = CHEMIN_URL . "Session_creation_compte_4_confirmation.php?m=" . urlencode($email) . "&u=" . $pass_lien;

$message = "
<html>
<body>
<div style='text-align:center;'>
    <p>Base de données naturalistes epitheca.fr</p>
    <img src='" . CHEMIN_URL . "images/logo200pt.png' alt='Logo Epitheca'>
</div>
<p style='text-align:center;'>Ceci est un message automatique.</p>
<p>Bonjour,</p>
<p>Vous avez demandé la création d'un compte sur la base de données naturalistes <em>epitheca.fr</em></p>
<div style='width:100%; background-color:#B7F57C; text-align:center; padding:15px; border-radius:5px;'>
    <a href='$lien_activation' style='font-weight:bold; color:black; text-decoration:none;'>Cliquez sur ce lien pour finaliser votre inscription.</a>
</div>
<p>Attention, ce lien est valable 30 minutes.</p>
<p>En cas de problème, vous pouvez copier ce lien dans votre navigateur :<br>$lien_activation</p>
<p>Je reste à votre disposition pour plus de renseignements.<br><br>Mathieu MONCOMBLE</p>
</body>
</html>";

// Headers corrigés (\r\n et suppression des espaces avant les :)
$headers = "MIME-Version: 1.0\r\n";
$headers .= "From: " . MAIL_ADMIN . "\r\n";
$headers .= "Reply-To: " . MAIL_ADMIN . "\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";

// Envoi au futur utilisateur
mail($email, $subject, $message, $headers);

// 4. Mail pour les administrateurs
$to_admin = "";
$i = 0;
$res = $bd->execRequete("SELECT email FROM observateurs WHERE administrateur LIKE 'oui'");
while ($bo = $bd->objetSuivant($res)) {
    $i++;
    if ($i == 1) $to_admin = $bo->email;
    else $to_admin .= ", " . $bo->email;
}

if (!empty($to_admin)) {
    $subject_admin = "Une nouvelle demande de compte"; 
    $message_admin = "
    <html>
    <body>
    <div style='text-align:center;'>
        <p>Base de données naturalistes epitheca.fr</p>
        <img src='" . CHEMIN_URL . "images/logo200pt.png'>
    </div>
    <p>Ceci est un message automatique.</p>
    <p><strong>Bonne nouvelle !</strong> L'adresse <strong>$email</strong> a demandé l'ouverture d'un compte.</p>
    </body>
    </html>";
    
    mail($to_admin, $subject_admin, $message_admin, $headers);
}

// 5. Affichage final
include("Session_entete.html");
?>
    <div class="connexion-droit">
        <br><br>
        <span class="titre-connexion">Un courriel vous a été envoyé<br>
        Ce courriel contient le lien d'activation valable 30 minutes.</span>
        <p>Pensez à vérifier vos spams. ;-)</p>
    </div>        
</div>
</div>
</body>
</html>

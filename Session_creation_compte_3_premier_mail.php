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

// Construction du message avec HTML propre
// Note : urlencode($email) est crucial pour la validité du lien
$lien_activation = CHEMIN_URL . "Session_creation_compte_4_confirmation.php?m=" . urlencode($email) . "&u=" . $pass_lien;

$subject = "🌿 epitheca.fr : Finalisez votre inscription";

// Utilisation de couleurs douces et naturelles (vert naturaliste)
$couleur_principale = "#4CAF50"; 
$couleur_fond = "#f4f4f4";

$message = "
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
</head>
<body style='margin: 0; padding: 0; background-color: $couleur_fond; font-family: Arial, sans-serif;'>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
        <tr>
            <td align='center' style='padding: 20px 0;'>
                <table border='0' cellpadding='0' cellspacing='0' width='600' style='background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
                    <tr>
                        <td align='center' style='padding: 40px 0 20px 0; background-color: #ffffff;'>
                            <img src='" . CHEMIN_URL . "images/logo200pt.png' alt='Epitheca Logo' width='150' style='display: block;'>
                            <h1 style='color: #333; font-size: 24px; margin-top: 20px;'>Bienvenue sur Epitheca</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px 40px; color: #555; line-height: 1.6;'>
                            <p style='font-size: 16px;'>Bonjour,</p>
                            <p style='font-size: 16px;'>Vous avez demandé la création d'un compte sur la base de données naturalistes <strong>epitheca.fr</strong>. Nous sommes ravis de vous compter parmi nous !</p>
                            <p style='font-size: 16px;'>Pour valider votre adresse e-mail et finaliser votre inscription, cliquez simplement sur le bouton ci-dessous :</p>
                        </td>
                    </tr>
                    <tr>
                        <td align='center' style='padding: 30px 0;'>
                            <a href='$lien_activation' style='background-color: $couleur_principale; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 18px; display: inline-block;'>Activer mon compte</a>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 0 40px 20px 40px; color: #999; font-size: 13px; text-align: center;'>
                            <p>Attention : ce lien est valable <strong>30 minutes</strong>.<br>Ceci est un message automatique, merci de ne pas y répondre.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px 40px; border-top: 1px solid #eee; color: #999; font-size: 12px;'>
                            Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
                            <a href='$lien_activation' style='color: $couleur_principale;'>$lien_activation</a>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px 40px; background-color: #f9f9f9; color: #777; font-size: 14px;'>
                            À très bientôt,<br>
                            <strong>Mathieu MONCOMBLE</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
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

<?php
session_start ();
require("Util.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

$session = ControleAcces ("quoideneuf.php", $_POST, session_id(), $bd);
 if (SessionValide ($session, $bd))
    {
    $observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
$code_obs = "$observateur->code_obs";
Entete ("Base de données de l'association des naturalistes vendéens", "Quoi de neuf ?", $code_obs, $bd);
	}
	?>
	<div id="bloc-page">
		
		<div class="bloc-50pc-gauche-centre">
			Epitheca est développée par <a href="mailto:contact@epitheca.fr">Mathieu MONCOMBLE</a><br>
			<a href="https://framasphere.org/people/2027e100824e0132ae4e2a0000053625">
				<img src="images/diaspora.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;"></a>

			<br>Le code est basé sur la base de données dévelloppée pour l'assocation Les Naturalistes Vendéens.
			<br>						
			<br><br>La base est dévelopée en PHP 7 et produit un HTML5 et CSS3.
			
			<br><br>
			<img src="images/apache.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<img src="images/MySQL.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<img src="images/php.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<br>
			<img src="images/geany.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<img src="images/meld.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<img src="images/filezilla.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<br>
			<img src="images/ubuntu.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">
			<img src="images/leaflet.png" alt="Chargement en cours" style="width:100px;margin-top:8px;margin-right:8px;">

		</div>
				<div class="bloc-50pc-droit-centre">
				L'évolution de cette base de données peut-être suivi sur GitHub.
				<br><br>
<a class="github-button" href="https://github.com/epitheca" data-color-scheme="no-preference: light; light: light; dark: light;" data-size="large" aria-label="Follow @epitheca on GitHub">Follow @epitheca</a>
				</div>
	</div>
<?php

// Affichage du pied de page
PiedDePage($session, $code_obs, $bd);
?>

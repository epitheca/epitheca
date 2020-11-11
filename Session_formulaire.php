<!--
Copyright Mathieu MONCOMBLE (mathieu.moncomble@epitheca.fr) 2009-2020

This file is part of epitheca.

    epitheca is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    epitheca is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with epitheca.  If not, see <https://www.gnu.org/licenses/>.
-->

<?php
// Formulaire pour la saisie des login et mot de passe
function FormIdentification($nom_script, $email_defaut="",$bd)
{	
//Inclusion de l'entête pour la session
include("Session_entete.html");
?>
			<div class="connexion-droit">
			<span class="titre-connexion">Connectez-vous</span>
					<form method="post" action="<?php echo $nom_script;?>">
					<br><input class="session" placeholder="Adresse de courriel" type="email" name="email" size="10">
					<br><input class="session" placeholder="Mot de passe" type="password" name="mot_de_passe" size="31">
					<input type="hidden"  name="duree"  value="12096000">
					<br>
						<input type="submit" value="Se connecter" class="vert"><br><br>
						<a href="Session_creation_compte_mot_de_passe_1.php">Mot de passe oublié</a>
						</form>
						<br><br><br>
			<p class="titre">Pas encore de compte ?</p>
						<button onclick="window.location.href='Session_creation_compte_1.php';" class="vert">Créér un nouveau compte</button>
						<br><br>
						<a href="Charte.php">Mentions légales</a>
            </div>            

</BODY>
</HTML>
<?php 
 exit;
}

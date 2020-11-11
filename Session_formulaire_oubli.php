<html>
			<center>Un observateur avec cette adresse mail existe déjà.<br> Voulez-vous réinitialiser le mot de passe ?</center>
			<p style="margin-top:100px; font-size:4vw" align="center">Réinitialisation du mot de passe</p>
					<br>
					<form method="post" action="Session_formulaire_oubli_mail.php">
					<input class="session" placeholder="Adresse de courriel" type="email" value "<?php echo $email; ?>" name="email" size="10"></span>
					<p style="text-align:center">
						<input type="submit" value="Réinitialiser" class="noir"><br><br>
					</form>
					<p style="text-align:center">
						<a href="Session_creation_compte.php"> <input type="submit" class="noir" value="Nouveau compte"> </a>
						<a href="index.php"> <input type="submit" class="noir" value="Se connecter"> </a>
						<a href="session_explication"> <input type="submit" class="noir" value="Plus d'informations"> </a>
					</p>
					<br><br><br>
</html>

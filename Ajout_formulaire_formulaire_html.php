<!--
Copyright Mathieu MONCOMBLE (contact@epitheca.fr) 2009-2022

This file is part of epitheca.

    epitheca is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License.

    epitheca is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with epitheca.  If not, see <https://www.gnu.org/licenses/>.
-->

<?php
//Formulaire type en html pour l'inclusion dans les fichiers le nécessitant
?>

<!-- Insertion du script pour masquer les divs-->
<script type="text/javascript" src="afficher_cacher_div.js"></script>

<!-- Début du formulaire-->
<?php 

//Si c'est une insertion on ajuste la page vers l'ancre
if (isset($mode)){
if ($mode=="INSERTION") echo '<form method="post" action="Ajout.php#ancre" name="donnees">';
else echo '<form method="post" action="Ajout.php" name="donnees">';
}
else echo '<form method="post" action="Ajout.php" name="donnees">';

//La valeur obs2 et ou obs 3 est NULL on la transforme en 1 - non précisée
			if (is_null($obs2)) $obs2='1';
			if (is_null($obs3)) $obs3='1';

?>
	<!-- fieldset avec le bloc saisi pour le script de géoportail-->
	<div class='bloc-100pc'>
		<fieldset id="bloc_saisi">
		<legend></legend>
		
		<div class="bloc-50pc-gauche">
			<span class="sous-titre">1. Renseignements obligatoires</span><br>
			<p class="aere">

						Date : <input type="date" value="<?php echo $date;?>" name="date">
                        
                        <br>
					
						<input type="text" id="longitude" name="longitude" placeholder="Longitude" size="7" onchange="updateLatLng(document.getElementById('latitude').value,document.getElementById('longitude').value,1);"
								title="Nombre décimal entre -2.45 et -0.52" required value="<?PHP echo $longitude;?>">
						
						<input type="text" id="latitude" name="latitude" placeholder="Latitude" size="7" onchange="updateLatLng(document.getElementById('latitude').value,document.getElementById('longitude').value,1);"
								title="Nombre décimal entre 46.24 et 47.1" required value="<?PHP echo $latitude; ?>">
																	
			<!-- Espace pour la carte-->						
			<div id="mapid"></div>
					<?php
					
							//Définition du marqueur s'il existe des coordonéees
							if ($latitude <>"" && $longitude<>"")
								{						
								echo "<script>var latitude=$latitude; var longitude=$longitude;</script>";
								}
								else echo "<script>var latitude=46.668972; var longitude=-1.433574;</script>";
			 
						echo '<script src="leaflet/leaflet_capture.js"></script>';
						?>
						
		</div>
	
		<!-- fieldset pour les autres renseignements-->
	
	<div class="bloc-50pc-droit">
	
	<legend></legend>
		<span class="sous-titre">2. Autres renseignements (optionnels)</span><br>
			<!-- bascule pour la météo-->
			<p class="aere"><a href="" onclick="bascule('météo'); return false;" class="lien_fonce">Ajouter des renseignements météorologiques</a><br>
			</p>
				<?php
			//Vérification de l'existence de données pour moduler l'affichage
			if ($cond_clim<>"X" || $vent<>"X" || $temp<>"") echo "<div id='météo' style='display:block;'>";
			else echo "<div id='météo' style='display:none;'>";
				form_vent ($vent, $bd);
				echo "<br>";
				form_condclim ($cond_clim, $bd);
				echo "<br>";
				form_temperature ($temp, $bd);
				echo "<br>";

				?>
			</div>
			<!-- bascule pour les routes, rivières-->
			<p class="aere"><a href="" onclick="bascule('corine'); return false;"  class="lien_fonce">Ajouter une route, une rivière ou des codes Corines</a><br>
			</p>
			<?php
			
			//Vérification de l'existence de données pour moduler l'affichage
			if ($route<>"X" || $riviere<>"X" || $corine1<>"0" || $corine2<>"0" || $corine3<>"0" || $corine4<>"0") echo "<div id='corine' style='display:block;'>";
			else echo "<div id='corine' style='display:none;'>";
				
				form_route ($route,"A","A", $bd);
				echo "<br>";
				form_riviere ($riviere,"A","A", $bd);
				echo "<br><br>Biotope CORINE<br>";
				form_corine (1, $corine1, "A", "90%", $bd);
				echo "<br>";
				form_corine (2, $corine2, "A", "90%", $bd);
				echo "<br>";
				form_corine (3, $corine3, "A", "90%", $bd);
				echo "<br>";
				form_corine (4, $corine4, "A", "90%", $bd);
				?>
			</div>
			
			<?php
				//Vérification de l'existence d'observateur associé.
				$observateurAssocie=observateurAssocie ($obs1, $bd);
	
				//Comptage du nombre d'observateur associé
				$nombreAssocie=count($observateurAssocie);
	
				//Si le nombre est égal à 0, on affiche un message.
	if ($nombreAssocie == 0)
	{
		?>
	<p class='center'><br>Remarque : vous pourriez associer un observateur à vos données. <br> Vous pouvez lui en faire la demande <a href='./ObservateursMAJ.php'>ici</a></p>
	<input type="hidden"  name="obs2"  value="1">
	<input type="hidden"  name="obs3"  value="1">
	<input type="hidden" name="origineDonnee" value="CLASSIQUE">
	<input type="hidden" name="choix" value="0">

<?php
}
	else
		{
			?>
			<!-- bascule pour les déterminateurs-->
			<p class="aere"><a href="" onclick="bascule('observateur'); return false;"  class="lien_fonce">Ajouter des observateurs, ou préciser les déterminateurs et collecteurs</a><br>
			</p>
			<?php
				
			//Vérification de l'existence de données pour moduler l'affichage
			if ($determinateur<>"CLASSIQUE" || $obs2<>"1" || $obs3<>"1") echo "<div id='observateur' style='display:block;'>";
			else echo "<div id='observateur' style='display:none;'>";
				
				form_case_det ($determinateur, $obs1, $obs2, $obs3, $origineDonnee, $bd);
				?>
			</span></p></div>				
	</fieldset>
	<?php
}
?>
	</div></div>
	<div class="bloc-100pc">
    <!-- fieldset pour les renseignements taxonomiques-->
	<fieldset>
	<legend></legend>
        <span class="sous-titre" id="ancre">3. Renseignements taxonomiques</span>
        <br>
		<p class="aere">
		
        <?php
		//Une proposition d'espèce fut faite mais elle n'est pas bonne, elle est undefined ou 0
		if ($espece=="undefined"||$espece=="0") $espece="";
		if ($espece=="")
			{
			form_espece_test ($espece, $bd);		
			
			?>
			<input type="hidden" id="codeespecea" name="espece"/>
			<input type="submit" id="button" name="valider_sp" value="Sélectionner"/>
			
			<!-- Insertion du script pour rechercher le code de l'espèce-->
			<script type="text/javascript">
				$("#button").click(function() {
				var val = $('#item').val()
				var xyz = $('#items option').filter(function() {
				return this.value == val;
				}).data('xyz');
				document.getElementById("codeespecea").value = xyz;
				})
				</script>
			<!--Fin du fieldset dans le cas ou l'espèce n'est pas saisie-->
			</fieldset>
</div>
		<?php
		}

//l'espèce est saisie et est correcte
	if ($espece<>"undefined" && $espece<>"" && $espece<>"0")
	{
			//on récupère le code de l'espèce
			$nom_espece = ChercheSpavecCode ($espece, $bd, FORMAT_OBJET);
			?>
			<!--On cache la valeur de l'espèce dans un champ hidden"-->
			<input type="hidden" id="codeespecea" name="espece" value="<?php echo $espece; ?>"/>
			
			<!--On cache la valeur de l'espèce dans un champ hidden"-->
			<div class="bloc-avertissement">
			<?php
			echo $nom_espece->NOM_VALIDE;
			?>
			<INPUT type="submit" name="annuler_sp" value="Annuler"><br></div>
			<p class="aere">
			<?php
			form_espece ($espece, $info_1, $info_2, $sexe, $bd);
			echo "<div class='adaptative'>";
			form_abondance ($abo, $bd);
			echo "</div>";
			
			echo "<br><p class='aere'>";
			?>
			<!-- bascule pour les remarques-->
			<p class="aere"><a href="" onclick="bascule('remarque'); return false;">Ajouter une remarque</a><br>
			</p>
			<?php
			//Vérification de l'existence de remarque pour moduler l'affichage
			if ($remarques<>"") echo "<div id='remarque' style='display:block;'>";
			else echo "<div id='remarque' style='display:none;'>";
			form_remarque ($remarques,$bd);
			echo "</div>";
			?>
			<!-- bascule pour les fichiers-->
			<p class="aere"><a href="" onclick="bascule('fichier'); return false;">Ajouter un fichier image (*.jpg, *.gif, *.png) ou son (*.mp3)</a><br>
			</p>
			<?php
			//Vérification de l'existence de fichier pour moduler l'affichage
			$fichier=Cherche_fichier ($numero, $bd, $format=FORMAT_OBJET);
			if ($fichier=="X") echo "<div id='fichier' style='display:none;'>";
			else echo "<div id='fichier' style='display:block;'>";				
			form_fichier ($fichier, $numero, $bd);
			echo "</div>";
			//Ajout des champs hidden pour la capture des id des images
			echo "<input type='hidden' id='fichier_joint' name='fichier_joint' value='X'>";
			echo "<br></p></div>";
		}
	?>


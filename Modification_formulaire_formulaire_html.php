<?php
//Formulaire type en html pour l'inclusion dans les fichiers le nécessitant
?>
<!-- Insertion du script pour masquer les divs-->
<script type="text/javascript" src="afficher_cacher_div.js"></script>

<!-- Début du formulaire-->
<form method="post" action="modification_donnee_resp.php" name="donnees">

	<!-- fieldset avec le bloc saisi pour le script de géoportail-->
	<div class='bloc-960-moyen'>
	<fieldset id="bloc_saisi">
	<legend></legend>
		<span class="sous-titre">1. Renseignements obligatoires</span><br>
			<p class="aere">
				<span style="position:relative;left:20px;">
				Date :
						<input type="text" value="<?php echo $datefr;?>" maxlength="10" size="8" style="width:150px" name="datefr">
						<input type="button" value="calendrier"  onclick="displayCalendar(document.forms[0].datefr,'dd/mm/yyyy',this)">
				Longitude : <input type="number" id="longitude" name="longitude" class="number" style="width:90px" min="-2.45" max="-0.52" step="0.000001" placeholder="Longitude" onchange="updateLatLng(document.getElementById('latitude').value,document.getElementById('longitude').value,1)"
						title="Nombre décimal" required value="<?PHP echo $longitude;?>">
				Latitude  : <input type="number" id="latitude" name="latitude" class="number" style="width:90px" placeholder="Latitude" min="46.24" max="47.1" step="0.000001" onchange="updateLatLng(document.getElementById('latitude').value,document.getElementById('longitude').value,1) "
						title="Nombre décimal" required value="<?PHP echo $latitude;?>">
						
							
							<!-- Espace pour les stations-->
							<a href="" onclick="bascule('1'); return false;">Par station</a>
							
							<?php
							//Pas de station choisie, on les masque
							if ($station<>"X" && $station<>"") echo "<div id='1'>";
							//Dans le cas contraire, on les montre
							else echo "<div id='1'  style='display:none;'>";						
													
							//vérification des droits chiros
							$droitchiro= droit_chiro ($obs1,$bd);
							if ($droitchiro=="oui") 
							Form_station_chiro ($station, "5px", "A", $bd);
							else
							Form_station ($station, "5px", "A", $bd); ?>
							<br><br><a href="Station_ajouter.php">Ajouter une station</a>
							<br><br>
							</div>
						
						<!-- Espace pour la carte-->						
						<div id="mapid"></div>
					<?php
							//Définition du marqueur s'il existe des coordonéees
							if ($latitude <>"" && $longitude<>"")
								{						
								echo "<script>var latitude=$latitude; var longitude=$longitude;</script>";
								}
								else echo "<script>var latitude=46.668972; var longitude=-1.433574;</script>";
			 
						//Vérification des droits chiro pour inclusion du script pour la capture des coordonées
						if ($droitchiro=="oui") 
						echo '<script src="leaflet/leaflet_capture_chiro.js"></script>';
						if ($droitchiro<>"oui") echo '<script src="leaflet/leaflet_capture.js"></script>';
						?>
						
	</fieldset>
	</div>
	
	<!-- fieldset pour les autres renseignements-->
	<div class='bloc-960-moyen'>
		<fieldset>
	<legend></legend>
		<span class="sous-titre">2. Autres renseignements</span><br>
		<span style="position:relative;left:20px;">
			<!-- bascule pour la météo-->
			<p class="aere"><a href="" onclick="bascule('météo'); return false;">Ajouter des renseignements météorologiques</a><br>
			</p>
				<?php
			//Vérification de l'existence de données pour moduler l'affichage
			if ($cond_clim<>"X" || $vent<>"X" || $temp<>"") echo "<div id='météo' style='display:block;'>";
			else echo "<div id='météo' style='display:none;'>";
				
				form_condclim ($cond_clim, $bd);
				form_vent ($vent, $bd);
				form_temperature ($temp, $bd);
				?>
			</div>
			<!-- bascule pour les routes, rivières-->
			<p class="aere"><a href="" onclick="bascule('corine'); return false;">Ajouter une route, une rivière ou des codes Corines</a><br>
			</p>
			<?php
			
			//Vérification de l'existence de données pour moduler l'affichage
			if ($route<>"X" || $riviere<>"X" || $corine1<>"X" || $corine2<>"X" || $corine3<>"X" || $corine4<>"X") echo "<div id='corine' style='display:block;'>";
			else echo "<div id='corine' style='display:none;'>";
				
				form_route ($route,"A","A", $bd);
				form_riviere ($riviere,"A","A", $bd);
				echo "<br><br>Biotope CORINE<br>";
				form_corine (1, $corine1, "A", "A", $bd);
				echo "<br>";
				form_corine (2, $corine2, "A", "A", $bd);
				echo "<br>";
				form_corine (3, $corine3, "A", "A", $bd);
				echo "<br>";
				form_corine (4, $corine4, "A", "A", $bd);
				?>
			</div>
			<!-- bascule pour les déterminateurs-->
			<input type="hidden"  name="obs2"  value="<?php echo $obs2;?>">
			<input type="hidden"  name="obs3"  value="<?php echo $obs3;?>">
			<input type="hidden"  name="obs4"  value="<?php echo $obs4;?>">
			<input type="hidden"  name="typedonnee"  value="<?php echo $determinateur?>">
			<br>
			<?php
			//Vérification de l'existence de données pour moduler l'affichage
			form_case_det_resp ($determinateur, $obs1, $obs2, $obs3, $obs4, $bd);
				?>
			
			
			</span></p>			
	</div>
	
	<!-- fieldset pour les renseignements taxonomiques-->
	<div class='bloc-960-moyen'>
	<fieldset>
	<legend></legend>
	<span class="sous-titre">3. Renseignements taxonomiques</span><br>
		
			<span style="position:relative;left:20px;"><p class="aere">
		<?php
		//Une proposition d'espèce fut faite mais elle n'est pas bonne, elle est undefined ou 0
		if ($espece=="undefined"||$espece=="0") $espece="";
		if ($espece=="")
			{
			form_espece_test ($espece, $bd);		
			?>
			<input type="hidden" id="codeespecea" name="espece"/>
			<input type="submit" id="button" name="valider_sp" value="Valider"/>
			
			<!-- Insertion du script pour rechercher le code de l'espèce-->
			<script type="text/javascript">
				$("#button").click(function() {
				var val = $('#item').val()
				var xyz = $('#items option').filter(function() {
				return this.value == val;
				}).data('xyz');
				document.getElementById("codeespecea").value = xyz;
			
				/* if value doesn't match an option, xyz will be undefined*/
				/*var msg = xyz ? 'xyz=' + xyz : 'No Match';
				alert(msg)*/
				})
				</script>
			<!--Fin du fieldset dans le cas ou l'espèce n'est pas saisie-->
			</fieldset></div>
		<?php
		}

//l'espèce est saisie et est correcte
	if ($espece<>"undefined" && $espece<>"" && $espece<>"0")
	{
			//on récupère le code de l'espèce
			$nom_espece = ChercheSpavecCode ($espece, $bd);
			?>
			<!--On cache la valeur de l'espèce dans un champ hidden"-->
			<input type="hidden" id="codeespecea" name="espece" value="<?php echo $espece; ?>"/>
			
			<!--On cache la valeur de l'espèce dans un champ hidden"-->
			<div class="bloc-avertissement">
			<?php
			echo $nom_espece;
			?>
			<INPUT type="submit" name="annuler_sp" value="Changer"><br></div>
			<p class="aere">
			<?php
			form_espece ($espece, $info_1, $info_2, $sexe, $bd);
			echo "<br>";
			form_abondance ($abo, $bd);
			echo "<br>";
			
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
			<p class="aere"><a href="" onclick="bascule('fichier'); return false;">Ajouter un fichier image ou son</a><br>
			</p>
			<?php
			//Vérification de l'existence de fichier pour moduler l'affichage
			$fichier=Cherche_fichier ($numero, $bd, $format=FORMAT_OBJET);
			if ($fichier=="X") echo "<div id='fichier' style='display:none;'>";
			else echo "<div id='fichier' style='display:block;'>";
			form_fichier ($fichier, $numero, $bd);
			echo "</div>";
			echo "<br></p></div>";
		}
	?>

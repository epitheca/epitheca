<!--
Copyright Mathieu MONCOMBLE (contact@epitheca.fr) 2009-2020

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
//Formulaire pour le choix des dates.
?>
		<!--spacer-->
		<div class="spacer"></div>	

	<!--ouverture d'un bloc 610-->	
		<div class="bloc-100pc">
			<div class="titre">Sélectionner des données</div>
			<form method="post" action="Consultation.php">
			<fieldset>
			<legend></legend>
				<input type="hidden"  name="second"  value="oui">
				<input type="hidden"  name="code_obs"  value="<?php echo $code_obs;?>">
				<div class="sous-titre">Sélection par date</div>
                Période du
				<input type="date" value="<?php echo $dateenmin;?>" name="datefrmin">
				au
				<input type="date" value="<?php echo $dateenmax;?>" name="datefrmax">
				</fieldset>
				
				<!--fieldset pour le choix de la station-->
				<fieldset>
				<div class="sous-titre">Sélection par zone en indiquant les coordonnées des points X et Y</div>
				<br>
				Sélectionner les données entre les coordonnées suivantes : 
				Longitude <input type="text" id="GeoXYFormLon" name="longitude_X" class="number" value="<?PHP echo $longitude_X;?>" style="width:90px; margin-top:10px;" maxlength="10" placeholder="Latitude"  pattern="-?\d{1,3}\.\d+">
				et 
				Latitude <input type="text" id="GeoXYFormLat" name="latitude_X" class="number" value="<?PHP echo $latitude_X;?>" style="width:90px; margin-left:13px;" maxlength="10" placeholder="Longitude" pattern="-?\d{1,3}\.\d+">
				<br> et les coordonnées suivantes : Longitude
				<input type="text" id="GeoXYFormLon2" name="longitude_Y" class="number" value="<?PHP echo $longitude_Y;?>" style="width:90px ;margin-left:5px;" maxlength="10" placeholder="Longitude" pattern="-?\d{1,3}\.\d+">
				et latitude 
				<input type="text" id="GeoXYFormLat2" name="latitude_Y" class="number" value="<?PHP echo $latitude_Y;?>" style="width:90px;margin-left:5px;" maxlength="10" placeholder="Latitude" pattern="-?\d{1,3}\.\d+">
				<br><br>    
				<!--fieldset pour le choix du taxon-->
				<fieldset>
				<div class="sous-titre">Autres critères de selection</div>
				<?php
				
				form_groupe_avec_tous ($groupe, "5px", "50%", $bd);
				echo "<br>";
			if ($groupe <> "%" && $groupe <>"tous")
			{
			form_espece_simple ($groupe, $sps, "5px","90%", $bd);
			echo "<br>";
			}
			form_route ($route, "5px", "50%", $bd);
			echo "<br>";
			form_riviere ($riviere,"5px","50%", $bd);
			echo "<br>";
			echo "Biotope CORINE";
			form_corine ("", $corine, "5px", "50%", $bd);
			?>
			<br><br>
		<p align=center><INPUT type="submit" name="filtre" value="Sélectionner les données"></p>
		</fieldset>
</div>
	
	


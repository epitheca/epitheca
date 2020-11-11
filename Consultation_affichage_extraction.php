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
?>
<!--ouverture d'un bloc de 25%-->
	<div class="bloc-25pc-droit">
		<legend></legend>
        <div class="titre">Extraire vos données</div>
		<?php
		//Transformation pour le séparateur des milliers
		$num_donnees_fr=number_format("$num_donnees", 0, '', ' '); 
		echo "<CENTER><span class='tresgros'>$num_donnees_fr</span> données <br> (réparties sur $num_rows fiches)";
		echo "<br>";
		
		//Requete pour le csv
		?>
<div id="contenant" style="display:none; background-color:transparent; border:1px solid #5078DC; text-align:left;">
<div id="barre" style="display:block; background-color:#5078DC; width:0%;">
<div id="pourcentage" style="text-align:right;">
&nbsp;
</div>
</div>
</div> 
		
		<?php
		//Case à cocher pour le rafraichissement du csv
		//Capture de la valeur de la case à cocher
		if (isset($_POST['csv_raf'])) $csv_raf=$_POST['csv_raf'];
		else $csv_raf="";
		
		//Controle pour le surplux de données
		if ($num_donnees>500) $csv_raf="CHECKED";
		if (isset($_POST['csv_creer'])) $csv_raf="";
				
		//Le rafraichissement des données est demandé
		if ($csv_raf<>"CHECKED")
		{
		csv_obs ($requete_groupee, $code_obs, $bd);
		
		//Création du nom de fichier
		if ($num_donnees>5000) $nom_fichier="$code_obs.zip";
		else $nom_fichier="$code_obs.csv";
		
		//Création de l'URL
		$url="./telechargements/$nom_fichier";
		
		//Recherche de la taille du fichier
		$taille = taille($url);
		//choix de l'image pour les données
		if ($num_donnees<5000) $imagefichier= "images/csv.png";
		else $imagefichier= "images/zip.png";
		?>
		<center>
		<a class="info-image" href="<?php echo $url;?>"><img src="<?php echo $imagefichier;?>"></a><br>
		<?php echo $taille;?>
		</center>
		<br>
		<INPUT type="checkbox" name="csv_raf" value="CHECKED" <?php echo $csv_raf;?>>ne pas rafraichir le csv<br>
	<?php
	}
		else
		{
		?>
		<br>
		Le fichier d'extraction n'est pas créé car la requête concerne plus de 500 données si vous souhaitez le créer, cliquez ci-dessous.
		<br>
		<INPUT type="submit" name="csv_creer" value="Créer le CSV"><br>
		</form>
<?php
	}
		//Requete pour la carte
		carte_obs_mini ($requete_groupee, $code_obs, $bd);
		
		//Création du nom du fichier
		$url_img="cartes/mini_carte_$code_obs.png";
		
		//choix de l'image pour les données
		if ($num_donnees<5000) $imagefichier= "images/csv.png";
		else $imagefichier= "images/zip.png";
		?>
		<br>		
		<a class="info-image" href="<?php echo $url_img;?>"><img src="<?php echo $url_img;?>" alt="chargement" width="80%" ></a>
		</center>
		</div>

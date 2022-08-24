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
// Formulaire de saisie d'une donnée pour coordonnées

function FormDonnees ($date, $longitude, $latitude, $espece, $abo, $info_1, $info_2, $sexe, $corine1, $corine2, $corine3, $corine4, $obs1, $obs2, $obs3, $origineDonnee, $determinateur, $vent, $cond_clim, $temp, $riviere, $route, $remarques, $mode, $fichier, $bd)
{	
include ("Listes.php");

//Il n'y a pas de numero pour le moment (pour les fichiers joints)
$numero="";

// Gestion de la date
    //La date est vide on place la date du jour par défaut
        if (empty ($date)) $date = date("Y-m-d");

//Transformation des NULL en valeur générique
if (is_null ($vent)) $vent="X";
if (is_null ($cond_clim)) $cond_clim="X";
if (is_null ($route)) $route="X";
if (is_null ($riviere)) $riviere="X";
if (is_null ($corine1)) $corine1="X";
if (is_null ($corine2)) $corine2="X";
if (is_null ($corine3)) $corine3="X";
if (is_null ($corine4)) $corine4="X";
if (is_null ($info_1)) $info_1="X";
if (is_null ($info_2)) $info_2="X";
if (is_null ($sexe)) $sexe="X";

// Formulaire en html
include "Ajout_formulaire_formulaire_html.php";
echo '<input type="hidden"  name="mode"  value="INSERTION">';

	//l'espèce est saisie et est correcte
	if ($espece<>"undefined" && $espece<>"" && $espece<>"0")
	{
		?>
					
			<p align=center><INPUT type="submit" class="gros" name="valider_final" value="Valider la donnée">
			</p>
			<!--Fin du fieldset-->
			</fieldset>		
</form>

		<?php
    }
}

function FormMajDonnees ($numero, $mail, $bd)
{
include ("Listes.php");	

$resultat  = $bd->execRequete ("SELECT * FROM donnees WHERE numero = '$numero'");
  while ($bo = $bd->objetSuivant ($resultat))
    {
	// L'observateur a-t'il le droit de modifier la donnée ?
		$observateurs = Chercheobservateurs ($mail, $bd);
		if ($bo->obs_1 <> $observateurs->code_obs && $bo->obs_2 <> $observateurs->code_obs && $bo->obs_3 <> $observateurs->code_obs)
		echo "<H3><CENTER>Vous n'avez pas la permission de modifier cette donnée car vous n'êtes pas le propriétaire de celle-ci.</H3></CENTER>";
	else 
	{
		     
//Transformation de la température en NULL et suppression de la virgule
	if ($bo->temperature=='') $temp='NULL';
	$temp= str_replace(',', '.', 	$bo->temperature);
//Récupération des valeurs :
$longitude=$bo->longitude;
$latitude=$bo->latitude;
$date =$bo->date;
$info_1=$bo->info_1;
$info_2=$bo->info_2;
$abo=$bo->abondance;
$sexe=$bo->sexe;
$espece=$bo->espece;
$remarques=$bo->remarques;
$cond_clim=$bo->meteo;
$temp=$bo->temperature;
$vent=$bo->vent;
$route=$bo->route;
$riviere=$bo->riviere;
$corine1=$bo->corine_1;
$corine2=$bo->corine_2;
$corine3=$bo->corine_3;
$corine4=$bo->corine_4;
$obs1=$bo->obs_1;
$obs2=$bo->obs_2;
$obs3=$bo->obs_3;
$determinateur=$bo->type_donnees;

	//Vérification de l'existence de fichiers joints
	$fichier="";

//Transformation des NULL en valeur générique
if (is_null ($vent)) $vent="X";
if (is_null ($cond_clim)) $cond_clim="X";
if (is_null ($route)) $route="X";
if (is_null ($riviere)) $riviere="X";
if (is_null ($corine1)) $corine1="X";
if (is_null ($corine2)) $corine2="X";
if (is_null ($corine3)) $corine3="X";
if (is_null ($corine4)) $corine4="X";
if (is_null ($info_1)) $info_1="X";
if (is_null ($info_2)) $info_2="X";
if (is_null ($sexe)) $sexe="X";

  // Formulaire en html
include "Ajout_formulaire_formulaire_html.php";
?>
<input type="hidden"  name="obs1"  value="<?php echo $obs1;?>">
<input type="hidden"  name="mode"  value="MAJ_CONF">
<input type="hidden"  name="numero"  value="<?php echo $numero;?>">
<?php
//l'espèce est saisie et est correcte
	if ($espece<>"undefined" && $espece<>"" && $espece<>"0")
	{
		?>
	<p align=center><INPUT type="submit" name="MAJ_CONF" value="Modifier la donnée">
	</p>
			
</form>
<?php
}}}}

?>

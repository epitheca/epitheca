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

<!-- Début du formulaire pour l'accès rapide-->
<div class='titre'>Accéder directement à une donnée :</div><br>
<form method="post" action="acces_rapide_fonction.php" name="acces_rapide">
	<input type="text" maxlength="10" size="8" style="width:25%" name="numero" id="numero_rapide" placeholder="numéro">
	<input type="hidden" name="code_obs" value="<?php echo $code_obs;?>">
	<input type="submit" name="acces_rapide" value="Accéder">
</form>


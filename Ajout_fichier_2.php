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
 //Fichier pour l'affichage de fichiers existants
session_start ();
require("Util.php");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

$session = ControleAcces ("Ajout.php", $_POST, session_id(), $bd);
if (SessionValide ($session, $bd))
    {
$observateur = Chercheobservateurs ($session->email, $bd, FORMAT_OBJET);
$code_obs = "$observateur->code_obs";
	}
 ?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" charset="utf-8" > 
<link rel='stylesheet' HREF='Css_iframe_fichier.css' TYPE='text/css'>
<link rel='stylesheet' HREF='Css_fenetre_nodal.css' TYPE='text/css'>
</HEAD>
<BODY>

<?php
 
 //Il s'agit d'une suppression
 if (isset($_GET['sup']))
 {
 //Suppression du fichier
 $identifiant="televersements/";
 $identifiant.=$_GET['sup'];
 unlink ($identifiant);
 //Suppression de la table
 $identifiant=$_GET['sup'];
 $requete  = "DELETE FROM fichiers WHERE identifiant='$identifiant'";
$resultat = $bd->execRequete ($requete);
 }
 
 //Capture du numéro de la donnée	
 $numero=$_GET['numero'];
  
 //Compte du nombre de fichiers joints
 $nbr_fichier=Compte_fichier ($numero, $bd);
 
 //Vérification de l'existence d'un fichier joint
 $fichier=Cherche_fichier ($numero, $bd, $format=FORMAT_OBJET);
 
 
 if ($nbr_fichier>0)
 {
 ?>
<div class="fichier_contenant">
		
		<!--premier fichier-->
		<?php
		
		//Récupération de l'extention
		$extension=strrchr($fichier,'.');
		
		//C'est une image
		if ($extension==".jpg" || $extension==".jpeg" || $extension==".gif" || $extension==".png" || $extension==".JPG" || $extension==".JPEG" || $extension==".GIF" || $extension==".PNG")
			{			
			
			?>
			<div class="fichier_affichage_image">
				<a href='<?php echo "televersements/$fichier";?>' target='_blank' > <img src="<?php echo "televersements/$fichier";?>" width='200px'> </a>
			</div>
			<?php
		}
	
			//C'est un son
			if ($extension==".mp3" || $extension==".MP3")
			{					
			?>
			<div class =fichier_affichage_son>
				<audio controls="controls" style="width: 200px;">
				<source src="<?php echo "televersements/$fichier"; ?>" type="audio/mp3" />
				Votre navigateur n'est pas compatible
				</audio>
			</div>
			<?php
		}
	
		//Il y a au moins deux fichiers
		if ($nbr_fichier>1)
		{
	?>
			
	<?php
			//Recherche dans la table le second fichier
			$res=$bd->execRequete ("SELECT * FROM `fichiers` WHERE `numero_donnee` LIKE '$numero' AND identifiant NOT LIKE '$fichier'");
			while ($bo = $bd->objetSuivant ($res))
			$fichier2=$bo->identifiant;
			
			//Récupération de l'extention
			$extension=strrchr($fichier2,'.');
			
			//C'est un son
			if ($extension==".mp3")
			{						
		?>
				<div class =fichier_affichage_son>
					<audio controls="controls" style="width: 200px;">
					<source src="<?php echo "televersements/$fichier2"; ?>" type="audio/mp3" />
					Votre navigateur n'est pas compatible
					</audio>
				</div>
		<?php
			}
		
			//C'est une image
			if ($extension==".jpg" || $extension==".jpeg" || $extension==".gif" || $extension==".png")
			{				
		?>
				<div class="fichier_affichage_image">
					<a href='<?php echo "televersements/$fichier2";?>' target='_blank' > <img src="<?php echo "televersements/$fichier2";?>" width='200px'> </a>

				</div>
		<?php
			}
		}
		
		//Il y a au moins trois fichiers
		if ($nbr_fichier>2)
			{
			//Recherche dans la table le troisième fichier
			$res=$bd->execRequete ("SELECT * FROM `fichiers` WHERE (`numero_donnee` LIKE '$numero') AND (identifiant NOT LIKE '$fichier') AND (identifiant NOT LIKE '$fichier2')");
			while ($bo = $bd->objetSuivant ($res))
			$fichier3=$bo->identifiant;
		
			//Récupération de l'extention
			$extension=strrchr($fichier3,'.');
		
			//C'est un son
			if ($extension==".mp3")
			{						
		?>
				<div class =fichier_affichage_son>
					<audio controls="controls" style="width: 200px;">
					<source src="<?php echo "televersements/$fichier3"; ?>" type="audio/mp3" />
					Votre navigateur n'est pas compatible
					</audio>
				</div>
		<?php
			}
		
			//C'est une image
			if ($extension==".jpg" || $extension==".jpeg" || $extension==".gif" || $extension==".png")
			{				
		?>
				<div class="fichier_affichage_image">
						<a href='<?php echo "televersements/$fichier3";?>' target='_blank' > <img src="<?php echo "televersements/$fichier3";?>" width='200px'> </a>

				</div>
		<?php
			}
			}
			
		//Il y a quatre fichiers
		if ($nbr_fichier>3)
			{
			//Recherche dans la table le quatrième fichier
			$res=$bd->execRequete ("SELECT * FROM `fichiers` WHERE (`numero_donnee` LIKE '$numero') AND (identifiant NOT LIKE '$fichier') AND (identifiant NOT LIKE '$fichier2') AND (identifiant NOT LIKE '$fichier3')");
			while ($bo = $bd->objetSuivant ($res))
			$fichier4=$bo->identifiant;
		
			//Récupération de l'extention
			$extension=strrchr($fichier4,'.');
		
			//C'est un son
			if ($extension==".mp3")
			{						
		?>
				<div class =fichier_affichage_son>
					<audio controls="controls" style="width: 200px;">
					<source src="<?php echo "televersements/$fichier4"; ?>" type="audio/mp3" />
					Votre navigateur n'est pas compatible
					</audio>
				</div>
		<?php
			}
		
			//C'est une image
			if ($extension==".jpg" || $extension==".jpeg" || $extension==".gif" || $extension==".png")
			{				
		?>
				<div class="fichier_affichage_image">
						<a href='<?php echo "televersements/$fichier4";?>' target='_blank' > <img src="<?php echo "televersements/$fichier4";?>" width='200px'> </a>

				</div>
		<?php
			}
			}
			
		//Ajout des liens de suppression
		
		//1
	?>
		<div class="spacer"></div>
		<div class="fichier_supprimer">
		<a href="Ajout_fichier_2.php?sup=<?php echo $fichier;?>&amp;numero=<?php echo $numero;?>">Supprimer le fichier</a>
		</div>
	<?php
		
		//Il y a au moins deux fichiers
		if ($nbr_fichier>1)
			{
	?>
			<div class="fichier_supprimer">
			<a href="Ajout_fichier_2.php?sup=<?php echo $fichier2;?>&amp;numero=<?php echo $numero;?>">Supprimer le fichier</a>
			</div>
	<?php
			}	
		
		//Il y a au moins trois fichiers
		if ($nbr_fichier>2)
			{
	?>
			<div class="fichier_supprimer">
			<a href="Ajout_fichier_2.php?sup=<?php echo $fichier3;?>&amp;numero=<?php echo $numero;?>">Supprimer le fichier</a>
			</div>
	<?php
			}
			
		//Il y a quatre fichiers
		if ($nbr_fichier>3)
			{
	?>
			<div class="fichier_supprimer">
			<a href="Ajout_fichier_2.php?sup=<?php echo $fichier4;?>&amp;numero=<?php echo $numero;?>">Supprimer le fichier</a>
			</div>
	<?php
			}

}
		//Il y a moins de quatre fichiers, on ajoute propose d'ajouter un fichier
		if ($nbr_fichier<4)
		{
	?>
		<div class="spacer"></div>
		<form action="Ajout_fichier_2_fonction.php" method="post" enctype="multipart/form-data">
		Envoyer un fichier : 
		<input type="hidden" name="numero" value="<?php echo $numero;?>">
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Envoyer" name="submit">
		</form>
	<?php
		}	
	?>		
	</div>
</BODY>
</html>

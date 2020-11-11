<?php

function pasdedonnee ($obs, $bd)
{
//Récupération du nom et prénom 
        $observateur = Chercheobservateursaveccode ($obs, $bd, FORMAT_OBJET);
        
        //Création de l'ancre
        $Ajout='class="lien_fonce"';
        ?>
        <span class="titre">Bienvenue <?php echo  "$observateur->prenom $observateur->nom"; ?> ! <br><br> Pour commencer, ajoutez des données en cliquant sur :
			<br><br>
            
        <a <?php echo $Ajout;?> href=<?php CHEMIN_URL?>"./Ajout.php"><img src="images/logo_ajouter.png" class="img_entete"><p class="text_entete">Ajouter des données</p></a>
            </span>
            <?php
}
?>

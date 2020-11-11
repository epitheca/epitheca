<?php

function mesdonnees ($obs, $bd)
{
    //Calcul du nombre total
$nbrtotal = Calcdontotal ($obs, '', $bd);
    ?>
			<span class="titre">Vous avez saisi <?php echo $nbrtotal; ?> données
			</span>
        
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>        

<?php
    
    //Calcul du nombre de données par groupe pour ajout d'un array
       
        //Création du tableau
        $stats=array();
        //Selection de toutes les données de l'observateur
        $resultat = $bd->execRequete ("SELECT * FROM donnees WHERE obs_1='$obs'");
        while ($bo = $bd->objetSuivant ($resultat))
        {
        $groupe=ChercheGroupeSp ($bo->espece, $bd, $format=FORMAT_OBJET);
        //Insertion du nom du groupe dans la table
        $stats[]=$groupe;
        }
       
    $stats=(array_count_values($stats));
    
    //Récupération des labels :
    $label = json_encode(array_keys($stats));
    
    //Récupération des valeurs :
     $data = json_encode(array_values($stats));
     
     //Transformation de la liste
 $liste_francaise = ChercheGroupeNomFrancaisSp (array_keys($stats), $bd);
 $liste_francaise= json_encode (array_values ($liste_francaise));
 
?>
<canvas id="pie-chart" width="800" height="450"></canvas>

<script>
new Chart(document.getElementById("pie-chart"), {
    type: 'pie',
    data: {
      labels: <?php echo $liste_francaise; ?>,
      datasets: [{
        backgroundColor: ["#B7F57C", "#289E3F", "#3C9E3F", "#509E3F", "#649E3F"],
        data: <?php echo $data; ?>
      }]
    },
    options: {
      
    }
});

</script>
<?php		
        }
?>

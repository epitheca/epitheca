<?php
?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>        
<?php
        //Création du tableau
        $stats=array();
        //Selection de toutes les données de l'observateur
        $resultat = $bd->execRequete ("SELECT MONTHNAME(Timestamp) AS month, YEAR(Timestamp) AS year,  COUNT(code_obs) AS observateur FROM observateurs GROUP BY EXTRACT(YEAR_MONTH FROM Timestamp)");
        while ($bo = $bd->objetSuivant ($resultat))
        {
			$moisetannee="$bo->month $bo->year";
        $stats[$moisetannee]=$bo->observateur;
        }
              
    //Récupération des labels :
    $label = json_encode(array_keys($stats));
    
    //Récupération des valeurs :
     $data = json_encode(array_values($stats));
     
?>
<canvas id="barre_nombre_compte" width="800" height="450"></canvas>

<script>
new Chart(document.getElementById("barre_nombre_compte"), {
    type: 'bar',
    data: {
      labels: <?php echo $label; ?>,
      datasets: [{
        backgroundColor: ["#B7F57C"],
        data: <?php echo $data; ?>
      }]
    },
    options: {
              legend: {
            display: false
    }
}});

</script>

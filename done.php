<?php
$agendaSod = $_GET['agendaSod'];
// Initialisation du tableau pour stocker les données
$donnees = array();

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Vérifie si les champs nécessaires sont définis dans $_POST
    if (isset($_GET['date']) && isset($_GET['country']) && isset($_GET['event'])) {
        // Récupère les valeurs des champs du formulaire
        $date = $_GET['date'];
        $country = $_GET['country'];
        $event = $_GET['event'];

        // Vérifie si le nombre de noms est égal au nombre d'âges
        if (count($date) == count($country)) {
            // Parcours les valeurs pour construire le tableau à deux dimensions
            for ($i = 0; $i < count($country); $i++) {
                $donnees[] = array(
                    'date'=>$date[$i],
                    'country'=>$country[$i],
                    'event'=>$event[$i]
                );
            }
            echo "<pre>";
            print_r($donnees);
            echo "</pre>";

            // Générer le fichier CSV
            $csvFilePath = 'datas/'.$agendaSod.'_datas.csv';
            $csvFile = fopen($csvFilePath, 'w');

            // Écriture de l'en-tête du fichier CSV
            fputcsv($csvFile, array('date', 'country', 'event'));

            // Écriture des données dans le fichier CSV
            foreach ($donnees as $cle => $info) {
                fputcsv($csvFile, array($info['date'], $info['country'], $info['event']));
            }

            fclose($csvFile);

            echo "Le fichier CSV a été généré avec succès.";
        } else {
            echo "Le nombre de clés, de noms et d'âges ne correspond pas.";
        }
    } else {
    echo "Tous les champs du formulaire ne sont pas définis.";
    }
}

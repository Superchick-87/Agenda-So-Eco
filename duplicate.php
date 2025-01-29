<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $agendaSod  = $_POST['agendaSodd'];
    $date       = $_POST['datec'];

    // Chemins des fichiers existants
    $fileDataSource = "datas/{$agendaSod}_datas.csv";
    $filePrefSource = "datas/{$agendaSod}_pref.csv";

    // Chemins des fichiers cibles (avec la nouvelle date)
    $fileDataTarget = "datas/{$date}_datas.csv";
    $filePrefTarget = "datas/{$date}_pref.csv";

    // Vérification de l'existence des fichiers source
    if (file_exists($fileDataSource) && file_exists($filePrefSource)) {
        // Duplication des fichiers avec la nouvelle date
        if (copy($fileDataSource, $fileDataTarget) && copy($filePrefSource, $filePrefTarget)) {
            echo "Fichiers dupliqués avec succès : {$fileDataTarget} et {$filePrefTarget}";
        } else {
            echo "Erreur lors de la duplication des fichiers.";
        }
    } else {
        echo "Les fichiers source n'existent pas : {$fileDataSource} ou {$filePrefSource}";
    }
}


// Redirection vers index.php
header("Location: ./index.php");
exit(); // Toujours ajouter exit() après une redirection pour arrêter l'exécution du script

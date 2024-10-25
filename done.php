<?php

function defaultLand($x)
{
    if ($x === '') {
        $x = 'Sélectionner un pays';
        return $x;
    }
}
$agendaSod = $_GET['agendaSod'];
// Initialisation du tableau pour stocker les données
$donnees = array();

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Vérifie si les champs nécessaires sont définis dans $_GET
    if (isset($_GET['date']) && isset($_GET['country']) && isset($_GET['event'])) {
        // Récupère les valeurs des champs du formulaire
        $date = $_GET['date'];
        $country = $_GET['country'];
        $event = $_GET['event'];

        // Vérifie si le nombre de noms est égal au nombre d'âges
        if (count($date) == count($country) && count($country) == count($event)) {
            // Chemin vers le fichier de pays
            $paysFilePath = 'datas/pays.csv';

            // Lire le fichier pays.csv et stocker les correspondances entre référence (colonne 3) et nom complet du pays (colonne 5)
            $paysCorrespondances = array();

            if (($paysFile = fopen($paysFilePath, 'r')) !== FALSE) {
                // Lire chaque ligne du fichier pays.csv
                while (($ligne = fgetcsv($paysFile, 1000, ',')) !== FALSE) {
                    // Supposons que la référence du pays soit dans la 3e colonne (index 2) et le nom complet du pays dans la 5e colonne (index 4)
                    $paysCorrespondances[$ligne[2]] = $ligne[4];
                }
                fclose($paysFile);
            }

            // Parcours les valeurs pour construire le tableau à deux dimensions
            for ($i = 0; $i < count($country); $i++) {
                // Utiliser la 3e colonne (référence pays) pour obtenir le nom complet du pays
                $countryReference = $country[$i]; // Supposons que 'country' soit la référence
                $countryFullName = isset($paysCorrespondances[$countryReference]) ? $paysCorrespondances[$countryReference] : 'Unknown';

                // Ajouter les données au tableau, y compris le nom complet du pays
                $donnees[] = array(
                    'date' => $date[$i],
                    'country' => $countryReference,
                    'event' => $event[$i],
                    'country_full_name' => $countryFullName // Ajout du nom complet du pays
                );
            }

            // Affichage des données
            // echo "<pre>";
            // print_r($donnees);
            // echo "</pre>";

            // Générer le fichier CSV
            $csvFilePath = 'datas/' . $agendaSod . '_datas.csv';
            $csvFile = fopen($csvFilePath, 'w');

            // Écriture de l'en-tête du fichier CSV
            fputcsv($csvFile, array('date', 'country', 'event', 'country_full_name'));

            // Écriture des données dans le fichier CSV
            foreach ($donnees as $info) {
                // Écrire la ligne avec le nom complet du pays
                fputcsv($csvFile, array($info['date'], $info['country'], $info['event'], $info['country_full_name']));
            }

            fclose($csvFile);

            echo "Le fichier CSV a été généré avec succès.";
        } else {
            echo "Le nombre de dates, de pays et d'événements ne correspond pas.";
        }
    } else {
        echo "Tous les champs du formulaire ne sont pas définis.";
    }

    // Ajouter les données au tableau, y compris le nom complet du pays
    $donneesPref[] = array(
        'inter_Date_Haut' => $_GET['interDateHaut'], // Date espacement haut  
        'inter_Date_Bas' => $_GET['interDateBas'], // Date espacement bas
        'inter_Pays_Haut' => $_GET['interPaysHaut'], // Pays espacement haut  
        'inter_Pays_Bas' => $_GET['interPaysBas'] // Pays espacement bas 
    );
    // Générer le fichier CSV
    $csvFilePathh = 'datas/' . $agendaSod . '_pref.csv';
    $csvFilee = fopen($csvFilePathh, 'w');
    
    if ($csvFilee === false) {
        die("Erreur lors de l'ouverture du fichier CSV.");
    }
    
    // Écriture de l'en-tête du fichier CSV
    fputcsv($csvFilee, array('inter_Date_Haut', 'inter_Date_Bas', 'inter_Pays_Haut', 'inter_Pays_Bas'));

    // Écriture des données dans le fichier CSV
    foreach ($donneesPref as $infoo) {
        // Écrire la ligne avec le nom complet du pays
        fputcsv($csvFilee, array($infoo['inter_Date_Haut'], $infoo['inter_Date_Bas'],$infoo['inter_Pays_Haut'], $infoo['inter_Pays_Bas']));
    }
    fclose($csvFilee);

}
include('pdfV2.php');

// Chemin du dossier et du fichier PDF
$directory = 'ProductionPdf/';
$filename = 'infog_SOD_Agenda_'.$agendaSod .'.pdf';
$filePath = $directory . $filename;
// Vérifier si le fichier existe
if (file_exists($filePath)) {
    // Si le fichier existe, afficher le PDF dans la page
    echo '
    <h3>Votre PDF :</h3>
    <embed src="' . $filePath . '" type="application/pdf" width="70%" height="800px" />';
} else {
    // Si le fichier n'existe pas, afficher un message
    echo '<p>Le fichier PDF n\'existe pas dans le dossier spécifié.</p>';
}

?>





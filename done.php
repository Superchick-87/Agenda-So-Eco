<!DOCTYPE html>
<html lang="fr">
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="css/styles.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Agenda SOD</title>
</head>

<body>
    <?php

    // Fonction pour définir une valeur par défaut pour le pays
function defaultLand($x)
{
    return $x === '' ? 'Sélectionner un pays' : $x;
}

// Fonction pour lire les correspondances des pays depuis le fichier CSV
function lireCorrespondancesPays($filePath)
{
    $paysCorrespondances = [];
    if (($paysFile = fopen($filePath, 'r')) !== false) {
        while (($ligne = fgetcsv($paysFile, 1000, ',')) !== false) {
            $paysCorrespondances[$ligne[2]] = $ligne[4];
        }
        fclose($paysFile);
    }
    return $paysCorrespondances;
}

// Fonction pour générer un fichier CSV
function genererCSV($filePath, $header, $data)
{
    $file = fopen($filePath, 'w');
    if ($file === false) {
        die("Erreur lors de l'ouverture du fichier CSV : $filePath");
    }
    fputcsv($file, $header);
    foreach ($data as $row) {
        fputcsv($file, $row);
    }
    fclose($file);
}

// Vérifie si la méthode est GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $agendaSod = $_GET['agendaSod'];

    // Initialisation des tableaux pour stocker les données
    $donnees = [];
    $donneesPref = [];

    // Chemin vers le fichier de correspondances pays
    $paysFilePath = 'datas/pays.csv';
    $paysCorrespondances = lireCorrespondancesPays($paysFilePath);

    // Vérifie et traite les données principales
    if (isset($_GET['date'], $_GET['country'], $_GET['event'])) {
        $date = $_GET['date'];
        $country = $_GET['country'];
        $event = $_GET['event'];
        $letterSpacing = $_GET['letterSpacing'];

        if (count($date) == count($country) && count($country) == count($event)) {
            for ($i = 0; $i < count($country); $i++) {
                $countryReference = $country[$i];
                $countryFullName = isset($paysCorrespondances[$countryReference]) ? $paysCorrespondances[$countryReference] : 'Unknown';
                $eventText = str_replace("\n", '*@*', $event[$i]);

                $donnees[] = [
                    'date' => $date[$i],
                    'country' => $countryReference,
                    'event' => $eventText,
                    'country_full_name' => $countryFullName,
                    'letter_spacing' => $letterSpacing[$i]
                ];
            }

            // Générer le fichier des données principales
            $csvFilePath = "datas/{$agendaSod}_datas.csv";
            genererCSV($csvFilePath, ['date', 'country', 'event', 'country_full_name', 'letter_spacing'], $donnees);
        } else {
            echo "Le nombre de dates, de pays et d'événements ne correspond pas.";
        }
    } else {
        echo "Tous les champs du formulaire pour les données principales ne sont pas définis.";
    }

    // Vérifie et traite les données de préférences
    if (isset($_GET['interDateHaut'], $_GET['interDateBas'], $_GET['interPaysHaut'], $_GET['interPaysBas'], $_GET['interligne'], $_GET['adjustColonne'])) {
        $donneesPref[] = [
            'inter_Date_Haut' => $_GET['interDateHaut'],
            'inter_Date_Bas' => $_GET['interDateBas'],
            'inter_Pays_Haut' => $_GET['interPaysHaut'],
            'inter_Pays_Bas' => $_GET['interPaysBas'],
            'interligne' => $_GET['interligne'],
            'adjust_Colonne' => $_GET['adjustColonne']
        ];

        // Générer le fichier des préférences
        $csvFilePathh = "datas/{$agendaSod}_pref.csv";
        genererCSV($csvFilePathh, ['inter_Date_Haut', 'inter_Date_Bas', 'inter_Pays_Haut', 'inter_Pays_Bas', 'interligne', 'adjust_Colonne'], $donneesPref);
    } else {
        echo "Tous les champs du formulaire pour les préférences ne sont pas définis.";
    }
}
    include('pdfV2.php');
  

// Chemin du dossier et du fichier PDF
$directory = 'ProductionPdf/';
$filename = 'infog_SOD_Agenda_' . $agendaSod . '.pdf';
$filePath = $directory . $filename;

// Vérifier si le fichier existe
if (file_exists($filePath)) {
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate'); // Pour éviter la mise en cache

   
    readfile($filePath);
    exit;
} else {
    // Si le fichier n'existe pas, afficher un message
    echo '<p>Le fichier PDF n\'existe pas dans le dossier spécifié.</p>';
}


    
?>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">

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

    // Vérifie si la méthode est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $agendaSod = $_POST['agendaSod'];

        // Initialisation des tableaux pour stocker les données
        $donnees = [];
        $donneesPref = [];

        // Chemin vers le fichier de correspondances pays
        $paysFilePath = 'datas/pays.csv';
        $paysCorrespondances = lireCorrespondancesPays($paysFilePath);

        // Vérifie et traite les données principales
        if (isset($_POST['date'], $_POST['country'], $_POST['event'])) {
            $date = $_POST['date'];
            $country = $_POST['country'];
            $event = $_POST['event'];
            $adresse = $_POST['adresse'];
            $phone = $_POST['phone'];
            $mail = $_POST['mail'];
            $web = $_POST['web'];
            $letterSpacing = $_POST['letterSpacing'];

            if (count($date) == count($country) && count($country) == count($event)) {
                for ($i = 0; $i < count($country); $i++) {
                    $countryReference = $country[$i];
                    $countryFullName = isset($paysCorrespondances[$countryReference]) ? $paysCorrespondances[$countryReference] : 'Unknown';
                    $eventText = str_replace("\n", '*@*', $event[$i]);
                    $adresseText = str_replace("\n", '*@*', $adresse[$i]);
                    $phoneText = $phone[$i];
                    $mailText = $mail[$i];
                    $webText = $web[$i];

                    $donnees[] = [
                        'date' => $date[$i],
                        'country' => $countryReference,
                        'event' => $eventText,
                        'country_full_name' => $countryFullName,
                        'letter_spacing' => $letterSpacing[$i],
                        'adresse' => $adresseText,
                        'phone' => $phoneText,
                        'mail' => $mailText,
                        'web' => $webText

                    ];
                }

                // Générer le fichier des données principales
                $csvFilePath = "datas/{$agendaSod}_datas.csv";
                genererCSV($csvFilePath, ['date', 'country', 'event', 'country_full_name', 'letter_spacing', 'adresse', 'phone', 'mail', 'web'], $donnees);
            } else {
                echo "Le nombre de dates, de pays et d'événements ne correspond pas.";
            }
        } else {
            echo "Tous les champs du formulaire pour les données principales ne sont pas définis.";
        }

        // Vérifie et traite les données de préférences
        if (isset($_POST['interDateHaut'], $_POST['interDateBas'], $_POST['interPaysHaut'], $_POST['interPaysBas'], $_POST['interligne'], $_POST['adjustColonne'])) {
            $donneesPref[] = [
                'inter_Date_Haut' => $_POST['interDateHaut'],
                'inter_Date_Bas' => $_POST['interDateBas'],
                'inter_Pays_Haut' => $_POST['interPaysHaut'],
                'inter_Pays_Bas' => $_POST['interPaysBas'],
                'interligne' => $_POST['interligne'],
                'adjust_Colonne' => $_POST['adjustColonne']
            ];

            // Générer le fichier des préférences
            $csvFilePathh = "datas/{$agendaSod}_pref.csv";
            genererCSV($csvFilePathh, ['inter_Date_Haut', 'inter_Date_Bas', 'inter_Pays_Haut', 'inter_Pays_Bas', 'interligne', 'adjust_Colonne'], $donneesPref);
        } else {
            echo "Tous les champs du formulaire pour les préférences ne sont pas définis.";
        }
    };

    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    //@ Fabrication du pdf

    include('pdfV2.php');

    //@ FIN - Fabrication du pdf


    //@ Affichage du pdf

    // Chemin du dossier et du fichier PDF
    $directory = 'ProductionPdf/';
    $filename = 'infog_SOD_Agenda_' . $agendaSod . '.pdf';
    $filePath = $directory . $filename;

    //* Version php : 7.1.32

    // Vérifier si le fichier existe avant de l'afficher
    if (file_exists($filePath)) {
        // Affichage avec la balise <iframe>
        echo '<div class="iframe" >
            <iframe src="' . htmlspecialchars($filePath) . '#zoom=140" 
                    width="540px" 
                    height="1120px" 
                    style="border:none;">
            </iframe>
        </div>';
    } else {
        // Affichage d'un message si le fichier est introuvable
        echo '<div style="color: red; text-align: center; margin-top: 20px;">
            Le fichier PDF demandé est introuvable.
        </div>';
    }
    //* FIN - Version php : 7.1.32

    //* Version php : 7.0.33

    // // Vérifier si le fichier existe
    // if (file_exists($filePath)) {

    //     header('Content-Type: application/pdf');
    //     header('Content-Disposition: inline; filename="' . $filename . '"');
    //     header('Cache-Control: no-cache, no-store, must-revalidate'); // Pour éviter la mise en cache

    //     readfile($filePath);
    //     exit;
    // } else {
    //     // Si le fichier n'existe pas, afficher un message
    //     echo '<p>Le fichier PDF n\'existe pas dans le dossier spécifié.</p>';
    // }

    //* FIN - Version php : 7.0.33

    //@ FIN - Affichage du pdf
    ?>
</body>

</html>
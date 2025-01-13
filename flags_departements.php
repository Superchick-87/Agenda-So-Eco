<?php
set_time_limit(0);  // Augmenter la limite d’exécution

// Dossier de sortie pour les images
$outputDir = "images/flags_S/";
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// Lire le fichier CSV
$csvFile = "datas/dep.csv";
if (($handle = fopen($csvFile, "r")) !== false) {
    // Ignorer la première ligne (en-têtes)
    fgetcsv($handle);

    // Initialisation de la position Y pour le texte
    $lastY = 0;

    // Parcourir chaque ligne du CSV
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $num = $data[0];
        $depFile = $data[1];  // Nom de fichier provenant de la colonne dep_file
        $dep = $data[2];       // Nom du département

        // Paramètres de l'image
        $width = 2560;
        $height = 1536;
        $bgColor = [230, 21, 14];  // Fond en RVB
        $textColor = [255, 255, 255];  // Blanc
        $letterSpacing = strlen($num) > 2 ? 20 : 40;  // Réduire l'espacement à 20 si plus de 2 caractères

        // Créer une image vide
        $image = imagecreatetruecolor($width, $height);

        // Remplir l'image avec la couleur de fond
        $bg = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
        imagefill($image, 0, 0, $bg);

        // Définir la couleur du texte
        $color = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);

        // Définir la police et la taille du texte
        $fontFile = __DIR__ . "/fonts/Utopia Std/UtopiaStd-SemiboldDisp.ttf";  // Chemin vers la police
        $fontSize = 800;  // Taille de police

        // Calculer la largeur totale du texte en tenant compte de l'espacement
        $textLength = strlen($num);
        $totalTextWidth = 0;
        $totalTextHeight = 0;

        // Parcourir chaque caractère pour calculer la largeur totale
        for ($i = 0; $i < $textLength; $i++) {
            $char = $num[$i];
            $charBBox = imagettfbbox($fontSize, 0, $fontFile, $char);
            $charWidth = abs($charBBox[4] - $charBBox[0]);
            $charHeight = abs($charBBox[5] - $charBBox[1]);

            $totalTextWidth += $charWidth;
            $totalTextHeight = max($totalTextHeight, $charHeight);

            // Ne pas ajouter l'espacement après le dernier caractère
            if ($i < $textLength - 1) {
                $totalTextWidth += $letterSpacing;  // Ajouter l'espacement entre les caractères
            }
        }

        // Si le texte ne contient qu'un seul caractère, centrer correctement sans espacement
        if ($textLength == 1) {
            $x = round(($width / 2) - ($totalTextWidth / 2));
        } else {
            // Calculer la position pour centrer horizontalement
            $x = round(($width / 2) - ($totalTextWidth / 2));
        }

        // Vérifier si le texte contient le caractère "1"
        if (strpos($num, '1') !== false) {
            // Si oui, déplacer l'ensemble du texte de 75px vers la gauche
            $x -= 75;
        }

        // Si c'est le premier texte, centrer verticalement dans l'image
        if ($lastY == 0) {
            $y = round(($height / 2) + ($totalTextHeight / 2)); // Centrer verticalement
        } else {
            // Si le texte précédent ne se trouve pas à la base de l'image, ajuster la position Y
            $y = $lastY + 50;  // Décalage de 50px entre les textes
        }

        // Vérifier si le texte dépasse la hauteur de l'image
        if ($y + $totalTextHeight > $height) {
            // Si le texte dépasse, ajuster la position verticale pour réinitialiser
            $y = round(($height / 2) + ($totalTextHeight / 2));  // Centrer à nouveau
        }

        // Ajouter chaque caractère avec l'espacement ajusté
        $currentX = $x;
        for ($i = 0; $i < $textLength; $i++) {
            $char = $num[$i];
            imagettftext($image, $fontSize, 0, $currentX, $y, $color, $fontFile, $char);

            // Ajuster la position pour le prochain caractère
            $charBBox = imagettfbbox($fontSize, 0, $fontFile, $char);
            $charWidth = abs($charBBox[4] - $charBBox[0]);
            $currentX += $charWidth;

            // Ajouter l'espacement après chaque caractère, sauf le dernier
            if ($i < $textLength - 1) {
                $currentX += $letterSpacing;
            }
        }

        // Mettre à jour la position Y pour le texte suivant
        $lastY = $y + $totalTextHeight; // La prochaine position Y sera juste après le texte actuel

        // Enregistrer l'image dans le dossier de sortie avec le nom de fichier provenant de dep_file
        $outputPath = $outputDir . $depFile . ".png";
        imagepng($image, $outputPath);

        // Afficher le nom du fichier traité
        echo "Fichier traité : " . $depFile . ".png<br>";

        // Libérer la mémoire
        imagedestroy($image);
    }

    fclose($handle);
    echo "Images générées avec succès dans le dossier $outputDir";
} else {
    echo "Impossible de lire le fichier CSV.";
}
?>

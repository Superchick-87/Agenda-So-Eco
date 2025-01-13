<?php
set_time_limit(0); // Définir une limite de 300 secondes (5 minutes)

function createSquareRoundFlag($inputImagePath, $outputImagePath) {
    // Charger l'image d'origine
    $image = imagecreatefrompng($inputImagePath);
    imagealphablending($image, true); // Activer la fusion alpha pour PNG
    imagesavealpha($image, true);

    // Obtenir les dimensions de l'image d'origine
    $origWidth = imagesx($image);
    $origHeight = imagesy($image);

    // La taille carrée sera basée sur la hauteur
    $squareSize = $origHeight;

    // Si la largeur est plus grande que la hauteur, on la recadre
    if ($origWidth > $origHeight) {
        // Nouvelle largeur = hauteur, recadrer à partir du centre
        $offsetX = ($origWidth - $squareSize) / 2;
        $offsetY = 0; // Pas besoin de décaler en hauteur
    } else {
        // Si la largeur est inférieure ou égale à la hauteur, on ajuste en largeur
        $offsetX = 0;
        $squareSize = $origWidth; // Utiliser la largeur comme nouvelle taille carrée
    }

    // Créer une image carrée à partir de l'image d'origine
    $croppedImage = imagecreatetruecolor($squareSize, $squareSize);
    imagecopy($croppedImage, $image, 0, 0, $offsetX, $offsetY, $squareSize, $squareSize);

    // Créer une image ronde centrée avec la taille carrée
    $roundedImage = imagecreatetruecolor($squareSize, $squareSize);
    imagealphablending($roundedImage, false);
    imagesavealpha($roundedImage, true);

    // Remplir l'image ronde avec de la transparence
    $transparent = imagecolorallocatealpha($roundedImage, 0, 0, 0, 127);
    imagefill($roundedImage, 0, 0, $transparent);

    // Centre de l'image carrée
    $centerX = $squareSize / 2;
    $centerY = $squareSize / 2;
    $radius = $squareSize / 2;

    // Copier les pixels dans le cercle
    for ($x = 0; $x < $squareSize; $x++) {
        for ($y = 0; $y < $squareSize; $y++) {
            // Calculer la distance par rapport au centre
            $distance = sqrt(pow($x - $centerX, 2) + pow($y - $centerY, 2));
            if ($distance <= $radius) {
                // Copier le pixel uniquement s'il est dans le cercle
                $color = imagecolorat($croppedImage, $x, $y);
                imagesetpixel($roundedImage, $x, $y, $color);
            }
        }
    }

    // Ajouter un contour blanc autour de l'image ronde
    $borderSize = 10; // Épaisseur du contour
    $finalSize = $squareSize + $borderSize * 2;
    $borderImage = imagecreatetruecolor($finalSize, $finalSize);
    $white = imagecolorallocate($borderImage, 255, 255, 255);

    // Remplir le fond avec du blanc
    imagefill($borderImage, 0, 0, $white);

    // Copier l'image ronde sur le fond blanc
    imagecopy($borderImage, $roundedImage, $borderSize, $borderSize, 0, 0, $squareSize, $squareSize);

    // Enregistrer l'image traitée en tant que JPG
    imagejpeg($borderImage, $outputImagePath, 90); // 90 est la qualité de compression JPG

    // Libérer la mémoire
    imagedestroy($image);
    imagedestroy($croppedImage);
    imagedestroy($roundedImage);
    imagedestroy($borderImage);
}

function processAllFlags($inputDir, $outputDir) {
    // Vérifier si le répertoire de sortie existe, sinon le créer
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    // Parcourir tous les fichiers du répertoire source
    $files = glob($inputDir . "*.png"); // Trouver tous les fichiers PNG
    foreach ($files as $file) {
        // Obtenir le nom de fichier sans l'extension
        $filename = pathinfo($file, PATHINFO_FILENAME);

        // Définir le chemin de sortie avec extension .jpg
        $outputImagePath = $outputDir . $filename . ".jpg";

        // Traiter l'image et l'enregistrer au format JPG
        createSquareRoundFlag($file, $outputImagePath);

        echo "Image traitée et enregistrée en JPG : " . $outputImagePath . "</br>";
    }
}

// Définir les répertoires source et de sortie
$inputDirectory = 'images/flags_S/'; // Dossier source contenant les images PNG
$outputDirectory = 'images/flags/'; // Dossier où les JPG seront enregistrés

// Traiter toutes les images
processAllFlags($inputDirectory, $outputDirectory);

?>

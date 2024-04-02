<?php
require_once('TCPDF/tcpdf.php');

/**
 * Extend TCPDF to work with multiple columns
 */
class MC_TCPDF extends TCPDF {

    /**
     * Print content in two columns with borders, left-aligned justified text
     * @param $content (array) array of content for the columns
     * @param $colWidth (int) width of each column
     * @param $colSpacing (int) spacing between columns
     * @param $startY (int) starting Y position
     * @param $colHeight1 (int) height of the first column
     * @param $colHeight2 (int) height of the second column
     * @param $borderWidth (int) width of the border
     * @param $fontSize (float) font size of the text
     * @param $minHeight (int) minimum height for each event
     * @public
     */
    public function PrintTwoColumnsWithBorder($content, $colWidth, $colSpacing, $startY, $colHeight1, $colHeight2, $borderWidth, $fontSize, $minHeight) {
        $x1 = 0; // Starting position of column 1
        $x2 = $colWidth + $colSpacing; // Starting position of column 2
        $y1 = $startY;
        $y2 = 1; // Y position of column 2
    
        // Set font size
        $this->SetFont('', '', $fontSize);
    
        // Loop through each content item
        foreach ($content as $item) {
            // Calculate the height of the current cell
            $numLines = ceil($this->getStringHeight($colWidth, $item['text']) / $fontSize); // Calculate the number of lines
            $cellHeight = max($minHeight, $numLines * $fontSize); // Height of the cell based on the number of lines
    
            // Draw border for column 1 cell
            $this->Rect($x1, $y1, $colWidth, $cellHeight);
    
            // Write content to the first column if it's not full
            if ($y1 + $cellHeight <= $startY + $colHeight1) {
                $this->SetXY($x1, $y1); // Position the text inside the column without any margin
                $this->MultiCell($colWidth, $fontSize, $item['text'], 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);
                $y1 += $cellHeight; // Increment the Y position for the next item
            } else {
                // Draw border for column 2 cell
                $this->Rect($x2, $y2, $colWidth, $cellHeight);
    
                // Write content to the second column if the first column is full
                $this->SetXY($x2, $y2); // Position the text inside the column without any margin
                $this->MultiCell($colWidth, $fontSize, $item['text'], 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);
                $y2 += $cellHeight; // Increment the Y position for the next item
            }
        }
    }
}

// Création d'une nouvelle instance MC_TCPDF
$pdf = new MC_TCPDF('P', 'mm', array(97.8, 197.5), true, 'UTF-8', false);

// Ajouter une nouvelle page
$pdf->AddPage();

// Paramètres du document
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Votre nom');
$pdf->SetTitle('Titre du document');
$pdf->SetSubject('Sujet');
$pdf->SetKeywords('TCPDF, PDF, exemple, test, guide');

// Paramètres de la page
$pdf->SetMargins(0, 0, 0); // Marge gauche, droite, haut
$pdf->SetAutoPageBreak(false); // Désactiver le saut de page automatique

// Lecture des données CSV
$csvFile = 'datas/2024-03-31_datas.csv';
if (file_exists($csvFile)) {
    $csvData = file_get_contents($csvFile);
    $lines = explode(PHP_EOL, $csvData);

    // Variable pour suivre si c'est la première ligne
    $firstLine = true;

    // Définition des colonnes
    $colonneLargeur = 46.5;
    $colonneHauteur1 = 170; // Hauteur de la colonne 1
    $colonneHauteur2 = 195; // Hauteur de la colonne 2
    $espaceEntreColonnes = 4.5; // Espace entre les colonnes
    $minHeight = 12; // Hauteur minimale de chaque événement

    // Tableau pour contenir les données à imprimer dans les colonnes
    $content = array();

    // Boucle pour chaque ligne du CSV
    foreach ($lines as $line) {
        // Sauter la première ligne
        if ($firstLine) {
            $firstLine = false;
            continue;
        }

        $data = str_getcsv($line);
        // Vérifier si la ligne contient au moins 3 colonnes
        if (count($data) >= 3) {
            $event = $data[2]; // Supposons que la troisième colonne contient le texte 'event'
            $country = $data[1]; // Supposons que la deuxième colonne contient le nom du pays

            // Ajouter le contenu à imprimer dans la colonne 1
            $content[] = array(
                'text' => $event,
            );
        }
    }

    // Impression du contenu dans deux colonnes avec bordures et texte aligné à gauche
    $pdf->PrintTwoColumnsWithBorder($content, $colonneLargeur, $espaceEntreColonnes, 26, $colonneHauteur1, $colonneHauteur2, 1, 9, $minHeight);
} else {
    die('Le fichier CSV est introuvable.');
}

// Génération du PDF
ob_clean(); // Effacer tout contenu de sortie avant de générer le PDF
$pdf->Output('exemple.pdf', 'I');
?>

<?php
require_once('TCPDF/tcpdf.php');

// Créer une nouvelle instance de TCPDF
$pdf = new TCPDF('P', 'mm', array(100, 195), true, 'UTF-8', false);

// Définir les informations du document
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Votre nom');
$pdf->SetTitle('Document PDF avec deux colonnes');
$pdf->SetSubject('Exemple de document PDF avec deux colonnes');
$pdf->SetKeywords('TCPDF, exemple, deux colonnes, PDF');

// Ajouter une nouvelle page
$pdf->AddPage();
// Définir les marges
$pdf->SetMargins(0, 0, 0);

// Fonction pour générer le contenu HTML des deux colonnes à partir des données CSV
function generateHTMLColumns($data) {
    $htmlContent = '';

    // Début des balises div pour chaque colonne avec un contour noir
    $htmlLeftColumn = '<div style="font-size:12px; width: 30mm;  position: fixed; left:10mm; border: 1px solid black;">';
    $htmlRightColumn = '<div style="font-size:9px;width: 48%; border: 1px solid black; padding: 5px;">';

    // Commencer à partir de la deuxième ligne du CSV (excluant l'en-tête)
    for ($i = 1; $i < count($data); $i++) {
        $row = $data[$i];

        // Générer le contenu HTML pour chaque ligne du CSV (colonne "event" uniquement)
        $htmlRow = '<p style="margin: 5px;">' . $row[2] . '</p>'; // Colonne "event"

        // Déterminer dans quelle colonne placer la ligne en fonction du numéro de ligne
        if ($i % 2 == 0) {
            // Ajouter la ligne à la deuxième colonne
            $htmlRightColumn .= $htmlRow;
        } else {
            // Ajouter la ligne à la première colonne
            $htmlLeftColumn .= $htmlRow;
        }
    }

    // Fermer les balises div pour chaque colonne
    $htmlLeftColumn .= '</div>';
    $htmlRightColumn .= '</div>';

    // Fusionner le contenu des deux colonnes
    $htmlContent = $htmlLeftColumn . $htmlRightColumn;

    return '<div>'.$htmlContent.'</div>';
}

// Lire le contenu du fichier CSV
$filename = 'datas/2024-03-31_datas.csv';
$data = [];
if (($handle = fopen($filename, 'r')) !== false) {
    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        $data[] = $row;
    }
    fclose($handle);
}

// Générer le contenu HTML des deux colonnes à partir de la colonne "event" du CSV (en excluant l'en-tête)
$htmlContent = generateHTMLColumns($data);

// Écrire le contenu HTML dans le PDF
$pdf->writeHTML($htmlContent, true, false, true, false, '');

// Sortie du document au navigateur
$pdf->Output('exemple.pdf', 'I');
?>

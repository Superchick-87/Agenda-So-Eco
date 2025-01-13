<?php
require_once('TCPDF/tcpdf.php');

include('includes/jourEnFr.php');
include('includes/manipText.php');
// Envoyer les en-têtes HTTP pour générer le fichier PDF
// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="document.pdf"');
// header('Cache-Control: private, max-age=0, must-revalidate');
// header('Pragma: public');

/**
 * Extend TCPDF to work with multiple columns
 */
class MC_TCPDF extends TCPDF
{

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
    public function PrintTwoColumnsWithBorder($content, $colWidth, $colSpacing, $startY, $colHeight1, $colHeight2, $borderWidth, $fontSize, $minHeight)
    {
        $x1 = 0; // Position de la colonne 1
        $x2 = $colWidth + $colSpacing; // Position de la colonne 2
        $y1 = $startY;
        $y2 = 1; // Position Y de la colonne 2, alignée avec celle de la colonne 1

        $borderWidth = 0;

        // Définir la taille de police
        $this->SetFont('', '', $fontSize);

        // Combiner tous les événements en une seule chaîne
        $allEvents = '';
        foreach ($content as $item) {
            $allEvents .= "" . $item['text'] . ""; // Concaténer tous les textes
        }

        // Calculer la hauteur de tout le contenu
        $totalLines = ceil($this->getStringHeight($colWidth, $allEvents, '', true, 0, false, true, $colWidth, 'T') / $fontSize);
        $totalHeight = max($minHeight, $totalLines * $fontSize);

        // Si le contenu tient dans la première colonne
        if ($totalHeight <= $colHeight1) {
            // Ecrire le contenu dans la première colonne sans bordure
            $this->SetXY($x1, $y1);
            $this->writeHTMLCell($colWidth, $totalHeight, '', '', $allEvents, 0, 1, false, true, 'L', true);
        } else {
            // Diviser le contenu en deux colonnes
            $linesPerColumn = ceil(count($content) / $_POST['adjustColonne']);
            $column1Events = array_slice($content, 0, $linesPerColumn + 0.5);
            $column2Events = array_slice($content, $linesPerColumn);

            // Préparer le texte pour la première colonne
            $column1Text = '';
            foreach ($column1Events as $event) {
                $column1Text .= $event['text'] . "";
            }

            // Préparer le texte pour la deuxième colonne
            $column2Text = '';
            foreach ($column2Events as $event) {
                $column2Text .= $event['text'] . "";
            }

            // Ecrire le contenu dans la première colonne sans bordure
            $this->SetXY($x1, $y1);
            $this->writeHTMLCell($colWidth, $colHeight1, '', '', $column1Text, 0, 1, false, true, 'L', true);

            // Ecrire le contenu dans la deuxième colonne sans bordure
            $this->SetXY($x2, $y2);
            $this->writeHTMLCell($colWidth, $colHeight2, '', '', $column2Text, 0, 1, false, true, 'L', true);
        }
    }

    /**
     * Split a string into two parts based on available height
     * @param $width (float) width of the text area
     * @param $text (string) text to split
     * @param $availableHeight (float) available height for the text
     * @param $adjustLineHeight (bool) whether to adjust line height
     * @param $adjustFontSize (bool) whether to adjust font size
     * @return array containing two parts of the text
     */
}


// Création d'une nouvelle instance MC_TCPDF
$pdf = new MC_TCPDF('P', 'mm', array(97.8, 197.5), true, 'UTF-8', false);

// Supprimer les marges par défaut
$pdf->SetMargins(0, 0, 0);  // Gauche, haut, droite
$pdf->SetHeaderMargin(0);    // Marge d'en-tête
$pdf->SetFooterMargin(0);    // Marge de pied de page

// Désactiver l'en-tête et le pied de page
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

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

// $pdf->SetFont('UtopiaStdSemiboldDisp', '', 14, '', false);
$pdf->SetFont('utopiastdb', '', 14, '', false);
$pdf->SetFont('utopiastd', '', 14, '', false);
$pdf->SetFont('robotob', '', 14, '', false);
$pdf->SetTextColor(0, 0, 0, 100);


$pays = '
    font-family:roboto;
    font-weight:bold;
';

// Ajouter une image SVG en haut à gauche
$pdf->ImageSVG(
    $file = 'images/fond.svg',  // Chemin de l'image SVG
    $x = 0,  // Position X
    $y = 0,  // Position Y
    $w = 97.8,  // Largeur de l'image
    $h = 197.5,  // Hauteur de l'image
    '',       // Lien si cliquable (laisser vide si aucun)
    '',       // Alignement de l'image
    '',       // Palette des couleurs (laisser vide pour les couleurs de base)
    0,        // Suppression de bordure (0 pour non)
    false     // Conserver l'aspect proportionnel
);




// Lecture des données CSV
$csvFile = "datas/{$agendaSod}_datas.csv";
// $csvFile = 'datas/2024-03-31_datas.csv';
if (file_exists($csvFile)) {
    // Ajuster le texte du pays en utilisant la fonction d'ajustement
    $adjustedCountry = interletter($country_full_name);

    $csvData = file_get_contents($csvFile);
    $lines = explode(PHP_EOL, $csvData);

    // Variable pour suivre si c'est la première ligne
    $firstLine = true;
    // $pdf->SetFont($font_family = 'utopiastd', '', 14, '', false);

    // Définition des colonnes
    $colonneLargeur = 48;
    $colonneHauteur1 = 180; // Hauteur de la colonne 1
    $colonneHauteur2 = 195; // Hauteur de la colonne 2
    $espaceEntreColonnes = 2; // Espace entre les colonnes
    $minHeight = 12; // Hauteur minimale de chaque événement
    $fontSize = 9.5; // Taille de la police

    // Tableau pour contenir les données à imprimer dans les colonnes
    $content = array();

    // Initialiser une variable pour savoir si c'est le premier ajout
    $firstLine = true;
    $previousDate = '';
    $firstEvent = true;

    // Boucle pour chaque ligne du CSV
    foreach ($lines as $line) {
        // Sauter la première ligne
        if ($firstLine) {
            $firstLine = false;
            continue;
        }

        $data = str_getcsv($line);
        // Vérifier si la ligne contient au moins 3 colonnes
        if (count($data) >= 4) {
            $date = trim($data[0]);
            $event = trim($data[2]);
            $country = trim($data[1]);
            $country_full_name = trim($data[3]);
            $letter_Spacing = $data[4];

            // Vérifier si la date est différente de la date précédente
            if ($date !== $previousDate) {
                // Ajouter une ligne avec la date
                // $content[] = array(
                //     'text' => '
                //     <p style="margin: 0; padding: 0; line-height:' . $_POST['interDateHaut'] . 'px; font-size: 2pt;">,</p> 
                //     <img src="images/jours/' . afficherJourSuivant($date) . '.svg"/>
                //     <p style="margin: 0; padding: 0; line-height:' . $_POST['interDateBas'] . 'px; font-size: 2pt;">,</p> 
                // ',
                // );

                $content[] = array(
                    'text' => '
                    <p style="margin: 0; padding: 0; line-height:' . $_POST['interDateHaut'] . 'px; font-size: 2pt;">,</p> 
                    <div style="line-height: 18px; font-family:utopiastd; background-color: #d42e1a; color:white; font-size:14;"> ' . coupeMois(afficherJourSuivant($date)) . '</div>
                    <p style="margin: 0; padding: 0; line-height:' . $_POST['interDateBas'] . 'px; font-size: 3pt;">,</p> 
                ',
                );

                // Mettre à jour la date précédente
                $previousDate = $date;

                // Réinitialiser la variable pour les événements de cette date
                $firstEvent = true;
            }

            // Chemin de l'image du drapeau
            $flagImage = 'images/flags/' . $country . '.jpg';

            // Vérifier si le fichier image existe
            if (file_exists($flagImage)) {

                // Ajouter le pays et l'événement
                $content[] = array(
                    'text' => '
                <div style="line-height:' . $_POST['interPaysHaut'] . 'px;"> </div>
                    <div style="line-height:1px;  position:relative; margin-left:90px;  width:100%; padding:0;">
                        <span style="font-size:14px; color:white; letter-spacing:-1pt;">--</span>
                        <span style="font-family : robotob; ' . interletter(strlen($country_full_name)) . 'width:80%; font-size:11px;">' . htmlspecialchars($country_full_name) . '</span>
                    </div>
                    <img src="' . $flagImage . '" style="line-height:33px; padding:0; height:5mm;"/>
                    <div style="line-height:' . $_POST['interPaysBas'] . 'px;"></div>
                    <div style="font-family:utopiastd; word-break: break-all; width:100%; letter-spacing: ' . $letter_Spacing . 'pt; font-size:9.5; line-height:' . $_POST['interligne'] . 'px;">' . turn(exposant($event)) . '</div>
                    ',
                );



                // Marquer que le premier événement pour cette date a été traité
                $firstEvent = false;
            } else {
                // Si le drapeau n'existe pas, on ajoute juste l'événement
                $content[] = array(
                    'text' => turn(htmlspecialchars($event)), // Ajuster le texte de l'événement
                );
            }
        }
    }


    // Impression du contenu dans deux colonnes avec bordures et texte aligné à gauche
    $pdf->PrintTwoColumnsWithBorder($content, $colonneLargeur, $espaceEntreColonnes, 18, $colonneHauteur1, $colonneHauteur2, 1, $fontSize, $minHeight);
} else {
    die('Le fichier CSV est introuvable.');
}

// Chemin du dossier où enregistrer le fichier PDF
$directory = __DIR__ . '/ProductionPdf/'; // Exemple : 'pdf_files/' ou '/var/www/html/pdf/'

// Vérifier si le dossier existe, sinon le créer
if (!file_exists($directory)) {
    mkdir($directory, 0777, true); // Créer le dossier avec les permissions
}

// Nom du fichier PDF
$filename = 'infog_SOD_Agenda_' . $agendaSod . '.pdf'; // Le nom du fichier PDF

// Enregistrer le fichier PDF dans le dossier spécifique
ob_clean();
$pdf->Output($directory . $filename, 'F'); // 'F' pour sauver dans un fichier
// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="infog_SOD_Agenda_"');
// header('Cache-Control: private, max-age=0, must-revalidate');
// header('Pragma: public');
// $pdf->Output('infog_SOD_Agenda_', 'I'); // 'F' pour sauver dans un fichier
ob_end_clean();
// echo "Le PDF a été généré avec succès dans le dossier : " . $directory . $filename;

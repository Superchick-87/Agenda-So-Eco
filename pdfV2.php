<?php
require_once('TCPDF/tcpdf.php');

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
        $x1 = 0; // Starting position of column 1
        $x2 = $colWidth + $colSpacing; // Starting position of column 2
        $y1 = $startY;
        $y2 = 1; // Y position of column 2

        // Set font size
        $this->SetFont('', '', $fontSize);

        // Combine all events into a single string
        $allEvents = '';
        foreach ($content as $item) {
            $allEvents .= "<br/>" . $item['text'] . "<br/>";
        }

        // Calculate the height of all events combined
        $totalLines = ceil($this->getStringHeight($colWidth, $allEvents, '', true, 0, false, true, $colWidth, 'T') / $fontSize); // Adjust line height
        $totalHeight = max($minHeight, $totalLines * $fontSize);

        // Check if all events can fit in column 1
        if ($totalHeight <= $colHeight1) {
            // Draw border for column 1 cell
            $this->Rect($x1, $y1, $colWidth, $totalHeight);

            // Write content to column 1
            $this->SetXY($x1, $y1); // Position the text inside the column without any margin
            $this->writeHTMLCell($colWidth, $totalHeight, '', '', $allEvents, 0, 1, false, true, 'L', true);
        } else {
            // Split the text into two columns without repeating events
            $linesPerColumn = ceil(count($content) / 2);
            $column1Events = array_slice($content, 0, $linesPerColumn + 1);
            $column2Events = array_slice($content, $linesPerColumn);

            // Prepare text for column 1
            $column1Text = '';
            foreach ($column1Events as $event) {
                $column1Text .= $event['text'] . "";
            }

            // Prepare text for column 2 (excluding events already in column 1)
            $column2Text = '';
            foreach ($column2Events as $event) {
                if (!in_array($event, $column1Events)) {
                    $column2Text .= $event['text'] . "";
                }
            }

            // Draw border for column 1 cell
            $this->Rect($x1, $y1, $colWidth, $colHeight1);

            // Write content to column 1
            $this->SetXY($x1, $y1); // Position the text inside the column without any margin
            $this->writeHTMLCell($colWidth, $colHeight1, '', '', $column1Text, 0, 1, false, true, 'L', true);

            // Draw border for column 2 cell
            $this->Rect($x2, $y2, $colWidth, $colHeight2);

            // Write content to column 2
            $this->SetXY($x2, $y2); // Position the text inside the column without any margin
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
    private function getStringSplit($width, $text, $availableHeight, $adjustLineHeight = true, $adjustFontSize = true)
    {
        // Set current font
        $currentFont = $this->FontFamily;
        $currentFontSize = $this->FontSizePt;

        // Initialize variables

        $textParts = array('', '');
        $remainingText = $text;
        $lineHeight = $this->getCellHeightRatio() * $this->FontSize;
        $currentHeight = 0;
        $maxHeight = $availableHeight * 11; // Add a small buffer to avoid cutting off text

        // Adjust font size and line height if needed
        if ($adjustFontSize) {
            $fontSizeRatio = $availableHeight / $lineHeight;
            $this->SetFontSize($currentFontSize * $fontSizeRatio);
            $lineHeight = $this->getCellHeightRatio() * $this->FontSize; // Update line height with adjusted font size
        }

        // Loop through each character in the text
        for ($i = 0; $i < strlen($text); $i++) {
            // Add the character to the current part of the text
            $textParts[0] .= $text[$i];
            $textParts[1] = $remainingText;

            // Calculate the height of the current part of the text
            $currentHeight = $this->getStringHeight($width, $textParts[0], '', true, 0, false, true, $width, 'T');

            // Check if the current part of the text exceeds the available height
            if ($currentHeight >= $maxHeight) {
                // If adjustLineHeight is true, adjust the line height to fit the available height
                if ($adjustLineHeight) {
                    $lineHeightRatio = $availableHeight / $currentHeight;
                    $this->SetFontSize($this->FontSize * $lineHeightRatio);
                    $lineHeight = $this->getCellHeightRatio() * $this->FontSize;
                }

                // Recalculate the height of the current part of the text with the adjusted line height
                $currentHeight = $this->getStringHeight($width, $textParts[0], '', true, 0, false, true, $width, 'T');
                // Calculate the remaining text
                $remainingText = substr($text, $i);
                break;
            }
        }

        // Reset font size to original value
        $this->SetFont($currentFont, '', $currentFontSize);

        return array($textParts[0], $remainingText);
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
$csvFile = $csvFilePath;
// $csvFile = 'datas/2024-03-31_datas.csv';
if (file_exists($csvFile)) {
    $csvData = file_get_contents($csvFile);
    $lines = explode(PHP_EOL, $csvData);

    // Variable pour suivre si c'est la première ligne
    $firstLine = true;

    // Définition des colonnes
    $colonneLargeur = 46.8;
    $colonneHauteur1 = 180; // Hauteur de la colonne 1
    $colonneHauteur2 = 195; // Hauteur de la colonne 2
    $espaceEntreColonnes = 4.2; // Espace entre les colonnes
    $minHeight = 12; // Hauteur minimale de chaque événement
    $fontSize = 9.5; // Taille de la police

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
            $date = trim($data[0]); // Utilisez trim() pour supprimer les espaces blancs autour de la date
            $event = trim($data[2]); // Utilisez trim() pour supprimer les espaces blancs à la fin du texte
            $country = $data[1]; // Supposons que la deuxième colonne contient le nom du pays

            // Vérifier si la date est différente de la date précédente
            if ($date !== $previousDate) {
                // Ajouter une ligne avec la date
                $content[] = array(
                    'text' => '
                           

                            <div style="color:red; border: 1px solid black; font-family: Roboto; font-size: 15pt; font-weight: bold;">' . $date . '</div>
                           <p style=" margin: 0; padding: 0; line-height:0pt; font-size: 5pt;">,</p> 

                            ',
                );
                // Mettre à jour la date précédente
                $previousDate = $date;
            }

            // Chemin de l'image du drapeau
            $flagImage = 'images/flags/' . $country . '.png';

            // Vérifier si le fichier image existe
            if (file_exists($flagImage)) {
                // Ajouter le texte à imprimer avec le drapeau
                $content[] = array(
                    'text' => '                  
                    <table style="width: auto; margin: 0mm;  padding: 0mm; border-spacing: 0; ">
                        <tr style="vertical-align: middle; margin: 0mm; padding: 0mm;">
                        <td style="text-align: left; width: 8mm; height: 5mm; margin: 0mm; padding: 0mm;">
                        <img src="' . $flagImage . '" style="height: 5mm; margin: 0mm; padding: 0mm;" />
                        </td>
                        <td style="line-height: 12pt; text-align: left; font-family: Roboto; font-size: 15pt; font-weight: bold; margin: 0mm; padding: 0mm;">
                        ' . $country . '
                        </td>
                        </tr>
                        <tr style="margin: 0mm; padding: 0mm;">
                        <td colspan="2" style="width: 45mm; line-height: 9.5pt; font-family: Times; font-size: 9.5pt; margin: 0mm; padding: 0mm;">
                        ' . $event . '
                        </td>
                        </tr>
                    </table>
                    <p style=" margin: 0; padding: 0; line-height:2pt; font-size: 5pt;">...........................................................................................</p> 

                                '

                );
            } else {
                $content[] = array(
                    'text' => $event,
                );
            }
        }
    }

    // Impression du contenu dans deux colonnes avec bordures et texte aligné à gauche
    $pdf->PrintTwoColumnsWithBorder($content, $colonneLargeur, $espaceEntreColonnes, 16, $colonneHauteur1, $colonneHauteur2, 1, $fontSize, $minHeight);
} else {
    die('Le fichier CSV est introuvable.');
}

// Génération du PDF
ob_clean(); // Effacer tout contenu de sortie avant de générer le PDF
$pdf->Output('exemple.pdf', 'I');

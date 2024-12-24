<?php
// Dans MC_TCPDF.php
require_once('./TCPDF/tcpdf.php');

class MC_TCPDF extends TCPDF {
    public function ChapterBody($file) {
        $this->selectColumn();

        // Lire les données du fichier CSV
        $data = [];
        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }

        // Définir la taille des colonnes
        $this->SetColumns(2, 45); // Deux colonnes de 45 mm de largeur

        // Définir la police et la couleur du texte
        $this->SetFont('times', '', 9);
        $this->SetTextColor(50, 50, 50);

        // Afficher les données du CSV
        foreach ($data as $row) {
            // Générer le contenu HTML pour chaque ligne du CSV (colonne "event" uniquement)
            $htmlRow = '<p>' . $row[2] . '</p>'; // Colonne "event"

            if ($this->GetX() > 90) {
                $this->selectColumn();
            }

            // Afficher le contenu
            $this->writeHTML($htmlRow, true, false, true, false, 'J');
        }

        $this->Ln();
    }
}
?>

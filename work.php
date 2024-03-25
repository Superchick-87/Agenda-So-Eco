<?php
$agendaSod = $_GET['agendaSod'];

$filename = 'datas/' . $agendaSod . '_datas.csv';

if (file_exists($filename)) {
    $file = fopen($filename, 'r');
    echo '
    <h2 class="state">Agenda en cours</h2>
   
    ';
    echo '
        <h4>Nombre total de signes : <span id="totalCharacters">0</span></h4>

        <form id="form2" action="done.php?" method="get">
        <input type="date" name="agendaSod" id="agendaSod" value="' . $agendaSod . '" style="display:none">
    
        <div id="inputs-container">
    ';

    $headers = fgetcsv($file);
    $i = 1;
    $a = 1;
    $b = 1;
    $c = 1;
    $d = 1;
    $s = 1;
    $z = 1;

    // Lecture du fichier CSV des pays
    $paysFile = fopen('datas/pays.csv', 'r');
    $paysData = [];
    while (($row = fgetcsv($paysFile)) !== false) {
        $paysData[] = $row;
    }
    fclose($paysFile);

    while (($row = fgetcsv($file)) !== false) {
        // Récupération du nom du pays à partir du fichier CSV
        $countryName = $row[1];
        
        echo '
        <div class="input-row" id="input-row-' . $a++ . '">
            <select name="country[]" id="country_' . $s++ . '">';

        // Si aucun pays n'est spécifié dans le fichier CSV
        if (empty($countryName)) {
            // Afficher les options du menu déroulant directement à partir des données obtenues
            foreach ($paysData as $pays) {
                echo '<option value="' . $pays[4] . '">' . $pays[4] . '</option>';
            }
        } else {
            // Sinon, afficher la liste des pays avec la correspondance sélectionnée
            foreach ($paysData as $pays) {
                $selected = ($pays[4] == $countryName) ? 'selected' : '';
                echo '<option value="' . $pays[4] . '" ' . $selected . '>' . $pays[4] . '</option>';
            }
        }

        echo '</select>
            <input id="date_' . $i++ . '" type="date" name="date[]" value="' . $row[0] . '">
            <textarea  id="event_' . $b++ . '" class="input-text" rows="5" name="event[]" placeholder="Evènement">' . $row[2] . '</textarea>
            <div id="remove-btn-' . $d++ . '" onclick="removeInputs(\'input-row-' . $c++ . '\')" class="remove-btn">+</div>
        </div>';
    }

    echo '
       
            
            <input id="valid" class="save pad" type="submit" value="Sauver">
    
    </form>
    <input id="add-btn" onclick="addInputs()" class="add-btn pad" type="button" value="Ajouter un évènement">

    </div>
    ';

    fclose($file);
} else {
    echo "
        <h2>Vous commencez l'édition d'un nouvel agenda.</h2>
    ";
    echo '
        <h4>Nombre total de signe : <span id="totalCharacters">0</span></h4>

        <input id="add-btn" onclick="addInputs()" class="add-btn pad" type="button" value="Ajouter un évènement">

            <form id="form2" action="done.php?" method="get">
               <input type="text" name="agendaSod" value=' . $agendaSod . '>
            <div id="inputs-container">
                </div>
                <input type="submit" value="Envoyer">
            </form>
    ';
}
?>

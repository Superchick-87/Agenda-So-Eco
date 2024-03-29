<?php
$agendaSod = $_GET['agendaSod'];

$filename = 'datas/' . $agendaSod . '_datas.csv';

if (file_exists($filename)) {
    $file = fopen($filename, 'r');
    echo '
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
        <div class="flex" id="selFag' . $d++ . '">
        <input id="date_' . $i++ . '" type="date" name="date[]" value="' . $row[0] . '">
            <select name="country[]" id="country_' . $s++ . '" >';

        // Si aucun pays n'est spécifié dans le fichier CSV
        if (empty($countryName)) {
            // Afficher les options du menu déroulant directement à partir des données obtenues
            foreach ($paysData as $pays) {
                echo '<option value="' . $pays[2] . '">' . $pays[4] . '</option>';
            }
        } else {
            // Sinon, afficher la liste des pays avec la correspondance sélectionnée
            foreach ($paysData as $pays) {
                $selected = ($pays[2] == $countryName) ? 'selected' : '';
                echo '<option value="' . $pays[2] . '" ' . $selected . '>' . $pays[4] . '</option>';
            }
        }
        echo '</select>';
        foreach ($paysData as $pays) {
            if ($pays[2] == $countryName) {
                echo '<div class="flag" id="flag' . $z++ . '" style="background-image: url(images/flags/' . $pays[2] . '.png);"></div>';
            }
        }
        echo '<div id="remove-btn-' . $d++ . '" onclick="removeInputs(\'input-row-' . $c++ . '\')" class="remove-btn">+</div>

       </div>
            <textarea  id="event_' . $b++ . '" class="input-text" rows="5" name="event[]" placeholder="Evènement" oninput="updateTotalCharacters()">' . $row[2] . '</textarea>
        </div>';
    }

    echo '
    <input id="save" class="save pad" type="submit" name="save" value="Sauver">
    <input id="make" class="save pad" type="submit" name="make" value="Générer" style="display:none;">
    <input id="add-btn" onclick="addInputs()" class="add-btn pad" type="button" value="Ajouter un évènement">
    
    </form>
    </div>
    ';
    echo '
    <div class="state colorInfo">
    <h2>Agenda en cours</h2>
    <p id="totalCharacters" style="display:none;">0</p>
    <h4 id="signes"></h4>
    </div>
    ';
    fclose($file);
} else {
    echo '
        <form id="form2" action="done.php?" method="get">
        <input type="text" name="agendaSod" value=' . $agendaSod . ' style="display:none;">
        <div id="inputs-container">
        </div>
        <input id="add-btn" onclick="addInputs()" class="add-btn pad" type="button" value="Ajouter un évènement">
                <input type="submit" value="Envoyer">
            </form>
            <div class="state colorInfo">
            <h2>Nouvel agenda</h2>
            <p id="totalCharacters" style="display:none;">0</p>
            <h4 id="signes"> signes</h4>
            </div>
    ';
}

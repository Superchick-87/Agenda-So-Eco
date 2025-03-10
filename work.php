<?php
$agendaSod = $_GET['agendaSod'];
$csvFilePath = 'datas/' . $agendaSod . '_datas.csv';
include('includes/options.php');
include('includes/manipText.php');
include('includes/jourEnFr.php');

$csvFileePathh = 'datas/' . $agendaSod . '_pref.csv';
$interDateBasValue = null;
$interDateHautValue = null;
$interPaysHautValue = null;
$interPaysBasValue = null;



// Vérifier si le fichier CSV PREF existe
if (file_exists($csvFileePathh)) {
    if (($csvFilee = fopen($csvFileePathh, 'r')) !== false) {
        // Lire l'en-tête du CSV
        fgetcsv($csvFilee); // Ignore the header
        // Lire la première ligne des valeurs
        if (($data = fgetcsv($csvFilee)) !== false) {
            $interDateHautValue = $data[0]; // Récupérer la valeur de 'inter_Date_Haut'
            $interDateBasValue = $data[1];  // Récupérer la valeur de 'inter_Date_Bas'
            $interPaysHautValue = $data[2]; // Récupérer la valeur de 'inter_Date_Haut'
            $interPaysBasValue = $data[3];  // Récupérer la valeur de 'inter_Date_Bas'
            $interLigneValue = $data[4];  // Récupérer la valeur de 'interligne'
            $adjustColonneValue = $data[5];  // Récupérer la valeur de 'adjust_Colonne'
        }

        fclose($csvFilee);
    }
}

if (file_exists($csvFilePath)) {

    $file = fopen($csvFilePath, 'r');
    $agendaSodd = '';

    echo '
    <input id="dupli" class="pad" type="submit" name="Dupliquer" value="Dupliquer" style="display:block;">
        <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1;">
            <div id="partDupli" class="confirmationBox">
                <input type="date" valeur="" name="agendaSodd" id="agendaSodd" style="display:none">
            </div>
        </div>   
    ';

    echo '
    <form id="form2" onsubmit="submitForm(event)">';

    //@ gestion des select pour les paramètres 

    include('includes/barreOpt.php');

    //@ FIN gestion des select pour les paramètres 

    echo '
        <div id="upDown">
            <div id="go-up" onclick="goUp()">></div>
            <div id="go-down" onclick="goDown()">></div>
        </div>
    ';
    echo '<input type="date" name="agendaSod" id="agendaSod" value="' . $agendaSod . '" style="display:none">
    <div id="inputs-container">';

    $headers = fgetcsv($file);
    $i = 1;
    $a = 1;
    $b = 1;
    $c = 1;
    $d = 1;
    $s = 1;
    $z = 1;
    $zz = 1;
    $zzz = 1;
    $xx = 1;
    $coord = 1;
    $adresse = 1;
    $phone = 1;
    $mail = 1;
    $web = 1;

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
    <div class="input-row" id="input-row-' . $a++ . '" onmousedown="startDrag(event)" draggable="false" ondragstart="drag(event)" ondragover="allowDrop(event)" ondrop="drop(event)" ondragend="dragEnd(event)">
        <div class="flex" id="selFag' . $d++ . '">
            <input id="date_' . $i++ . '" type="date" name="date[]" value="' . $row[0] . '" onchange="updateDayName(this)">
            <select name="country[]" id="country_' . $s++ . '">';

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
                echo '<div class="flag" id="flag' . $z++ . '" style="background-image: url(images/flags/' . $pays[2] . '.svg);"></div>';
            }
        }
        echo '<div onclick="toggleVisibility(' . $xx++  . ')" class="toggle-btn">-</div>';
        echo '<div id="remove-btn-' . $d++ . '" onclick="removeInputs(\'input-row-' . $c++ . '\')" class="remove-btn">+</div>';
        echo '</div>';

        echo '<div class="flex_opt">';

        echo '<div class="bloc_jour">';
        //@ interlettrage
        echo '<div class="flex_opt_picto">';

        echo '<div class="picto_opt" style="background-image: url(images/opti_letter_space.svg);"></div>';

        // Démarrer le menu déroulant
        echo '<select id="letterSpacing_' . $zz++ . '" name="letterSpacing[]" class="agendaOpt selopt" onchange="updateLetterSpacing(this)">';

        // Afficher la valeur actuelle du CSV si elle existe
        if (isset($row[4])) { // Assurez-vous que $row[4] existe
            // Afficher cette valeur comme option sélectionnée
            $currentValue = htmlspecialchars($row[4]);
            echo '<option value="' . $currentValue . '" selected>' . $currentValue . '</option>';
        } else {
            // Si aucune valeur n'est présente, ne rien faire
        }

        // Afficher les options du tableau $letterSpacing
        foreach ($letterSpacing as $value => $label) {
            // Vérifier si la valeur actuelle est égale à la valeur du tableau
            $selected = (isset($currentValue) && $value == $currentValue) ? ' selected' : '';
            echo '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>';
        }

        // Terminer le menu déroulant
        echo '</select>';
        echo '</div>';

        //@ FIN interlettrage

        echo '<div class="picto_drag"></div>';
        echo '<h3 id="jour_nom' . $zzz++ . '"  onchange="updateDayName(this)">' . afficherJourSuivant($row[0]) . '</h3>';
        echo '</div>';

        echo '<textarea id="event_' . $b++ . '" style="letter-spacing:' . $row[4] . 'pt;" class="input-text" rows="5" name="event[]" placeholder="Evènement" oninput="updateTotalCharacters()">' . turnFront(htmlspecialchars($row[2])) . '</textarea>';
        echo '</div>';
        echo '
                <div id="coord_' . $coord++ . '" class="bloc_contact">
                    <textarea id="adresse_' . $adresse++ . '" style="letter-spacing:' . $row[4] . 'pt;" class="adresse" rows="1" name="adresse[]" placeholder="Adresse" oninput="updateTotalCharacters()">' . turnFront(htmlspecialchars($row[5])) . '</textarea>
                    <div style="display:flex;">
                    <input id="phone_' . $phone++ . '" type="tel" pattern="[0-9]{10}" maxLength = "10" class="input-tel" name = "phone[]" placeholder = "Téléphone" oninput="updateTotalCharacters()" value="' . $row[6] . '">
                    <input id="mail_' . $mail++ . '" type="email" class="input-email" name = "mail[]" placeholder = "Mail" oninput="updateTotalCharacters()" value="' . $row[7] . '">
                </div>
                <input id="web_' . $web++ . '" type="text" class="input-web" name = "web[]" placeholder = "Site web" oninput="updateTotalCharacters()" value="' . $row[8] . '">
                    </div>';
        echo '</div>';
    }
    echo '
    <div class="add-btn" id="add-btn" onclick="addInputs()">+</div>';
    echo ' <div class="menu">
        <input id="save" class="save pad" type="submit" name="save" value="Sauver" style="display:none;">';

    echo '</div>
    </form>
    </div>';
    echo '
        <div class="state colorInfo">
            <h2>Agenda en cours</h2>
            <div class="detail">
                <p id="totalEvenements" style="display:none;">0</p>
                <p id="evenements" style="display:block;"></p>
                <p id="totalCharacters" style="display:none;">0</p>
                <p id="signes"></p>
            </div>
            </div>';
    fclose($file);
} else {

    echo '
    <form id="form2" onsubmit="submitForm(event)">';

    //@ gestion des select pour les paramètres 

    include('includes/barreOpt.php');


    //@ FIN gestion des select pour les paramètres 

    echo '
    <div id="upDown">
        <div id="go-up" onclick="goUp()">></div>
        <div id="go-down" onclick="goDown()">></div>
    </div>';
    echo '
    <input type="text" name="agendaSod" value=' . $agendaSod . ' style="display:none;">
    <div id="inputs-container">
        <div class="add-btn" id="add-btn" onclick="addInputs()">+</div>
    <div class="menu">
                <input id="save" class="save pad" type="submit" name="save" value="Sauver" style="display:none;">';
    echo '</div>
        </div>
    </form>
    <div class="state colorInfo">
        <h2>Nouvel agenda</h2>
        <div class="detail">
                <p id="totalEvenements" style="display:none;">0</p>
                <p id="evenements" style="display:block;"></p>
                <p id="totalCharacters" style="display:none;">0</p>
                <p id="signes"></p>
            </div>
    </div>';
};

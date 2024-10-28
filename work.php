
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

// Vérifier si le fichier CSV existe
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
        }

        fclose($csvFilee);
    }
}

if (file_exists($csvFilePath)) {
    $file = fopen($csvFilePath, 'r');

    echo '
    <form id="form2" action="done.php?" method="get">';

        //@ gestion des select pour les paramètres 

        include('includes/barreOpt.php');

        //@ FIN gestion des select pour les paramètres 

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
    <div class="input-row" id="input-row-' . $a++ . '" draggable="true" ondragstart="drag(event)" ondragover="allowDrop(event)" ondrop="drop(event)" ondragend="dragEnd(event)">
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
                echo '<div class="flag" id="flag' . $z++ . '" style="background-image: url(images/flags/' . $pays[2] . '.jpg);"></div>';
            }
        }
        echo '<div id="remove-btn-' . $d++ . '" onclick="removeInputs(\'input-row-' . $c++ . '\')" class="remove-btn">+</div>
        </div>';

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
                
                echo '<h3 id="jour_nom' . $zzz++ . '"  onchange="updateDayName(this)">'.afficherJourSuivant($row[0]).'</h3>';

            echo '</div>';
            
            echo '<textarea id="event_' . $b++ . '" style="letter-spacing:'.$row[4].'pt;" class="input-text" rows="5" name="event[]" placeholder="Evènement" oninput="updateTotalCharacters()">' . turnFront(htmlspecialchars($row[2])) . '</textarea>';
        echo '</div>';
        echo '</div>';
    }
    echo '
    <div class="add-btn" id="add-btn" onclick="addInputs()">+
    </div>
    <div class="menu">
        <input id="save" class="save pad" type="submit" name="save" value="Sauver" style="display:none;">';
        echo '<input id="make" class="save pad" type="submit" name="make" value="Générer" style="display:none;">';
     echo '</div>
    </form>
    </div>';

    echo '
        <div class="state colorInfo">
            <h2>Agenda en cours</h2>
            <p id="totalCharacters" style="display:none;">0</p>
            <h4 id="signes"></h4>
        </div>';
    fclose($file);
} else {
    echo '
    <form id="form2" action="done.php?" method="get">';

        //@ gestion des select pour les paramètres 

        include('includes/barreOpt.php');

        //@ FIN gestion des select pour les paramètres 

    echo'
        <input type="text" name="agendaSod" value=' . $agendaSod . ' style="display:none;">
        <div id="inputs-container">
            <div class="add-btn" id="add-btn" onclick="addInputs()">+</div>
            <div class="menu">
                <input id="save" class="save pad" type="submit" name="save" value="Sauver" style="display:none;">';
                //  echo'<input id="make" class="save pad" type="submit" name="make" value="Générer" style="display:none;">';
            echo'</div>
        </div>
    </form>
    <div class="state colorInfo">
        <h2>Nouvel agenda</h2>
        <p id="totalCharacters" style="display:none;">0</p>
        <h4 id="signes"> signes</h4>
    </div>';
};
?>

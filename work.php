<?php
$agendaSod = $_GET['agendaSod'];
// include ('done.php');

$filename = 'datas/' . $agendaSod . '_datas.csv';
echo $filename;

// Vérifier si le fichier CSV existe
if (file_exists($filename)) {
    // Ouvrir le fichier CSV en mode lecture
    $file = fopen($filename, 'r');
    echo "
    <h2>" . "Agenda en cours" . "</h2>
    ";
    // // Afficher les données du fichier CSV
    echo '
        <h4>Nombre total de signe : <span id="totalCharacters">0</span></h4>
        <button id="add-btn" onclick="addInputs() ">+</button></br>
        <form id="form2" action="done.php?" method="get">
            <div id="inputs-container">
    ';

    // // Lire la première ligne du fichier CSV (en-têtes)
    $headers = fgetcsv($file);
    // // Afficher les données avec les clés associées
    $i = 1;
    $a = 1;
    $b = 1;
    while (($row = fgetcsv($file)) !== false) {
        echo '
        <div class="input-row" id="input-row-' . $a++ . '">
        
        <input id="date_' . $i++ . '" type="date" name="date[]" value="' . $row[0] . '">
        <textarea  id="event_' . $b++ . '" class="input-text" rows="5" name="event[]" placeholder="Evènement">' . $row[2] . '</textarea>';
        echo "<button onclick='supprimerParent(this)' class='remove-btn child'>+</button></div>";
    }

    echo '
    <input type="submit" value="Envoyer">
    </form></div>
    ';

    // Fermer le fichier
    fclose($file);
} else {
    echo "
        <h2>" . "Vous commencez l'édition d'un nouvel agenda." . "</h2>
    ";
    echo '
        <h4>Nombre total de signe : <span id="totalCharacters">0</span></h4>

        <button id="add-btn" onclick="addInputs() ">+</button></br>

            <form id="form2" action="done.php?" method="get">
               <input type="text" name="agendaSod" value=' . $agendaSod . '>
            <div id="inputs-container">
                </div>
                <input type="submit" value="Envoyer">
            </form>
    ';
}

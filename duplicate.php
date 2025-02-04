<?php
echo '
     <link rel="stylesheet" type="text/css" href="css/styles.css">
';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification que les champs 'agendaSodd' et 'datec' sont bien définis et non vides
    if (isset($_POST['agendaSodd']) && !empty($_POST['agendaSodd']) && isset($_POST['datec']) && !empty($_POST['datec'])) {
        $agendaSod  = $_POST['agendaSodd'];
        $date       = $_POST['datec'];
        $forceCopy  = $_POST['forceCopy'];  // Récupère la valeur du champ caché 'forceCopy'

        // Debug : Afficher les valeurs des variables agendaSod et date
        // echo "<p>AgendaSod: $agendaSod</p>";
        // echo "<p>Date: $date</p>";

        // Chemins des fichiers source : utiliser la variable 'agendaSod' pour les noms de fichiers
        $fileDataSource = "datas/{$agendaSod}_datas.csv";
        $filePrefSource = "datas/{$agendaSod}_pref.csv";

        // Chemins des fichiers cibles : utiliser la variable 'date' pour les noms de fichiers cibles
        $fileDataTarget = "datas/{$date}_datas.csv";
        $filePrefTarget = "datas/{$date}_pref.csv";

        // Debug : Afficher les chemins des fichiers
        // echo "<p>Chemin fichier source Data: $fileDataSource</p>";
        // echo "<p>Chemin fichier source Pref: $filePrefSource</p>";
        // echo "<p>Chemin fichier cible Data: $fileDataTarget</p>";
        // echo "<p>Chemin fichier cible Pref: $filePrefTarget</p>";

        // Vérification de l'existence des fichiers source
        if (file_exists($fileDataSource) && file_exists($filePrefSource)) {
            // Si les fichiers cibles existent déjà
            if (file_exists($fileDataTarget) || file_exists($filePrefTarget)) {
                // Si 'forceCopy' est égal à 'yes', on copie les fichiers et on les écrase
                if ($forceCopy === 'yes') {
                    // Copier les fichiers cibles (écrasement)
                    copyFiles($fileDataSource, $fileDataTarget, $filePrefSource, $filePrefTarget);
                } else {
                    // Affichage du message de confirmation pour écraser les fichiers
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                            let confirmationBox = document.createElement('div');
                            confirmationBox.classList.add('confirmationBox');
                            
                            let messageBox = document.createElement('p');
                            messageBox.innerHTML = 'Les fichiers existent déjà. Voulez-vous les écraser ?';

                            let confirmButton = document.createElement('button');
                            confirmButton.classList.add('validButton');
                            confirmButton.innerText = 'Oui';
                            confirmButton.onclick = function() {
                                document.getElementById('forceCopy').value = 'yes';
                                document.getElementById('copyForm').submit();
                            };

                            let cancelButton = document.createElement('button');
                            cancelButton.classList.add('cancelButton');
                            cancelButton.innerText = 'Non';
                            cancelButton.onclick = function() {
                                window.history.back();
                            };

                            let divButton = document.createElement('div');
                            divButton.classList.add('containerButton');

                            let divOverlay = document.createElement('div');
                            divOverlay.classList.add('overlay');

                            divButton.appendChild(cancelButton);
                            divButton.appendChild(confirmButton);

                            confirmationBox.appendChild(messageBox);
                            confirmationBox.appendChild(divButton);

                            document.body.appendChild(divOverlay);
                            document.body.appendChild(confirmationBox);
                        });
                    </script>";
                }
            } else {
                // Si les fichiers cibles n'existent pas déjà, on les copie directement
                copyFiles($fileDataSource, $fileDataTarget, $filePrefSource, $filePrefTarget);
            }
        } else {
            echo "Les fichiers source n'existent pas : {$fileDataSource} ou {$filePrefSource}";
        }
    } else {
        echo "Les champs Agenda et Date ne sont pas correctement définis. AgendaSod: {$_POST['agendaSodd']}, Date: {$_POST['datec']}";
    }
}

function copyFiles($fileDataSource, $fileDataTarget, $filePrefSource, $filePrefTarget)
{
    // Essayer de copier les fichiers
    if (copy($fileDataSource, $fileDataTarget) && copy($filePrefSource, $filePrefTarget)) {
        echo "Les fichiers ont été copiés et écrasés avec succès.";
        header("Location: ./index.php"); // Redirection après copie
        exit();
    } else {
        echo "Erreur lors de la duplication des fichiers.";
    }
}

// Formulaire caché qui gère la soumission après confirmation
echo '
<form id="copyForm" method="post">
    <input type="hidden" name="agendaSodd" value="' . htmlspecialchars($agendaSod) . '">
    <input type="hidden" name="datec" value="' . htmlspecialchars($date) . '">
    <input type="hidden" id="forceCopy" name="forceCopy" value="no">
</form>';

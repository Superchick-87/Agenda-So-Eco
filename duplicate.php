<?php
ob_start();  // Démarrer la mise en tampon pour éviter l'erreur headers already sent

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agendaSodd'], $_POST['datec']) && !empty($_POST['agendaSodd']) && !empty($_POST['datec'])) {
        $agendaSod  = $_POST['agendaSodd'];
        $date       = $_POST['datec'];
        $forceCopy  = $_POST['forceCopy'] ?? 'no';

        // Définition des chemins des fichiers
        $fileDataSource = "datas/{$agendaSod}_datas.csv";
        $filePrefSource = "datas/{$agendaSod}_pref.csv";
        $fileDataTarget = "datas/{$date}_datas.csv";
        $filePrefTarget = "datas/{$date}_pref.csv";

        if (file_exists($fileDataSource) && file_exists($filePrefSource)) {
            if (file_exists($fileDataTarget) || file_exists($filePrefTarget)) {
                if ($forceCopy === 'yes') {
                    copyFiles($fileDataSource, $fileDataTarget, $filePrefSource, $filePrefTarget);
                } else {
                    // Demande de confirmation en JS
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let confirmationBox = document.createElement('div');
                                confirmationBox.classList.add('confirmationBox');
                                
                                let messageBox = document.createElement('p');
                                messageBox.innerHTML = 'Un agenda existe déjà. Voulez-vous le remplacer ?';

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
                copyFiles($fileDataSource, $fileDataTarget, $filePrefSource, $filePrefTarget);
            }
        } else {
            echo "Les fichiers source n'existent pas.";
        }
    } else {
        echo "Les champs Agenda et Date ne sont pas définis.";
    }
}

// Fonction de copie avec redirection après succès
function copyFiles($fileDataSource, $fileDataTarget, $filePrefSource, $filePrefTarget)
{
    if (copy($fileDataSource, $fileDataTarget) && copy($filePrefSource, $filePrefTarget)) {
        echo '<script>window.location.href="https://infographie.sudouest.fr/Agenda-So-Eco/index.php";</script>';
        exit();
    } else {
        echo "Erreur lors de la duplication des fichiers.";
    }
}

// Formulaire caché pour confirmation
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda So Eco</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css"> <!-- Ajout ici -->
</head>

<body>

    <form id="copyForm" method="post">
        <input type="hidden" name="agendaSodd" value="<?= htmlspecialchars($agendaSod ?? '') ?>">
        <input type="hidden" name="datec" value="<?= htmlspecialchars($date ?? '') ?>">
        <input type="hidden" id="forceCopy" name="forceCopy" value="no">
    </form>

</body>

</html>

<?php
ob_end_flush(); // Fin de la mise en tampon
?>
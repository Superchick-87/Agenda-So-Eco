<?php

//@ gestion des select pour les paramètres 

echo '<div id="options" class="bloc_opt">';

//* Espacements dates haut / bas
//* haut
// Définir la valeur par défaut
$defaultValue_date_Ht = 1;
// Vérifiez si $interDateHautValue existe, sinon utiliser la valeur par défaut
$selectedValue = isset($interDateHautValue) ? $interDateHautValue : $defaultValue_date_Ht;
// Afficher les menus déroulants avec les options pré-sélectionnées
echo '<div class="flex_opt_picto">
                <div class="picto_opt" style="background-image: url(images/opti_date_up.svg);"></div>';
echo '<select name="interDateHaut" id="interDateHaut" class="agendaOpt selopt">';

// Si la valeur par défaut est utilisée, ajouter une option pour elle
if (!isset($interDateHaut[$selectedValue])) {
    echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';
}

// Utiliser une boucle foreach pour afficher chaque option
foreach ($interDateHaut as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $selectedValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}

echo '</select>';

//* Info bulle
echo '<div class="tooltip" >i
                <div class="tooltiptext">
                    <div>Sert à régler l\'espacement entre le haut de la date en cours et le dernier bloc texte de la date précédente</div>
                    <img class="tooltippict" src="images/note_1.jpg" alt="Description">
                </div>
            </div>';
//* FIN - Info bulle

echo ' </div>';

//* bas
// Définir la valeur par défaut
$defaultValue_date_Bas = -1.5;

// Vérifiez si $interDateBasValue existe, sinon utiliser la valeur par défaut
$selectedValue = isset($interDateBasValue) ? $interDateBasValue : $defaultValue_date_Bas;

echo '<div class="flex_opt_picto">
        <div class="picto_opt" style="background-image: url(images/opti_date_down.svg);"></div>
        <select name="interDateBas" id="interDateBas" class="agendaOpt selopt">';

// Si la valeur par défaut n'existe pas dans $interDateBas, ajoutez-la
if (!isset($interDateBas[$selectedValue])) {
    echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';
}

// Utiliser une boucle foreach pour afficher chaque option
foreach ($interDateBas as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $selectedValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}

echo '</select>';

//* Info bulle
echo '<div class="tooltip" >i
                        <div class="tooltiptext">
                            <div>Sert à régler l\'espacement entre le bas de la date en cours et le premier bloc texte de la date suivante</div>
                            <img class="tooltippict" src="images/note_2.jpg" alt="Description">
                        </div>
                    </div>';
//* FIN - Info bulle

echo '</div><hr>';

//* FIN espacements dates haut / bas

//* Espacements pays haut / bas
//* haut
// Définir la valeur par défaut
// Définir la valeur par défaut
$defaultValue_Days_Ht = 1;

// Vérifiez si $interPaysHautValue existe, sinon utiliser la valeur par défaut
$selectedValue = isset($interPaysHautValue) ? $interPaysHautValue : $defaultValue_Days_Ht;

echo '<div class="flex_opt_picto">
        <div class="picto_opt" style="background-image: url(images/opti_flag_up.svg);"></div>
        <select name="interPaysHaut" id="interPaysHaut" class="agendaOpt selopt">';

// Si la valeur par défaut n'existe pas dans $interPaysHaut, ajoutez-la
if (!isset($interPaysHaut[$selectedValue])) {
    echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';
}

// Utiliser une boucle foreach pour afficher chaque option
foreach ($interPaysHaut as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $selectedValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}

echo '</select>';

//* Info bulle
echo '<div class="tooltip" >i
                <div class="tooltiptext">
                    <div>Sert à répartir l\'espacement entre les blocs : drapeaux / nom du pays + évènement</div>
                    <img class="tooltippict" src="images/note_3.jpg" alt="Description">
                </div>
            </div>';
//* FIN - Info bulle

echo '</div>';

//* bas
// Définir la valeur par défaut
$defaultValue_Days_Bas = -5;

// Vérifiez si $interPaysBasValue existe, sinon utiliser la valeur par défaut
$selectedValue = isset($interPaysBasValue) ? $interPaysBasValue : $defaultValue_Days_Bas;

echo '<div class="flex_opt_picto">
        <div class="picto_opt" style="background-image: url(images/opti_flag_down.svg);"></div>
        <select name="interPaysBas" id="interPaysBas" class="agendaOpt selopt">';

// Si la valeur par défaut n'existe pas dans $interPaysBas, ajoutez-la
if (!isset($interPaysBas[$selectedValue])) {
    echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';
}

// Utiliser une boucle foreach pour afficher chaque option
foreach ($interPaysBas as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $selectedValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}

echo '</select>';

//* Info bulle
echo '<div class="tooltip" >i
                        <div class="tooltiptext">
                            <div>Sert à régler l\'espacement entre le bloc texte et le bloc pays (drapeau + nom)</div>
                            <img class="tooltippict" src="images/note_4.jpg" alt="Description">
                        </div>
                    </div>';
//* FIN - Info bulle

echo '</div><hr>';

//* FIN espacements pays haut / bas

//* Interlignage
// Définir la valeur par défaut
$defaultValue = 9.5;

// Utiliser la valeur par défaut si $interLigneValue n'est pas défini
$selectedValue = isset($interLigneValue) ? $interLigneValue : $defaultValue;

echo '<div class="flex_opt_picto">
        <div class="picto_opt" style="background-image: url(images/opti_interligne.svg);"></div>
        <select name="interligne" id="interligne" class="agendaOpt selopt">';

// Si la valeur par défaut n'existe pas dans $interLigne, ajoutez-la
if (!isset($interLigne[$selectedValue])) {
    echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';
}

// Utiliser une boucle foreach pour afficher chaque option
foreach ($interLigne as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $selectedValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}

echo '</select>';

//* Info bulle
echo '<div class="tooltip" >i
                        <div class="tooltiptext">
                            <div>Sert à ajuster l\'interlignage de tous les évènements</div>
                            <img class="tooltippict" src="images/note_5.jpg" alt="Description">
                        </div>
                    </div>';
//* FIN - Info bulle

echo '</div><hr>';

//* FIN interlignage

//* Ajustement colonnes
// Définir la valeur par défaut
// $defaultColonneValue = end($adjustColonne);
$defaultColonneValue = 2.1;
$adjustColonneValue = isset($adjustColonneValue) ? $adjustColonneValue : $defaultColonneValue;
// Définir la valeur par défaut
echo '<div class="flex_opt_picto">
                <div class="picto_opt" style="background-image: url(images/opti_colonnes.svg);"></div>
                <select name="adjustColonne" id="adjustColonne" class="agendaOpt selopt">';

// Vérifier si la valeur par défaut doit être ajoutée manuellement
if (!isset($adjustColonne[$defaultColonneValue])) {
    $selected = ($adjustColonneValue == $defaultColonneValue) ? ' selected' : '';
    echo '<option value="' . $defaultColonneValue . '"' . $selected . '>' . $defaultColonneValue . '</option>';
}

// Utiliser une boucle foreach pour afficher chaque option de $adjustColonne
foreach ($adjustColonne as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $adjustColonneValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}

echo '</select>';

//* Info bulle
echo '<div class="tooltip" >i
                        <div class="tooltiptext">
                            <div>Force à basculer des évènements d\'une colonne à l\'autre</div>
                            <img class="tooltippict" src="images/note_6.jpg" alt="Description">
                        </div>
                    </div>';
//* FIN - Info bulle

echo '</div>';

//* FIN Ajustement colonnes

echo '</div>';

//@ FIN gestion des select pour les paramètres 
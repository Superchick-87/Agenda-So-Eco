<?php
//@ gestion des select pour les paramètres 

echo '<div id="options" class="bloc_opt">';

//* espacements dates haut / bas
// Afficher les menus déroulants avec les options pré-sélectionnées
echo '<div class="flex_opt_picto">';
echo '<div class="picto_opt" style="background-image: url(images/opti_date_up.svg);"></div>';
echo '<select name="interDateHaut" id="interDateHaut" class="agendaOpt selopt">';
// Utiliser une boucle foreach pour afficher chaque option
foreach ($interDateHaut as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $interDateHautValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}
echo '</select>';

// Info bulle
echo '<div class="tooltip" >i
        <div class="tooltiptext">
            <div>Sert à régler l\'espacement entre le haut de la date en cours et le dernier bloc texte de la date précédente</div>
            <img class="tooltippict" src="images/note_1.jpg" alt="Description">
        </div>
    </div>';
// FIN - Info bulle

echo ' </div>';

echo '<div class="flex_opt_picto">';
echo '<div class="picto_opt" style="background-image: url(images/opti_date_down.svg);"></div>';
echo '<select name="interDateBas" id="interDateBas" class="agendaOpt selopt">';
// Utiliser une boucle foreach pour afficher chaque option
foreach ($interDateBas as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $interDateBasValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}
echo '</select>';

// Info bulle
echo '<div class="tooltip" >i
        <div class="tooltiptext">
            <div>Sert à régler l\'espacement entre le bas de la date en cours et le premier bloc texte de la date suivante</div>
            <img class="tooltippict" src="images/note_2.jpg" alt="Description">
        </div>
    </div>';
// FIN - Info bulle

echo '</div><hr>';

//* FIN espacements dates haut / bas

//* espacements pays haut / bas

// Afficher les menus déroulants avec les options pré-sélectionnées
echo '<div class="flex_opt_picto">';
echo '<div class="picto_opt" style="background-image: url(images/opti_flag_up.svg);"></div>';
echo '<select name="interPaysHaut" id="interPaysHaut" class="agendaOpt selopt">';
// Utiliser une boucle foreach pour afficher chaque option
foreach ($interPaysHaut as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $interPaysHautValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}
echo '</select>';

// Info bulle
echo '<div class="tooltip" >i
        <div class="tooltiptext">
            <div>Sert à répartir l\'espacement entre les blocs : drapeaux / nom du pays + évènement</div>
            <img class="tooltippict" src="images/note_3.jpg" alt="Description">
        </div>
    </div>';
// FIN - Info bulle

echo '</div>';

echo '<div class="flex_opt_picto">';
echo '<div class="picto_opt" style="background-image: url(images/opti_flag_down.svg);"></div>';
echo '<select name="interPaysBas" id="interPaysBas" class="agendaOpt selopt">';
// Utiliser une boucle foreach pour afficher chaque option
foreach ($interPaysBas as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $interPaysBasValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}
echo '</select>';

// Info bulle
echo '<div class="tooltip" >i
        <div class="tooltiptext">
            <div>Sert à régler l\'espacement entre le bloc texte et le bloc pays (drapeau + nom)</div>
            <img class="tooltippict" src="images/note_4.jpg" alt="Description">
        </div>
    </div>';
// FIN - Info bulle

echo '</div><hr>';

//* FIN espacements pays haut / bas

//* Interlignage
// Définir la valeur par défaut
$defaultValue = 9.5;
$interLigneValue = isset($interLigneValue) ? $interLigneValue : $defaultValue;
echo '<div class="flex_opt_picto">';
echo '<div class="picto_opt" style="background-image: url(images/opti_interligne.svg);"></div>';
echo '<select name="interligne" id="interligne" class="agendaOpt selopt">';
// Utiliser une boucle foreach pour afficher chaque option
foreach ($interLigne as $value => $label) {
    // Vérifier si la valeur doit être sélectionnée
    $selected = ($value == $interLigneValue) ? ' selected' : '';
    echo '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
}
echo '</select>';

// Info bulle
echo '<div class="tooltip" >i
        <div class="tooltiptext">
            <div>Sert à ajuster l\'interlignage de tous les évènements</div>
            <img class="tooltippict" src="images/note_5.jpg" alt="Description">
        </div>
    </div>';
// FIN - Info bulle

echo '</div><hr>';

//* FIN interlignage

//* Ajustement colonnes
// Définir la valeur par défaut
$defaultColonneValue = end($adjustColonne);
$adjustColonneValue = isset($adjustColonneValue) ? $adjustColonneValue : $defaultColonneValue;

echo '<div class="flex_opt_picto">';
echo '<div class="picto_opt" style="background-image: url(images/opti_colonnes.svg);"></div>';
echo '<select name="adjustColonne" id="adjustColonne" class="agendaOpt selopt">';

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

// Info bulle
echo '<div class="tooltip" >i
        <div class="tooltiptext">
            <div>Force à basculer des évènements d\'une colonne à l\'autre</div>
            <img class="tooltippict" src="images/note_6.jpg" alt="Description">
        </div>
    </div>';
// FIN - Info bulle

echo '</div>';


//* FIN Ajustement colonnes

echo '</div>';

    //@ FIN gestion des select pour les paramètres 
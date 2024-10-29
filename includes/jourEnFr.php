<?php
function afficherJourSuivant($date)
{
    // Créer un objet DateTime à partir de la date donnée
    $dateObjet = new DateTime($date);

    // Ajouter un jour à la date
    // $dateObjet->modify('+1 day');

    // Configurer le formatteur pour afficher le jour de la semaine en français
    $formatter = new IntlDateFormatter(
        'fr_FR', // Locale française
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        'EEEE' // Format pour obtenir uniquement le nom complet du jour
    );

    // Retourner le jour de la semaine au format 'lundi', 'mardi', etc. avec majuscule
    return ucfirst($formatter->format($dateObjet)); // Mettre en majuscule la première lettre
}

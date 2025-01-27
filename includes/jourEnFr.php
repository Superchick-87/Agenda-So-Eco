<?php
// function afficherJourSuivant($date)
// {
//     // Créer un objet DateTime à partir de la date donnée
//     $dateObjet = new DateTime($date);

//     // Ajouter un jour à la date
//     // $dateObjet->modify('+1 day');

//     // Configurer le formatteur pour afficher le jour de la semaine en français
//     $formatter = new IntlDateFormatter(
//         'fr_FR', // Locale française
//         IntlDateFormatter::FULL,
//         IntlDateFormatter::NONE,
//         'Europe/Paris',
//         IntlDateFormatter::GREGORIAN,
//         'EEEE' // Format pour obtenir uniquement le nom complet du jour
//     );

//     // Retourner le jour de la semaine au format 'lundi', 'mardi', etc. avec majuscule
//     return ucfirst($formatter->format($dateObjet)); // Mettre en majuscule la première lettre
// }

function afficherJourSuivant($date)
{
    // Créer un objet DateTime à partir de la date donnée
    $dateObjet = new DateTime($date);

    // Ajouter un jour à la date
    // $dateObjet->modify('+1 day');

    // Configurer le formatteur pour afficher la date complète en français
    $formatter = new IntlDateFormatter(
        'fr_FR', // Locale française
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        'EEEE d MMMM' // Format pour jour, jour numérique et mois complet
    );

    // Retourner la date formatée avec majuscule au début
    return ucfirst($formatter->format($dateObjet)); // Mettre en majuscule la première lettre
}

function coupeMois($tring)
{
    $tring = str_replace('septembre', 'sept.', $tring);
    return $tring;
}
// Exemple d'utilisation
// echo afficherJourSuivant('2025-01-07'); // Affiche "Mercredi 8 janvier"

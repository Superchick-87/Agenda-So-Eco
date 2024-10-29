<?php
function turn($tring)
{
    $tring = str_replace('*@*', '<br>', $tring);
    // $tring = str_replace(',,', '<br>', $tring);
    return $tring;
}
function turnFront($tring)
{
    // $tring = str_replace('<br>', '</br>', $tring); 
    $tring = str_replace('*@*', '', $tring);
    return $tring;
}
function exposant($tring)
{
    $tring = str_replace('1 re', '1<sup>re</sup>', $tring);
    $tring = str_replace('1 er', '1<sup>er</sup>', $tring);
    $tring = str_replace('1 e', '1<sup>e</sup>', $tring);
    $tring = str_replace('2 e', '2<sup>e</sup>', $tring);
    $tring = str_replace('3 e', '3<sup>e</sup>', $tring);
    $tring = str_replace('4 e', '4<sup>e</sup>', $tring);
    $tring = str_replace('5 e', '5<sup>e</sup>', $tring);
    $tring = str_replace('6 e', '6<sup>e</sup>', $tring);
    $tring = str_replace('7 e', '7<sup>e</sup>', $tring);
    $tring = str_replace('8 e', '8<sup>e</sup>', $tring);
    $tring = str_replace('9 e', '9<sup>e</sup>', $tring);
    $tring = str_replace('0 e', '0<sup>e</sup>', $tring);

    $tring = str_replace('1re', '1<sup>re</sup>', $tring);
    $tring = str_replace('1er', '1<sup>er</sup>', $tring);
    $tring = str_replace('1e', '1<sup>e</sup>', $tring);
    $tring = str_replace('2e', '2<sup>e</sup>', $tring);
    $tring = str_replace('3e', '3<sup>e</sup>', $tring);
    $tring = str_replace('4e', '4<sup>e</sup>', $tring);
    $tring = str_replace('5e', '5<sup>e</sup>', $tring);
    $tring = str_replace('6e', '6<sup>e</sup>', $tring);
    $tring = str_replace('7e', '7<sup>e</sup>', $tring);
    $tring = str_replace('8e', '8<sup>e</sup>', $tring);
    $tring = str_replace('9e', '9<sup>e</sup>', $tring);
    $tring = str_replace('0e', '0<sup>e</sup>', $tring);

    $tring = str_replace('<sup>e</sup>t', 'et', $tring);

    $tring = str_replace(' "', ' «', $tring);
    $tring = str_replace('", ', '», ', $tring);
    $tring = str_replace('" ', '» ', $tring);
    $tring = str_replace(' «', ' « ', $tring);
    $tring = str_replace('», ', ' », ', $tring);
    $tring = str_replace('» ', ' » ', $tring);
    return $tring;
}

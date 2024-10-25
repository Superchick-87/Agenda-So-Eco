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

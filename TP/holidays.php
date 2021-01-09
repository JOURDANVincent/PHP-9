<?php

function dimanche_paques($annee)
{
    return date("d-m-Y", easter_date($annee));
}
function vendredi_saint($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("d-m-Y", strtotime("$dimanche_paques -2 day"));
}
function lundi_paques($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("d-m-Y", strtotime("$dimanche_paques +1 day"));
}
function jeudi_ascension($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("d-m-Y", strtotime("$dimanche_paques +39 day"));
}
function lundi_pentecote($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("d-m-Y", strtotime("$dimanche_paques +50 day"));
}


function jours_feries($annee, $alsacemoselle=false)
{
    
    $jours_feries[dimanche_paques($annee)] = 'Dimanche de Pâques';
    $jours_feries[lundi_paques($annee)] = 'Lundi de Pâques';
    $jours_feries[jeudi_ascension($annee)] = 'Jeudi Ascension';
    $jours_feries[lundi_pentecote($annee)] = 'Lundi de Pentecôte';

    $jours_feries["01-01-$annee"] = 'Nouvel An' ;       //    Nouvel an
    $jours_feries["01-05-$annee"] = 'Fête du Travail';      //    Fête du travail
    $jours_feries["08-05-$annee"] = 'Armistice';       //    Armistice 1945
    $jours_feries["15-05-$annee"] = 'Assomption';       //    Assomption
    $jours_feries["14-07-$annee"] = 'Fête Nationale' ;       //    Fête nationale
    $jours_feries["11-11-$annee"] = 'Armistice' ;       //    Armistice 1918
    $jours_feries["01-11-$annee"] = 'Toussaint' ;       //    Toussaint
    $jours_feries["25-12-$annee"] = 'Noël' ;       //    Noël
    
    if($alsacemoselle)
    {
        $jours_feries["$annee-12-26"] = "férié";
        $jours_feries[vendredi_saint($annee)] = 'Vendredi Saint';
    }
    //sort($jours_feries);
    return $jours_feries;
}
function est_ferie($jour, $alsacemoselle=false)
{
    $jour = date("d-m-Y", strtotime($jour));
    $annee = substr($jour, 0, 4);
    return in_array($jour, jours_feries($annee, $alsacemoselle));
}


?>
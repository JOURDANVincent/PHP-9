<?php
    session_start();

    // déclaration des tableaux mois et semaine
    $arrayWeek = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    $arrayMonth = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $newEvent = $newEventDay = null;


    // date courante
    $currentYear = date('Y');
    $currentMonth = date('m');


    // récupération des jours fériés français
    include("holidays.php");

    if (isset($year)) {
        $holidayDetect = jours_feries($year);
    } else {
        $holidayDetect = jours_feries($currentYear);
    }

    //var_dump($holidayDetect);

    // objet événement
    $objHoliday = new stdClass();
    $objHoliday->label = 'objHoliday';
    $objHoliday->color = 'bgHoliday';
    $objHoliday->array = $holidayDetect;

    // initialisation des tableaux $calendar / $birthday / $event
    if (!isset($_SESSION['calendar'])) {

        // anniversaire des Manusiens
        $birthday = array(
            '20-6-2020' => 'Kevin',
            '21-8-2019' => 'Florian M.',
            '24-4-2018' => 'Timothy',
            '26-11-2017' => 'Jérôme',
            '29-4-2016' => 'Lucas',
            '2-9-2015' => 'Julien',
            '12-11-1983' => 'Bixente',
            '4-7-2013' => 'Florian L.',
            '12-3-2012' => 'Laurent',
            '6-11-2011' => 'Stéphane'
        );

        // objet anniversaire
        $objBirthday = new stdClass();
        $objBirthday->label = 'objBirthday';
        $objBirthday->color = 'bgBirthday';
        $objBirthday->array = $birthday;

        // tableau événement
        $event = [];
        // objet événement
        $objEvent = new stdClass();
        $objEvent->label = 'objEvent';
        $objEvent->color = 'bgEvent';
        $objEvent->array = $event;

        // tableau rappel
        $recall = [];
        // objet rappel
        $objRecall = new stdClass();
        $objRecall->label = 'objRecall';
        $objRecall->color = 'bgRecall';
        $objRecall->array = $recall;

        // tableau autres
        $other = [];
        // objet autres
        $objOther = new stdClass();
        $objOther->label = 'objOther';
        $objOther->color = 'bgOther';
        $objOther->array = $other;

        // ---------------------------------- construction du tableau calendrier --------------------------------------//
        $calendar['birthday'] = $objBirthday;
        $calendar['event'] = $objEvent;
        $calendar['recall'] = $objRecall;
        $calendar['other'] = $objOther;
        $calendar['holiday'] = $objHoliday;

    } else { // sinon on recupère le calendar
        $calendar = $_SESSION['calendar'];
    }


    // ------------------------------------------ initialisation des variables -----------------------------------------//

    $month = (!empty($_SESSION['month'])) ? $_SESSION['month'] : null;
    $year = (!empty($_SESSION['year'])) ? $_SESSION['year'] : null;


    // Bouton retour home et reset variables ------------------------------------------------------------------------//
    if (!empty($_GET['home'])) { 

        $_GET = $_SESSION = $_COOKIE = null;
        $month = $year = null;
        session_destroy();
    }


    // affichage mois précedent et suivant ----------------------------------------------------------------------------------------------------------//
    if (!empty($_GET['newMonth'])) {

        // init variable $newMonth
        $newMonth = $_GET['newMonth'];

        // calcul des sauts d'année
        $month = intval($month + $newMonth);
        if ($month == 13) {
            $month = 1;
            $year += 1;
        } else if ($month == 0) {
            $month = 12;
            $year -= 1;
        } else {
            // $month = $newMonth;
        }

        // suppression de l'élément newMonth dans tableau $_GET
        $_GET = $newMonth = null;

    }


    // récupère mois / année selectionnée dans input page d'accueil
    if (!empty($_GET['year']) AND !empty($_GET['month'])) {

        // enregistrement des données dans $_GET et $SESSION
        $year = intval($_GET['year']);
        $month = intval($_GET['month']); 

        // destroy $_GET
        $_GET = null;
    }
    

    // récupère données du nouvel événement
    if(!empty($_GET['newEventDescription']) OR !empty($_GET['newEventDay']) OR !empty($_GET['newEventType'])) { 

        $newEventType = $_GET['newEventType'];
        $newEventDescription = $_GET['newEventDescription'];
        $newEventDay = $_GET['newEventDay'];
        
        if ($newEventType == 'event') {

            $calendar['event']->array[$newEventDay .'-'. $month .'-'. $year] = $newEventDescription;

        } else if ($newEventType == 'birthday') {

            $calendar['birthday']->array[$newEventDay .'-'. $month .'-'. $year] = $newEventDescription;

        } else if ($newEventType == 'recall') {

            $calendar['recall']->array[$newEventDay .'-'. $month .'-'. $year] = $newEventDescription;

        } else if ($newEventType == 'other') {

            $calendar['other']->array[$newEventDay .'-'. $month .'-'. $year] = $newEventDescription;
        } 

        // sauvegarde du nouvel événement dans session
        $_SESSION['calendar'] = $calendar;
        
        // destroy data
        $_GET = null;
    }


    // ----------------------------------initialisation de l'affichage --------------------------------------------- //
    $layout = (!empty($month) OR !empty($year)) ? 'show' : 'hide';


    if ($layout == 'show') {

        // Calul des données nécessaires au calendrier
        $timestamp = mktime(0, 0, 0, $month, 1, $year); //Donne le timestamp correspondant à cette date
        $firstday =  intval(date('N', $timestamp)); //Extrait le jour en chiffre de 1 à 7
        $totalday = intval(date('t', $timestamp)); //Extrait le nombre total de jours du mois sélectionné
        $timestamp = mktime(0, 0, 0, $month, $totalday, $year); //Donne le timestamp correspondant à cette date
        $lastday = intval(date('N', $timestamp)); //Extrait le jour en chiffre de 0 à 6


        // --------------------- recherche événement dans calendrier -----------------------------//

        foreach($calendar as $type => $value) {

            foreach($calendar[$type]->array as $key => $value) {  // recherche dans tableau birthday
            
                $searchInCalendar = explode('-', $key);
    
                if ($month == $searchInCalendar[1] AND ($year == $searchInCalendar[2] OR $type == 'birthday')) {
    
                    $dataBackground[$searchInCalendar[0]][0] = $calendar[$type]->color;
                    $dataBackground[$searchInCalendar[0]][1] = $type;
                    $dataBackground[$searchInCalendar[0]][2] = $value;
                    //var_dump($dataBackground);
                } 
            }

        }

                
        // déclaration du tableau gestion calendrier
        $array = [];

        for($a = 0; $a < ($firstday - 1); $a++) {
            array_push($array, null);
        }     

        for ($a = 0; $a < $totalday; $a++) {

            array_push($array, $a + 1);
        }

        for ($a = ($lastday - 1); $a < 6; $a++ ) {
            array_push($array, null);
        }

        // nombre de ligne tableau
        $line = count($array) / 7;

        // division du tableau pour obtenir un sous-tableau par semaine
        $arraychunk = array_chunk($array, 7, false);
        //var_dump($arraychunk);

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">

    <title>Le calendrier des MANUSIENS</title>
</head>

<body>

    <div class="container">
        <div class="row align-items-center justify-content-center">
            
            <?php
                if ($layout == 'hide') { // si != afficher l'énoncé exercice
            ?>
            <div class="col-12">
                <h1 class="mb-4 mt-5">Le calendrier des MANUSIENS</h1>
                <h2>Exercice TP :</h2>
                <p>
                    Faire un formulaire avec deux listes déroulantes. La première sert à choisir le mois, et le deuxième permet d'avoir l'année.  
                    En fonction des choix, afficher un calendrier comme celui-ci : 

                    http://icalendrier.fr/media/imprimer/2017/mensuel/calendrier-2017-mensuel-bigthumb.png
                </p>
            </div>

            <div class="col-6 mt-5">

                <form action="index.php" method="get">

                    <label for="monthSelect">Sélectionnes un mois..</label>
                    <select class="form-control selectNewDate mb-3" name="month" id="monthSelect">
                        <?php
                            foreach($arrayMonth as $key => $value) {
                                echo '<option value="' . ($key + 1) . '" '.(($currentMonth == $key + 1) ? 'selected' : '') .'>' . $value . '</option>';
                            }
                        ?>
                    </select>

                    <label for="yearSelect">Entres une année..</label>
                    <select class="form-control selectNewDate mb-3" name="year" id="yearSelect">
                        <?php
                            for($a = 1970; $a <= 2030; $a++) {
                                echo '<option value="' . $a . '"'.(($currentYear == $a) ? 'selected' : '') .'> année ' . $a . '</option>';
                            }
                        ?>
                    </select>

                    <div class="text-center">
                        <input type="submit" class="btn px-5" value="OPEN CALENDAR">
                    </div>

                </form>
                
            </div>

            <?php

                } 

                if ($layout == 'show') {

            ?>

            <div class="col-2 align-items-center">
                <a href="index.php?home=reset">
                    <img id="home" title="RESET du calendrier" class="img-fluid" src="assets/img/svg/home.svg" alt="bouton retour page d'accueil">
                </a>
            </div>

            <div class="col-8 text-center mb-4 mt-4">
                <!-- <h1>Le calendrier des MANUSIENS</h1> -->
                <h1><a href="https://lamanu.fr/" target="_blank" ><img id="laManu" class="img-fluid" src="assets/img/laManu.png" alt="logo la MANU"></a></h1>
            </div>

            <!-- affichage des infos de développement -->
            <div class="col-10 d-none">
                <h3 class="mt-3 pl-2"><a class="text-white">Informations</a></h3>
                <div class="pl-4">
                    <!-- affichage du jour de la semaine -->
                    <div><?= 'Le 01/'.$month.'/'.$year. ' était un ' .$arrayWeek[$firstday - 1]; ?></div>
                    <!-- affichage du jour de la semaine -->
                    <div><?= 'Le dernier jour était un ' .$arrayWeek[$lastday - 1]; ?></div>
                    <!-- affichage du nombre de jour -->
                    <div><?= 'Au mois de ' . $arrayMonth[$month -1] . ', il y avait ' .$totalday; ?></div>
                    <!-- nombre de ligne tableau -->
                    <div><?= 'nombre de ligne tableau : ' . $line; ?></div>
                    <!-- nouvel évènement -->
                    <div><?= 'nouvel évènement : ' . $newEvent; ?></div>
                </div>
            </div>

            <div class="col-2">
            </div>

            <?php
                }
            ?>
                
        </div> <!-- fin div row -->

    </div> <!-- fin div container -->

    <?php
        if ($layout == 'show') {
    ?>

    <div class="container text-center mt-3">
        
        <table class="table table-dark">

            <div class="d-flex justify-content-end align-items-center">
                <div class="col-6 d-flex pl-0">
                    <form class="justify-content-between" action="#" method='get'>
                        
                        <select class="form-control selectNewDate mr-2 mb-2" name="month" id="monthSelect">
                            <?php
                                foreach($arrayMonth as $key => $value) {
                                    echo '<option value="' . ($key + 1) . '" '.(($month == $key + 1) ? 'selected' : '') .' >' . $value . '</option>';
                                }
                            ?>
                        </select>
                        
                        <select class="form-control selectNewDate mr-2" name="year" id="yearSelect">
                            <?php
                                for($a = 1970; $a <= 2030; $a++) {
                                    echo '<option value="' . $a . '" '.(($year == $a) ? 'selected' : '') .'> année ' . $a . '</option>';
                                }
                            ?>
                        </select>

                        <input type="submit" class="form-control btn btn-dark" value="OPEN CALENDAR">

                    </form>
                </div>
                <h4 class="calendarTitle col ml-3 mb-3 text-center"><?php echo $arrayMonth[$month - 1] . ' ' . $year; ?></h4>
                <div class="col-1 d-flex pr-0 justify-content-end">
                    <a href="index.php?newMonth=-1" title="mois précédent"><span class="prev-m mr-3 py-2 px-2">&#x25B2;</span></a>
                    <a href="index.php?newMonth=+1" title="mois suivant"><span class="next-m py-2 px-2">&#x25BC;</span></a>
                </div>
            </div>
            
            <thead>
                <tr class="bg2">
                    <?php
                        foreach($arrayWeek as $value) {
                            echo '<th class="txt2">' . $value . '</th>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php // remplissage dynamique du calendrier Bootstrap

                    foreach($arraychunk as $modulo7 => $week) { // boucle ligne calendrier

                        echo '<tr>'; // balise ouverture tr
                        
                        foreach($arraychunk[$modulo7] as $day => $data) { // boucle cellule calendrier  data-toggle="modal" data-target="#eventModal"

                            if ($data == null) {
                                echo '<td class="bg3"></td>';

                            } else {

                                // contrôle du type de données ...
                                if (isset($dataBackground[$data][0]) ) {
                                    $class = $dataBackground[$data][0];
                                    $type = $dataBackground[$data][1];
                                    $name = $dataBackground[$data][2];

                                    if ($type == 'birthday') {
                                        echo '<td class="date '.$class.'" data-type="'.$type.'" data-name="'.$name.'" title="'.$data.'">'.$name.'</td>';

                                    } else if ($type == 'holiday') {
                                        echo '<td class="date '.$class.'" data-type="'.$type.'" data-name="'.$name.'" title="'.$data.'">'.$name.'</td>';

                                    } else {
                                        echo '<td class="date '.$class.'" data-type="'.$type.'" data-name="'.$name.'" title="'.$data.'">'.$name.'</td>'; 
                                    }

                                } else if ($day >= 5) {
                                    echo '<td class="date bgWE" >'.$data.'</td>';

                                } else {
                                    echo '<td class="date" >'.$data.'</td>';
                                }
                            }
                            
                        } // <a href="#" stretched-link data-toggle="modal" data-target="#eventModal" </a>
                        
                        echo '</tr>'; // balise fermeture tr
                    }
                ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-12 text-left mb-5">
                <h5 class="dateOfDay pl-2 mt-2 mb-3">aucune date sélectionnée..</h5>

                <form class="bgEventForm bdc py-3 px-5" action="index.php" post="get">
                    <div class="eventSelected pl-2 mb-3 txt2">aucun événement pour le moment..</div>
                    <input class="hiddenEvent form-control mb-2" type="hidden" name="newEventDay" value="">
                    <div class="d-flex">
                        <select class="selectEvent form-control mb-2 mr-2" name="newEventType" id="" value="">
                            <option class="bgOption" value="birthday">Anniversaire</option>
                            <option class="bgOption" value="event">Evénement</option>
                            <option class="bgOption" value="recall">Rappel</option>
                            <option class="bgOption" value="other">Autre</option>
                        </select>
                        <input class="inputEvent form-control mb-2" type="text" name="newEventDescription" value="" placeholder="Description du nouvel événement / Nom" required>  
                    </div>
                    <input class="addEvent" type="submit" value="+ ajouter événement">
                </form>
            </div>
        </div>
            
    </div>

    <?php
        }
    ?>


    <!--------------------------------------------- Modal des événements ------------------------------------------------->
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-6">
                <div class="modal fade" id="eventModal" aria-labelledby="=eventModalLabel" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content bgModal">
                            <div class="modal-header">
                                <h5 class="modal-title">jour</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="text-white" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="index.php" method="get">
                                    <p>Actuellement aucun événement sauvegardé..</p>
                                    <input class="hiddenEventModal form-control" type="hidden" name="newEventDate" value="">
                                    <input type="text" class="form-control inputEvent" name="newEventDescription" placeholder="Nouvel événement">
                                    <input type="submit" class="addEventModal" value="+ ajouter événement"/>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn bg6 text-white" data-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    
    <!-- script js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js " integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj " crossorigin="anonymous ">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js " integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN " crossorigin="anonymous ">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js " integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s " crossorigin="anonymous ">
    </script>

    <script src="assets/js/script.js"></script>

</body>

</html>
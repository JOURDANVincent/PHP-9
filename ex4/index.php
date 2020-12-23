<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   
    <title>Partie 9 - ex4</title>
</head>

<body>

    <h1>Partie 9 - ex4</h1>

    <p>
        Afficher le timestamp du jour.  
        Afficher le timestamp du mardi 2 août 2016 à 15h00.
    </p>

    <?php

        // paramétrage heure locale francaise
        setlocale(LC_TIME, 'fra_fra.UTF8' );

        // afficher le timestamp du jour : nombre de secondes écoulées depuis le 1er janvier 1970 à minuit
        echo 'Timestamp actuel : ' .time(). ' secondes <br><br>';

        // afficher le timestamp du jour
        $t1 = mktime(15, 0, 0, 8, 2, 2016);
        echo 'Timestamp mardi 2 août 2016 : ' .$t1. ' secondes <br><br>';

        // timestamp GMT
        $gmt1 = gmmktime(15, 0, 0, 8, 2, 2016);
        echo 'Timestamp GMT mardi 2 août 2016 : ' .$gmt1. ' secondes <br><br>';
       
    ?>

    <!-- script js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js " integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj " crossorigin="anonymous ">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js " integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN " crossorigin="anonymous ">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js " integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s " crossorigin="anonymous ">
    </script>

</body>

</html>
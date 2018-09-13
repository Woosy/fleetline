<!DOCTYPE html>
<html lang="fr" dir="ltr">
<link rel="stylesheet" type="text/css" href="assets/style.css"/>
<?php

/** ==========================================
* > Chargement des informations de la session
* > Include du header
*/

include("includes/header.php");

?>


<!-- Bouton retour fil d'actualité -->
<a href="home.php" class="home-retour loader-on"><i class="glyphicon glyphicon-home"></i></a>
<!-- Bouton overlay -->
<span class="overlay-btn" onclick="openNav()"><i class="glyphicon glyphicon-chevron-right"></i></span>

<body>


    <!-- Loader sur toute la page -->
    <div class="loader-fleetline">
        <img src="assets/images/animation-loader.gif" alt="loader">
    </div>


    <!-- Corp de la description -->
    <div class='post-description'>
    </div>


    <?php include("includes/overlay.php");



    $sql = $bdd -> prepare("SELECT * FROM participants WHERE utilisateur = ? AND post = ?");
    $sql -> execute(array($_COOKIE['mail'], $_GET['id']));
    $result = $sql -> rowCount();

    if ($result == 0) {
        echo "<input class='post-description-submit' type='submit' value='Je participe'>";
    }

    ?>


    <div class="post-participants">
        <h1>Participants</h1>
        <div class="post-participants-2">*
        </div>
    </div>

    <div class="post-image">
        <h1>Photos</h1>
        <div class="post-image-2">
        </div>
    </div>


</body>


<script type="text/javascript">


$(window).on('load', function () {

    function loadParticipants() {
        $.ajax({
            url:"includes/functions.php",
            type: "POST",
            data:{
                "action": "ajax_load_participants",
                "event_id": getParameterByName('id')
            },
            success:function(data) {
                // Afficher toutes les données de ma requête
                if (data == "erreur") {
                    errorAnimation("Une erreur est survenue");
                } else {
                    $('.post-participants-2').html(data);
                }
            }
        });
    }



    loadParticipants();

    // Ajoute l'utilisateur à la liste des participants
    $(".post-description-submit").click(function() {

        $.ajax({
            url:"includes/functions.php",
            type: "POST",
            data:{
                "action": "ajax_je_participe",
                "event_id": getParameterByName('id')
            },
            success:function(data) {
                // Afficher toutes les données de ma requête
                if (data == "erreur") {
                    errorAnimation("Une erreur est survenue");
                } else {
                    successAnimation("C'est noté !");
                    setTimeout(function() {
                        loadParticipants();
                        $(".post-description-submit").slideUp(1200);
                    }, 1200);
                }
            }
        });

    });


    // Récupération informations de l'événement
    $.ajax({
        url:"includes/functions.php",
        type: "POST",
        data:{
            "action": "ajax_event_page",
            "event_id": getParameterByName('id')
        },
        success:function(data) {
            // Afficher toutes les données de ma requête
            if (data == "erreur") {
                errorAnimation("Une erreur est survenue");
            } else if (data == "redirect") {
                window.location.replace("home.php");
            } else {
                $(".post-description").html(data);
            }
        }
    });


});


</script>


</html>

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

        <p class='post-description-titre'>Sortie sushi !</p>

        <div class='post-description-date'>
            <p class='post-description-date-j'>Samedi 13/09</p>
            <p class='post-description-date-h'>20:00</p>
        </div>

        <div class='post-description-description'>
            <p class='post-description-description-lieu'>Lyon</p>
            <p class='post-description-description-texte'>
                Erar iam nav em omni bus que arma me ntis instr ucta m mari com mittat.
            </p>
        </div>

    </div>


    <input class="post-description-submit"type="submit" name="" value="Je participe">

    <div class="post-participants">
        <h1>Participants</h1>
        <div class="post-participants-2">
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
            <div class="post-participants-test"></div>
        </div>
    </div>

    <div class="post-image">
        <h1>Photos</h1>
        <div class="post-image-2">
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
            <div class="post-image-test"><img src="assets/images/pdp_defaut_femme.png"/></div>
        </div>
    </div>






    <!-- Overlay -->
    <?php include("includes/overlay.php") ?>


</body>


<script type="text/javascript">


$(window).on('load', function () {

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
            } else {
                $(".post-description").html(data);
            }
        }
    });

});


</script>


</html>

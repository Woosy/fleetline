<!DOCTYPE html>
<html lang="fr" dir="ltr">
<!-- Bing Map API -->
<script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=ArQAdAFJEteYuCFdxZp-uqW9c1MdsgvjYodKNscL2rpgc8x2iEwivXh1e8x_iYr4&callback=loadMapScenario' async defer></script>
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


    <!-- Corps du formulaire -->
    <div class="newpost-page">

        <div class="newpost-titre">
            <p>Création de l'événement</p>
        </div>

        <div class="form-group newpost-form">

            <input class="form-control" type="text" value="" placeholder="Titre" name="titre">

            <input class="form-control" style="width: 48%; float: left;" type="date" name="date">
            <input class="form-control" style="width: 48%; float: right;" type="time" name="heure">

            <div id='searchBoxContainer'>
                <input class="form-control" placeholder="Lieu" type='text' id='searchBox' name="lieu"/>
            </div>
            <div id='myMap' style='display: none'></div>

            <textarea class="form-control" type="text" placeholder="Descriptif" name="description"></textarea>

            <input class="form-control action-post-new-event" type="submit" value="Lancement">

        </div>

    </div>






    <!-- Overlay -->
    <?php include("includes/overlay.php") ?>


</body>



<script type="text/javascript">

/**
* Envoie du nouveau post par ajax
*/
$(".action-post-new-event").click(function() {

    // =====================================
    // Récupération des données :
    // =====================================
    var erreur = "";

    var titre = $.trim($('input[name=titre]').val());
    var date = $.trim($('input[name=date]').val());
    var heure = $.trim($('input[name=heure]').val());
    var lieu = $.trim($('input[name=lieu]').val());
    var description = $.trim($('textarea[name=description]').val());


    // =====================================
    // Vérification des données récupérées :
    // =====================================

    // On vérifie que les champs ne soient pas vides
    if (titre != "" && date != "" && heure != "" && lieu != "" && description != "") {

        // On vérifie qu'ils ne dépassent pas la taille max possible dans la bdd
        if (titre.length <= 128 && heure.length <= 16 && lieu.length <= 128 && description.length <= 2500) {


            // Envoie des données :
            $.ajax({
                url:"includes/functions.php",
                type: "POST",
                data:{
                    "action": "ajax_new_event",
                    "titre": titre,
                    "date": date,
                    "heure": heure,
                    "lieu": lieu,
                    "description": description
                },
                success:function(data) {
                    if (data == "true") {
                        $(".action-post-new-event").attr('disabled', true);
                        successAnimation("Événement ajouté !");
                        setTimeout(function(){
                            window.location.replace("home.php");
                        }, 2500);
                    } else {
                        errorAnimation("Une erreur est survenue");
                    }
                }
            });


        } else {
            erreur = "Des champs sont trop longs";
        }

    } else {
        erreur = "Champs incomplets";
    }

    // Si une erreur est survenue : on affiche
    if (erreur) {
        console.log("Erreur : " + erreur);
        errorAnimation(erreur);
    }

});

</script>


</html>

<!DOCTYPE html>
<html lang="fr" dir="ltr">


<?php

/** ==========================================
* > Chargement des informations de la session
* > Include du header
*/

include("includes/head.php");

?>


<!-- Ajout croppie -->
<link rel="stylesheet" href="assets/librairies/croppie.css" />
<script src="assets/librairies/croppie.js"></script>


<!-- Bouton retour fil d'actualité -->
<a href="home.php" class="home-retour loader-on"><i class="glyphicon glyphicon-home"></i></a>


<body>

    <div class="home-fil-profil">
    </div>

    <!-- Loader sur toute la page -->
    <div class="loader-fleetline">
        <img src="assets/images/animation-loader.gif" alt="loader">
    </div>


    <?php

    include('includes/database.php');

    $sql = $bdd -> prepare("SELECT pdp FROM utilisateurs WHERE mail = ?");
    $sql -> execute(array($_COOKIE['mail']));

    $results = $sql -> fetch();

    ?>


    <!-- Header avec lien vers la page d'accueil -->
    <div class="profil-header">
        <a href="home.php" class="loader-on"><img class="profil-header-logo" src="assets/images/logo.png" alt="Logo"></a>
        <a href="disconnect.php" class="loader-on"><span class="glyphicon glyphicon-log-out profil-logout" aria-hidden="true"></span></a>
    </div>


    <!-- Container -->
    <div class="profil-page-container">

        <!-- Pdp -->
        <div class='profil-pdp'>
            <label for='file' class='label-file'><img class='profil-pdp-image' src='<?php echo $results['pdp'] ?>' alt='Logo'></label>
            <input id='file' type='file' style='display: none;'>
        </div>


        <!-- Informations de l'utilisateur -->
        <div class='profil-user'>
            <div class='profil-user-nom'><?php echo $_COOKIE['prenom'].' '.$_COOKIE['nom']; ?></div>
            <div class='profil-user-mail'><?php echo $_COOKIE['mail']; ?></div>
        </div>


        <button type='button' name='button' class='btn profil-btn-amis'>Ajouter aux amis</button>


        <!-- Liste des événements de l'utilisateur-->
        <div class='profil-evenements'>
            <div class='profil-evenements-title'>Mes événements</div>
        </div>



        <div class='profil-liste-posts'>
        </div>

    </div>



</body>



<script>

$(window).on('load', function () {


    // Redirection profil utilisateur
    $(".post-auteur-pdp").click(function () {
        console.log("click pdp");
        var url = $(".post-auteur-pdp").getAttribute("href");
        window.location.replace(url);
    });


    // Récupération des posts
    $.ajax({
        url:"includes/functions.php",
        type: "POST",
        data:{
            "action": "ajax_profil_posts"
        },
        success:function(data) {
            // Afficher toutes les données de ma requête
            $(".profil-liste-posts").html(data);
        }
    });



    // Récupération informations de l'utilisateur
    $.ajax({
        url:"includes/functions.php",
        type: "POST",
        data:{
            "action": "ajax_profil_page",
            "utilisateur_id": getParameterByName('id')
        },
        success:function(data) {
            // Afficher toutes les données de ma requête
            if (data == "erreur") {
                errorAnimation("Une erreur est survenue");
            } else if (data == "redirect") {
                window.location.replace("home.php");
            } else {
                $(".profil-page-container").html(data);
            }
        }
    });


});

</script>



</html>

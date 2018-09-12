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



    <!-- Header avec lien vers la page d'accueil -->
    <div class="profil-header">
        <a href="home.php" class="loader-on"><img class="profil-header-logo" src="assets/images/logo.png" alt="Logo"></a>
        <a href="disconnect.php" class="loader-on"><span class="glyphicon glyphicon-log-out profil-logout" aria-hidden="true"></span></a>
    </div>


    <!-- Pdp -->
    <div class="profil-pdp">
        <label for="file" class="label-file"><img class="profil-pdp-image" src="assets/images/pdp_defaut_homme.png" alt="Logo"></label>
        <input id="file" type="file" style="display: none;">
    </div>


    <!-- Informations de l'utilisateur -->
    <div class="profil-user">
        <div class="profil-user-nom"><?php echo $_COOKIE['prenom']." ".$_COOKIE['nom']; ?></div>
        <div class="profil-user-mail"><?php echo $_COOKIE['mail']; ?></div>
    </div>


    <button type="button" name="button" class="btn profil-btn-amis">Ajouter aux amis</button>


    <!-- Liste des événements de l'utilisateur-->
    <div class="profil-evenements">
        <div class="profil-evenements-title">Mes événements</div>
    </div>





    <!-- Affichage des posts -->
    <div href="evenement.php" class="home-post loader-on">
        <img class="post-auteur-pdp" src="assets/images/pdp_defaut_homme.png" alt="PDP">
        <p class="post-auteur-nom">Arthur D.</p>
        <p class="post-title">Ceci est un titre</p>
        <p class="post-desc">Lorem ipcididunt ut labore et dolore magna</p>
        <div class="post-autres">
            <p class="post-ville">Villeurbanne</p>
            <p class="post-date">Samedi 13/09</p>
            <p class="post-heure">13:00</p>
            <p class="post-likes">❤ 13</p>
        </div>
    </div>

    <div href="evenement.php" class="home-post loader-on">
        <img class="post-auteur-pdp" src="assets/images/pdp_defaut_homme.png" alt="PDP">
        <p class="post-auteur-nom">Arthur D.</p>
        <p class="post-title">Ceci est un titre</p>
        <p class="post-desc">Lorem ipcididunt ut labore et dolore magna</p>
        <div class="post-autres">
            <p class="post-ville">Villeurbanne</p>
            <p class="post-date">Samedi 13/09</p>
            <p class="post-heure">13:00</p>
            <p class="post-likes">❤ 13</p>
        </div>
    </div>






    <!-- Modal pour le crop avec croppie  -->
    <div id="modalCroppie" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modification photo de profil</h4>
                </div>


                <div class="modal-body">
                    <div style="margin: 0px auto; width: auto">
                        <div id="image_to_crop"></div>
                    </div>
                    <div style="margin-left: 37%; margin-bottom: 35px;">
                        <button class="btn btn-success crop_image">Enregistrer la photo</button>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>

            </div>

        </div>
    </div>


</body>



<script>

$('#file').on('change', function(){

    var reader = new FileReader();
    reader.onload = function (event) {
        $image_crop.croppie('bind', {
            url: event.target.result
        });
    }
    reader.readAsDataURL(this.files[0]);
    $('#modalCroppie').modal('show');

});



$image_crop = $('#image_to_crop').croppie({
    viewport: {
        width:200,
        height:200,
        type:'circle'
    },
    boundary:{
        width: 300,
        height: 300
    }
});



$('.crop_image').click(function(event){

    $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function(response){
        $('#uploadimageModal').modal('hide');
        $.ajax({
            url:"uneurl/upload.php",
            type: "POST",
            data:{
                "newImage": response
            },

            success:function(data) {

            }
        });
    })
});
</script>



</html>

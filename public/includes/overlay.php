<!-- Overlay pour la recherche -->
<div id="myNav" class="overlay">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <div class="overlay-content" style="display: none;">

        <!-- Recherche dans le site -->
        <p class="overlay-recherche-label">Recherchez sur le site</p>
        <div class="input-group overlay-recherche">
            <input id="input-recherche" style="border-top-left-radius: 20px; border-bottom-left-radius: 20px;" class="form-control" type="text" placeholder="Evénement, utilisateur, lieu...">
            <div class="input-group-btn">
                <button style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;" class="btn btn-default" type="submit">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
            </div>
        </div>

        <!-- Différents tags -->
        <div class="overlay-tags">
            <span class="label label-info">Soirée</span>
            <span class="label label-info">Cinéma</span>
            <span class="label label-info">Foot</span>
            <span class="label label-info">Restaurant</span>
            <span class="label label-info">Piscine</span>
        </div>

    </div>
</div>




<!-- Overlay success -->
<div id="overlay_success">

    <div class="overlay-success-content">
        <h2 id="success_msg">Succès</h2>
    </div>

</div>


<!-- Overlay error -->
<div id="overlay_error">

    <div class="overlay-error-content">
        <h2 id="error_msg">Une erreur est survenue</h2>
    </div>

</div>






<style type="text/css">
#overlay_success {
    z-index: 1001;
    position: absolute;
    height: auto;
    width: 100%;
    padding: 8px;
    margin-top: -15px;
    display: none;
    background-color: #6ab04c;
}
.overlay-success-content {
    position: relative;
    width: 100%;
    height: auto;
    text-align: center;
}
#success_msg{
    margin: 15px;
    color: #fff;
    display: none;
    font-size: 20px;
    font-family: Montserrat, sans-serif;
}



#overlay_error {
    z-index: 1001;
    position: absolute;
    height: auto;
    width: 100%;
    padding: 8px;
    margin-top: -15px;
    display: none;
    background-color: #EA2027;
}
.overlay-error-content {
    position: relative;
    width: 100%;
    height: auto;
    text-align: center;
}
#error_msg{
    margin: 15px;
    color: #fff;
    display: none;
    font-size: 20px;
    font-family: Montserrat, sans-serif;
}
</style>





<script type="text/javascript">


    $("#input-recherche").bind("input", function() {

        var recherche = $("#input-recherche").val();

        if (recherche.length > 2) {


            $.ajax({
                url:"includes/functions.php",
                type: "POST",
                data:{
                    "action": "ajax_search_autocomplete",
                    "recherche": recherche
                },
                success:function(data) {
                    console.log("res: " + data);
                }
            });


        }
    });


</script>

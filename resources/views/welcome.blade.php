

@extends('layouts.app')

@section('content')

<body class="index" style="background-image: url('assets/images/animation-accueil.gif')">


    <!-- Loader sur toute la page -->
    <div class="loader-fleetline">
        <img src="assets/images/animation-loader.gif" alt="loader">
    </div>


    <!-- Logo -->
    <div class="main">
        <img class="main-logo" src="assets/images/logo-slogan.png" alt="Logo">
    </div>


    <!-- Formulaire de connexion -->
    <div class="form-group main-login">

        <button id="connect_button" type="button" name="button" onclick="location.href='/oauth.php'" class="btn btn-default main-login-button loader-on">Connexion</button>

    </div>



    <style>

    /* =====================================
    * == 2. Page d'accueil
    * ================================== */

    /** (2.1) Logo & desc **/

    .main {
        /* Élément centré */
        text-align: center;
        margin-top: 36vh;
        transform: translateY(-36%);
    }
    .main-logo {
        height: 75%;
        width: 75%;
    }

    /** (2.2) Formulaire login **/

    .main-login {
        margin-left: auto;
        margin-right: auto;
        width: 80%;
        height: auto;
    }
    .main-login-input {
        width: 100%;
        height: 38px;
        border-radius: 8px;
        margin-top: 20px;
        color: #6a89cc;
        font-size: 15px;
        font-family: 'Montserrat', sans-serif;
    }
    .main-login-desc {
        color: white;
        font-size: 12px;
        font-family: 'Montserrat', sans-serif;
    }
    .main-login-button {
        margin-top: 30px;
        width: 100%;
        height: 50px;
        color: #6a89cc;
        font-size: 20px;
        font-family: 'Montserrat', sans-serif;
        border-radius: 10px;
    }

    /** Loader **/
    .loader-fleetline {
        z-index: 9999;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: #6a89cc;
    }
    .loader-fleetline img {
        margin-top: 35vh;
        transform: translateY(-35%);
        display: block;
        margin-left: auto;
        margin-right: auto;
    }


</style>


<script type="text/javascript">
// Loader
$(window).on('load', function () {
    $(".loader-fleetline").fadeOut(400);
    $('.loader-on').click(function (e) {
        e.preventDefault();
        var goTo = this.getAttribute("onclick");
        $(".loader-fleetline").fadeIn(400);
        setTimeout(function(){
            window.location = goTo.substring(15, 25);;
        }, 400);
    });
});
</script>




</body>



@endsection

<?php

include("head.php");

?>


<!-- ====================================== -->
<!-- ===== Header ========================= -->
<!-- ====================================== -->

<header>

    <div class="header">

        <!-- Partie gauche -->
        <div class="header-left">
            <a href="home.php" class="loader-on"><img class="header-logo" src="assets/images/logo.png" alt="Logo"></a>
        </div>


        <?php

        include('includes/database.php');

        $sql = $bdd -> prepare("SELECT pdp, id FROM utilisateurs WHERE mail = ?");
        $sql -> execute(array($_COOKIE['mail']));

        $results = $sql -> fetch();

        ?>


        <!-- Partie droite -->
        <div class="header-right">
            <a href="profil.php?id=<?php echo $results['id']; ?>" class="loader-on"><img class="header-pdp" src="<?php echo $results['pdp']; ?>" alt="Logo"></a>
        </div>

    </div>

</header>

<?php

// ========================
// RÉCUPÉRATION DE LA BDD :
// ========================
include('database.php');



/**
* Redirige ajax vers la bonne fonction a executer :
*/
if (isset($_POST['action']) && !empty($_POST['action'])) {
    $function2call = $_POST['action'];

    switch($function2call) {

        case 'ajax_new_event':
        ajax_new_event($bdd);
        break;

        case 'ajax_search_autocomplete':
        ajax_search_autocomplete($bdd);
        break;

        case 'ajax_home_page':
        ajax_home_page($bdd);
        break;

        case 'ajax_register_user':
        ajax_register_user($bdd);
        break;

        case 'ajax_event_page':
        ajax_event_page($bdd);
        break;

        case 'ajax_profil_posts':
        ajax_profil_posts($bdd);
        break;

        case 'ajax_je_participe':
        ajax_je_participe($bdd);
        break;

        case 'ajax_load_participants':
        ajax_load_participants($bdd);
        break;
    }
}



/**
*  Renvoie true si l'heure à laquelle l'utilisateur affiche la page est comprise
*  entre le début de l'événement, et l'événement +48h.
*  (on ne peut donc ajouter/consulter des photos que si l'événement à déjà commencé,
*  et on garde le principe d'éphémérité avec le +48h).
*/
function doesPostAcceptsPhotos($jour, $mois, $annee, $heure) {

    // Récupération du timestamp actuel
    $now = new DateTime();
    $now_timestamp = $now->getTimestamp();


    // Get le timestamp de l'événement
    $date_event = new DateTime($annee."-".$mois."-".$jour);
    $date_event_timestamp = $date_event->getTimestamp();
    $date_event_timestamp += ($heure * 3600);

    // Date de péremption des photos
    $date_event_timestamp_end = $date_event_timestamp + (48 * 3600);


    // Si l'heure à laquelle l'utilisateur essaie d'accéder à la page de l'évent est
    if ($now_timestamp >= $date_event_timestamp && $now_timestamp <= $date_event_timestamp_end) {
        return "true";
    }
    return "false";
}





/**
* Poste un nouvel événement dans la bdd
*/
function ajax_new_event($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $auteur = $_COOKIE['mail'];
        $titre = $_REQUEST['titre'];
        $heure = $_REQUEST['heure'];
        $lieu = $_REQUEST['lieu'];
        $description = $_REQUEST['description'];
        $temp = $_REQUEST['date'];
        $date_debut = strtotime($temp) + (intval(substr($heure, 0, 2)) * 3600) + (intval(substr($heure, -2)) * 60);

        $sql = $bdd -> prepare("INSERT INTO posts (auteur, date_debut, heure, titre, description, lieu) VALUES(?, ?, ?, ?, ?, ?)");
        $sql -> execute(array($auteur, $date_debut, $heure, $titre, $description, $lieu));

        echo "true";
    }


}





/**
* Permet d'afficher des propositions de réponses au fur et à mesure
* que l'utilisateur tape dans la barre de recherche
*/
function ajax_search_autocomplete($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $recherche = $_REQUEST['recherche'];

        $sql = $bdd -> prepare("SELECT * FROM utilisateurs WHERE prenom LIKE ? OR nom LIKE ? OR mail LIKE ?");
        $sql -> execute(array("%".$recherche."%", "%".$recherche."%", "%".$recherche."%"));

        $results = $sql -> fetchAll();
        $temp = "";

        foreach ($results as $resultat) {
            $temp = $temp." ".$resultat["prenom"]." ".$resultat["nom"]." ".$resultat["mail"];
        }

        echo $temp;

    } else {
        echo "false";
    }


}




/**
* Fonction qui récupère tout les posts et qui les renvoies sous forme d'un unique string
*/
function ajax_home_page($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $sql = $bdd -> prepare("SELECT * FROM posts ORDER BY date_debut");
        $sql -> execute();

        $results = $sql -> fetchAll();
        $output = "";

        foreach ($results as $resultat) {

            // On vérifie que l'événement n'est pas déjà passé
            if ($resultat['date_debut'] > time()) {

                // Préparation des variables :
                $sql2 = $bdd -> prepare("SELECT * FROM utilisateurs WHERE mail = ?");
                $sql2 -> execute(array($resultat['auteur']));
                $results2 = $sql2 -> fetch();

                $date = new DateTime();
                $date->setTimestamp($resultat['date_debut']);
                $date = date_format($date, 'd/m/Y');

                $output = $output."
                <a href='evenement.php?id=".$resultat['id']."' class='loader-on'>
                <div class='home-page'>
                <div class='home-post'>
                <img class='post-auteur-pdp' src='".$results2['pdp']."' alt='PDP'>
                <p class='post-auteur-nom'>".$results2['prenom']." ".$results2['nom'][0].".</p>
                <p class='post-title'>".$resultat['titre']."</p>
                <p class='post-desc'>".$resultat['description']."</p>
                <div class='post-autres'>
                <p class='post-ville'>".substr($resultat['lieu'], 0, 10)."</p>
                <p class='post-date'>".$date."</p>
                <p class='post-heure'>".$resultat['heure']."</p>
                <p class='post-likes'>❤ 13</p>
                </div>
                </div>
                </div>
                </a>";

            }

        }

        echo $output;

    }

}






/**
* Enregistre l'utilisateur dans la bdd si il n'existe pas encore
*/
function ajax_register_user($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $sql = $bdd -> prepare("SELECT * FROM utilisateurs WHERE mail = ?");
        $sql -> execute(array($_COOKIE['mail']));

        $result = $sql -> rowCount();

        if ($result == 0) {
            $sql2 = $bdd -> prepare("INSERT INTO  utilisateurs (mail, nom, prenom) VALUES (?, ?, ?)");
            $sql2 -> execute(array($_COOKIE['mail'], $_COOKIE['nom'], $_COOKIE['prenom']));
        }

    }

}




/**
* Renvoie les informations de l'événement en question
*/
function ajax_event_page($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        // On vérifie que l'id existe bien :
        $sql = $bdd -> prepare("SELECT * FROM posts WHERE id = ?");
        $sql -> execute(array($_REQUEST['event_id']));
        $result = $sql -> rowCount();
        if ($result != 0) {

            $sql2 = $bdd -> prepare("SELECT * FROM posts WHERE id = ?");
            $sql2 -> execute(array($_REQUEST['event_id']));
            $results2 = $sql2 -> fetch();

            $date = new DateTime();
            $date->setTimestamp($results2['date_debut']);
            $date = date_format($date, 'd/m/Y');

            $output = "<div class='post-description'>
            <p class='post-description-titre'>".$results2['titre']."</p>
            <div class='post-description-date'>
            <p class='post-description-date-j'>".$date."</p>
            <p class='post-description-date-h'>".$results2['heure']."</p>
            </div>
            <div class='post-description-description'>
            <p class='post-description-description-lieu'>".$results2['lieu']."</p>
            <p class='post-description-description-texte'>".$results2['description']."</p>
            </div>
            </div>";

            echo $output;

        } else {
            // Si l'id n'existe pas :
            echo "redirect";
        }

    } else {
        echo "erreur";
    }

}




/**
* Chargement des posts de l'utilisateur sur sa page de profil
*/
function ajax_profil_posts($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $sql = $bdd -> prepare("SELECT * FROM posts WHERE auteur = ? ORDER BY date_debut");
        $sql -> execute(array($_COOKIE['mail']));

        $results = $sql -> fetchAll();
        $output = "";

        foreach ($results as $resultat) {

            // On vérifie que l'événement n'est pas déjà passé
            if ($resultat['date_debut'] > time()) {

                // Préparation des variables :
                $sql2 = $bdd -> prepare("SELECT * FROM utilisateurs WHERE mail = ?");
                $sql2 -> execute(array($resultat['auteur']));
                $results2 = $sql2 -> fetch();

                $date = new DateTime();
                $date->setTimestamp($resultat['date_debut']);
                $date = date_format($date, 'd/m/Y');

                $output = $output."
                <a href='evenement.php?id=".$resultat['id']."' class='loader-on'>
                <div class='home-page'>
                <div class='home-post'>
                <img class='post-auteur-pdp' src='".$results2['pdp']."' alt='PDP'>
                <p class='post-auteur-nom'>".$results2['prenom']." ".$results2['nom'][0].".</p>
                <p class='post-title'>".$resultat['titre']."</p>
                <p class='post-desc'>".$resultat['description']."</p>
                <div class='post-autres'>
                <p class='post-ville'>".substr($resultat['lieu'], 0, 10)."</p>
                <p class='post-date'>".$date."</p>
                <p class='post-heure'>".$resultat['heure']."</p>
                <p class='post-likes'>❤ 13</p>
                </div>
                </div>
                </div>
                </a>";

            }

        }

        echo $output;

    }


}




/**
* Ajoute un utilisateur à la liste des participants d'un événement
*/
function ajax_je_participe($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $sql = $bdd -> prepare("INSERT INTO participants (utilisateur, post) VALUES (?, ?)");
        $sql -> execute(array($_COOKIE['mail'], $_REQUEST['event_id']));
        echo "true";

    } else {
        echo "erreur";
    }

}




/**
* Charge les pdp de tout les participants d'un événement
*/
function ajax_load_participants($bdd) {


    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $sql = $bdd -> prepare("SELECT * FROM participants WHERE post = ? ORDER BY id");
        $sql -> execute(array($_REQUEST['event_id']));

        $results = $sql -> fetchAll();
        $output = "";

        foreach ($results as $resultat) {

            // Préparation des variables :
            $sql2 = $bdd -> prepare("SELECT * FROM utilisateurs WHERE mail = ?");
            $sql2 -> execute(array($resultat['utilisateur']));
            $results2 = $sql2 -> fetch();

            $output = $output."
            <div class='post-participants-test'><img src='".$results2['pdp']."'/></div>";

        }

        echo $output;

    }

}


?>

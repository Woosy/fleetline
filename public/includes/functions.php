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
        $date_debut = strtotime($temp);

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

        $sql = $bdd -> prepare("SELECT * FROM posts ORDER BY id DESC");
        $sql -> execute();

        $results = $sql -> fetchAll();
        $output = "";

        foreach ($results as $resultat) {


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
            <img class='post-auteur-pdp' src='assets/images/pdp_defaut_homme.png' alt='PDP'>
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

?>

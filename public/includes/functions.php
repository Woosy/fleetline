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

        case 'ajax_user_login':
        ajax_user_login($bdd);
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

        // $auteur = $_SESSION['utilisateur_id'];
        $auteur = "1";
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
* Connexion de l'utilisateur
*/
function ajax_user_login($bdd) {

    // On vérifie que les données sont bien transmises
    if (isset($_REQUEST)) {

        $mail = $_REQUEST['mail'];
        $mdp = $_REQUEST['mdp'];

        $sql = $bdd -> prepare("SELECT * FROM utilisateurs WHERE mail = ? AND mdp = ?");
        $sql -> execute(array($mail, $mdp));
        $isValid = $sql -> rowCount();

        if ($isValid == "1") {
            $infos = $sql -> fetch();
            $_SESSION['uti_id'] = $infos['id'];
            $_SESSION['uti_mail'] = $infos['mail'];
            $_SESSION['uti_prenom'] = $infos['prenom'];
            $_SESSION['uti_nom'] = $infos['nom'];

            echo "true";
            
        } else {
            echo "false";
        }

    } else {
        echo "false";
    }


}


?>

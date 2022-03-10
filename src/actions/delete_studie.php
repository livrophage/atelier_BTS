<?php
include_once "check_security_token.php";
$redirect = "studies.php"; //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, l'études et ses données n'ont pas pu être supprimées, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
    include_once "../config.php";
    include_once "database-connection.php";

    $id = filter_input(INPUT_POST, "id");

    if (sqlCommand("SELECT count(id) FROM etudes WHERE id=:id", [":id" => $id], $conn)[0][0] == 1) { //vérification si l'id de l'étude existe
            sqlCommand("DELETE FROM plages WHERE id=:id", [":id" => $id], $conn, false);
            $_SESSION["error"] = false; //succès
            $_SESSION["error_message"] = "étude supprimée avec succès";
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible de supprimer cette étude, les données ne sont pas valides";
    }
    header("location: ../../admin/studies.php");//retour à la page


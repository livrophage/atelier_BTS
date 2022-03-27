<?php
include_once "check_security_token.php";
$redirect = "beach.php"; //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, la plage n'a pas pu être supprimée, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
    include_once "../config.php";
    include_once "database-connection.php";

    $id = filter_input(INPUT_POST, "id");

    if (sqlCommand("SELECT count(id) FROM plages WHERE id=:id", [":id" => $id], $conn)[0][0] == 1) { //vérification si l'id de la plage existe
            sqlCommand("DELETE FROM plages WHERE id=:id", [":id" => $id], $conn, false);
            $_SESSION["error"] = false; //succès
            $_SESSION["error_message"] = "Plage supprimée avec succès";
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible de supprimer cette plage, les données ne sont pas valides";
    }
    header("location: ../../raminaplaya/beach.php");//retour à la page


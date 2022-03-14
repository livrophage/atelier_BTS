<?php
include_once "check_security_token.php";
$redirect = "town.php"; //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, la commune n'a pas pu être supprimée, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
    include_once "../config.php";
    include_once "database-connection.php";

    $id = filter_input(INPUT_POST, "id");

    if (sqlCommand("SELECT count(id) FROM communes WHERE id=:id", [":id" => $id], $conn)[0][0] == 1) { //vérification si l'id de la commune existe
        $nbr_use = sqlCommand("SELECT count(id) FROM plages WHERE id_commune=:id", [":id" => $id], $conn)[0][0]; //vérification si la commune est utilisé par une plage
        if ($nbr_use == 0){
            sqlCommand("DELETE FROM communes WHERE id=:id", [":id" => $id], $conn, false);
            $_SESSION["error"] = false; //succès
            $_SESSION["error_message"] = "Commune supprimée avec succès";
        } else {
            $_SESSION["error"] = true; //erreur
            $_SESSION["error_message"] = "Impossible de supprimer cette commune, elle est utilisé comme commune d'une plage";
        }
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible de supprimer cette commune, les données ne sont pas valides";
    }
    header("location: ../../raminaplaya/town.php");//retour à la page


<?php
include_once "check_security_token.php";
$redirect = "user.php"; //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, l'utilisateur et ses données n'ont pas pu être supprimés, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
    include_once "../config.php";
    include_once "function.php";
    include_once "database-connection.php";

    $id = filter_input(INPUT_POST, "id");
    $type = sqlCommand("SELECT type_utilisateur FROM utilisateur WHERE id=:id",[":id"=>$id],$conn)[0]["type_utilisateur"];
    if (sqlCommand("SELECT count(id) FROM utilisateur WHERE id=:id", [":id" => $id], $conn)[0][0] == 1 && //vérification si l'id de l'utilisateur existe
        in_array($type, ["utilisateur", "administrateur", "superadministrateur"]) && ($type != "superadministrateur" || isSuperadmin()) && $id != $_SESSION["user_id"]){ //vérification de l'autorisation de l'utilisateur pour supprimer ce compte
            sqlCommand("DELETE FROM utilisateur WHERE id=:id", [":id" => $id], $conn, false);
            $_SESSION["error"] = false; //succès
            $_SESSION["error_message"] = "Utilisateur supprimé avec succès";
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible de supprimer cet utilisateur";
    }
    header("location: ../../raminaplaya/user.php");//retour à la page

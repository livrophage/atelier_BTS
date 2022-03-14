<?php
include_once "check_security_token.php";
$redirect = "species.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, les données n'ont pas pu être modifié, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté


//connection à la base de donnée
    include_once "function.php";
    include_once "../config.php";
    include_once "database-connection.php";

//récupération du nouveau nom de l'espèce
    $new_name = filter_input(INPUT_POST, "new_name");
    $id = filter_input(INPUT_POST, "id");

    if (checkLenString($new_name, 32) && sqlCommand("SELECT count(id) FROM especes WHERE id=:id", [":id" => $id], $conn)[0][0] == 1) {
        sqlCommand("UPDATE especes SET nom=:name WHERE id=:id", [":name" => $new_name, ":id" => $id], $conn,false);
        $_SESSION["error"] = false;//succès
        $_SESSION["error_message"] = "Données modifiées avec succès";
    } else {
        $_SESSION["error"] = true;//erreur
        $_SESSION["error_message"] = "Impossible de modifier les données, elles ne sont pas valide";
    }
    header("location: ../../raminaplaya/species.php");//retour à la page

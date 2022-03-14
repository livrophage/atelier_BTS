<?php
include_once "check_security_token.php";
$redirect = "beach.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, les données n'ont pas pu être modifié, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté


//connection à la base de donnée
    include_once "function.php";
    include_once "../config.php";
    include_once "database-connection.php";

//récupération du nouveau nom de la plage
    $new_name = filter_input(INPUT_POST, "new_name");
    $area = intval(filter_input(INPUT_POST,"area"));
    $id = filter_input(INPUT_POST, "id");

    if (checkLenString($new_name, 32) && checkInt($area,1,65535) && sqlCommand("SELECT count(id) FROM plages WHERE id=:id", [":id" => $id], $conn)[0][0] == 1) {
        sqlCommand("UPDATE plages SET nom=:name, superficie_etude=:area WHERE id=:id", [":name" => $new_name, ":id" => $id, ":area" => $area], $conn,false);
        $_SESSION["error"] = false;//succès
        $_SESSION["error_message"] = "Données modifiées avec succès";
    } else {
        $_SESSION["error"] = true;//erreur
        $_SESSION["error_message"] = "Impossible de modifier les données, elles ne sont pas valide";
    }
    header("location: ../../raminaplaya/beach.php");//retour à la page

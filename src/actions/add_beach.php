<?php
include_once "check_security_token.php";
$redirect = "beach.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, les données n'ont pas pu être enregistrées, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
include_once "../config.php";
include_once "database-connection.php";
include_once "function.php";
//récupération des données
$data = getPost(["name","area","town","nbr_department"]);
    if (checkLenString($data["name"], 32) && checkLenString($data["town"],32) && checkInt(intval($data["area"]),1,65535) && checkDepartment($data["nbr_department"])) {
        if (sqlCommand("SELECT count(id) FROM communes WHERE nom LIKE :name AND num_departement LIKE :nbr", [":name" => $data["town"],":nbr"=>$data["nbr_department"]], $conn)[0][0] == 0){
            sqlCommand("INSERT INTO communes (nom, num_departement) VALUES (:name,:number)",[":name"=>$data["town"],":number"=>$data["nbr_department"]],$conn);
        }
        $id_town = sqlCommand("SELECT id FROM communes WHERE nom LIKE :name AND num_departement LIKE :nbr", [":name" => $data["town"],":nbr"=>$data["nbr_department"]], $conn)[0]["id"];
        sqlCommand("INSERT INTO plages (nom, id_commune, superficie_etude) VALUES (:beach,:town_id,:area)", [":beach" => $data["name"],":town_id"=>$id_town,":area"=>$data["area"]], $conn, false);
        $_SESSION["error"] = false; //succès
        $_SESSION["error_message"] = "Plage ajoutée avec succès";
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible d'ajouter cette plage, les données ne sont pas valides";
    }
    header("location: ../../raminaplaya/beach.php");//retour à la page
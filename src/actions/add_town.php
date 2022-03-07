<?php
include_once "check_security_token.php";
$redirect = "town.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, les données n'ont pas pu être modifié, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
include_once "../config.php";
include_once "database-connection.php";
include_once "function.php";
//récupération des données
$data = getPost(["town","nbr_department"]);
    if (checkLenString($data["town"],32)  && checkDepartment($data["nbr_department"])) {
        sqlCommand("INSERT INTO communes (nom, num_departement) VALUES (:name,:number)",[":name"=>$data["town"],":number"=>$data["nbr_department"]],$conn);
        $_SESSION["error"] = false; //succès
        $_SESSION["error_message"] = "Commune ajoutée avec succès";
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible d'ajouter cette commune, les données ne sont pas valides";
    }
    header("location: ../../admin/town.php");//retour à la liste des communes
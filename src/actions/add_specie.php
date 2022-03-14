<?php
include_once "check_security_token.php";
$redirect = "species.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, les données n'ont pas pu être enregistrées, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
include_once "../config.php";
include_once "database-connection.php";
include_once "function.php";
//récupération des données
$data = getPost(["specie"]);
    if (checkLenString($data["specie"],32)) {
        sqlCommand("INSERT INTO especes (nom) VALUES (:name)",[":name"=>$data["specie"]],$conn);
        $_SESSION["error"] = false; //succès
        $_SESSION["error_message"] = "Espèce ajoutée avec succès";
    } else {
        $_SESSION["error"] = true; //erreur
        $_SESSION["error_message"] = "Impossible d'ajouter cette espèce, les données ne sont pas valides";
    }
    header("location: ../../raminaplaya/species.php");//retour à la liste des espèces
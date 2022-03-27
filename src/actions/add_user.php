<?php
include_once "check_security_token.php";
$redirect = "user.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, l'utilisateur n'a pas pu être ajouté, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté

//connection à la base de donnée
include_once "../config.php";
include_once "database-connection.php";
include_once "function.php";
//récupération des données
$data = getPost(["name", "firstname", "email","password", "user_type"]);
if (checkLenString($data["name"], 32) && checkLenString($data["firstname"], 32) && checkLenString($data["email"], 320) && checkEmail($data["email"])
    && checkLenString($data["password"], 128) && in_array($data["user_type"], ["utilisateur", "administrateur", "superadministrateur"]) && ($data["user_type"] != "superadministrateur" || isSuperadmin())) {
    if (sqlCommand("SELECT count(id) FROM utilisateur WHERE email=:email", [":email" => $data["email"]], $conn)[0][0] != 0) {
        $_SESSION["error"] = true;//erreur
        $_SESSION["error_message"] = "L'adresse mail est déjà utilisé";
    } else {
        sqlCommand("INSERT INTO utilisateur (nom, prenom, email,mdp, type_utilisateur) VALUES (:name,:firstname,:email,:pwd,:type)", [":name" => $data["name"], ":firstname" => $data["firstname"], ":email" => $data["email"],":pwd"=> password_hash($data["password"],PASSWORD_BCRYPT ), ":type" => $data["user_type"]], $conn);
        $_SESSION["error"] = false; //succès
        $_SESSION["error_message"] = "Utilisateur ajouté avec succès";
    }
} else {
    $_SESSION["error"] = true; //erreur
    $_SESSION["error_message"] = "Impossible d'ajouter cet utilisateur, les données ne sont pas valides";
}
header("location: ../../raminaplaya/user.php");//retour à la liste des communes
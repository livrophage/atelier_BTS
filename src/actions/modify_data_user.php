<?php
include_once "check_security_token.php";
$redirect = "user.php";//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$message = "Suite à une erreur, les données de l'utilisateur n'ont pas pu être modifiée, veuillez recommencer";//message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté


//connection à la base de donnée
include_once "function.php";
include_once "../config.php";
include_once "database-connection.php";

//récupération du nouveau nom de la plage
$data = getPost(["id", "name", "firstname", "email", "user_type"]);

if (checkLenString($data["name"], 32) && checkLenString($data["firstname"], 32) && checkLenString($data["email"], 320) && checkEmail($data["email"])
    && in_array($data["user_type"], ["utilisateur", "administrateur", "superadministrateur"]) && ($data["user_type"] != "superadministrateur" || isSuperadmin()) &&
    sqlCommand("SELECT count(id) FROM utilisateur WHERE id=:id", [":id" => $data["id"]], $conn)[0][0] == 1 && $data["id"] != $_SESSION["user_id"]) {
    if (sqlCommand("SELECT count(id) FROM utilisateur WHERE email=:email AND id!=:id", [":email" => $data["email"], ":id" => $data["id"]], $conn)[0][0] == 1) {
        $_SESSION["error"] = true;//erreur
        $_SESSION["error_message"] = "L'adresse mail est déjà utilisé";
    } else {
        sqlCommand("UPDATE utilisateur SET nom=:name,prenom=:firstname,email=:email,type_utilisateur=:user_type WHERE id=:id", [":name" => $data["name"],":firstname" => $data["firstname"],":email" => $data["email"],":user_type" => $data["user_type"], ":id" => $data["id"]], $conn, false);
        $_SESSION["error"] = false;//succès
        $_SESSION["error_message"] = "Données modifiées avec succès";
    }
} else {
    $_SESSION["error"] = true;//erreur
    $_SESSION["error_message"] = "Impossible de modifier les données, elles ne sont pas valide";
}
header("location: ../../raminaplaya/user.php");//retour à la page

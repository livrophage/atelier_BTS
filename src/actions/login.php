<?php
include_once "check_security_token.php";
include_once "../config.php";
include_once "database-connection.php";

$email = filter_input(INPUT_POST,'email');
$psw = filter_input(INPUT_POST,"password");
$user = sqlCommand("SELECT * FROM utilisateur WHERE email=:email",[':email'=>$email],$conn)[0];
if (password_verify($psw,$user["mdp"])){ //vérifie si l'utilisateur existe et si le mdp est bon
    $_SESSION["username"] = $user["prenom"]." ".substr($user["nom"],0,1);
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_type"] = $user["type_utilisateur"];
    if (isset($_SESSION["redirect"])){
        $redirection = $_SESSION["redirect"];//vérifie si une redirection a été donnée
        unset($_SESSION["redirect"]);
    }else{//redirection par défaut
        $redirection = "species.php";
    }
    header("location: ../../raminaplaya/".$redirection);
} else {
    $_SESSION["error_message_connection"] = "Identifiant ou mot de passe incorrect";
    //header("location: ../../raminaplaya/login.php");
}
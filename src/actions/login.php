<?php
include_once "check_security_token.php";
include_once "../config.php";
include_once "database-connection.php";

$email = filter_input(INPUT_POST,'email');
$psw = filter_input(INPUT_POST,"password");
$user = sqlCommand("SELECT * FROM utilisateur WHERE email=:email AND mdp = :password",[':email'=>$email, ':password'=>$psw],$conn);
if (count($user)==1){ //vérifie si l'utilisateur existe et si le mdp est bon
    $_SESSION["username"] = $user[0]["prenom"]." ".substr($user[0]["nom"],0,1);
    $_SESSION["user_id"] = $user[0]["id"];
    $_SESSION["user_type"] = $user[0]["type_utilisateur"];
    if (isset($_SESSION["redirect"])){
        $redirection = $_SESSION["redirect"];//vérifie si une redirection a été donnée
        unset($_SESSION["redirect"]);
    }else{//redirection par défaut
        $redirection = "species.php";
    }
    header("location: ../../raminaplaya/".$redirection);
} else {
    $_SESSION["error_message_connection"] = "Identifiant ou mot de passe incorrect";
    header("location: ../../raminaplaya/login.php");
}
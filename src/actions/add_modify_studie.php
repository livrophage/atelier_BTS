<?php
include_once "check_security_token.php";
include_once "function.php";
$data_post = getPost(["name", "studie_id"]);

$redirect = "studies.php"; //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
if (isset($data_post["studie_id"])) { //message d'erreur après la connexion de l'utilisateur s'il n'était pas encore connecté
    $message = "Suite à une erreur, l'étude n'a pas pu être modifié, veuillez recommencer";
} else {
    $message = "Suite à une erreur, l'étude n'a pas pu être créée, veuillez recommencer";
}
    include_once "../config.php";
    include_once "database-connection.php";

    
    $beach = [];
    $beach_id = sqlCommand("SELECT id FROM plages", [], $conn);

    foreach ($beach_id as $l) {//récupère l'état des checkbox des plages
        $checkbox = filter_input(INPUT_POST, "checkbox_beach_" . $l["id"]);
        if (isset($checkbox) == true) {
            $beach[] = $l["id"];
        }
    }

    function checkBeach($list_beach_check, $list_beach) //vérifie si les plages existe
    {
        if (count($list_beach_check) == 0) {
            return false;
        }
        $list_id_beach = [];
        foreach ($list_beach as $beach_id) {
            $list_id_beach[] = $beach_id["id"];
        }
        foreach ($list_beach_check as $beach_check) {
            if (in_array($beach_check, $list_id_beach) == false) {
                return false;
            }
        }
        return true;
    }
    
    function checkId($id, $conn) //vérifie si l'id de l'étude existe
    {
        if ($id == null or sqlCommand("SELECT count(*) FROM etudes WHERE id=:id", [":id" => $id], $conn)[0][0] == 1) {
            return true;
        }
        return false;
    }

    $newStudie = $data_post["studie_id"] == null;


    if (checkLenString($data_post["name"], 32) && checkBeach($beach, $beach_id) && checkId($data_post["studie_id"], $conn)) {

        if ($newStudie == true) {//vérifie s'il s'agit d'une nouvelle étude
            sqlCommand("INSERT INTO etudes (nom) VALUES (:name)", [":name" => $data_post["name"]], $conn, false);

            $studie_id = sqlCommand("SELECT id FROM etudes ORDER BY id DESC LIMIT 1", [], $conn)[0]["id"];


            foreach ($beach as $s) {
                sqlCommand("INSERT INTO plages_etude (id_etude, id_plage) VALUES (:id_studie, :id_beach)", ["id_studie" => $studie_id, ":id_beach" => $s], $conn, false);
            }
            $_SESSION["id_studie"] = $studie_id;
            $_SESSION["error_message"] = "étude créer avec succès";
        }else{
            sqlCommand("UPDATE etudes SET nom = :name WHERE id = :studie_id",
                [":name" => $data_post["name"], ":studie_id" => $data_post["studie_id"]], $conn, false);

            $request_beach_studie_db = sqlCommand("SELECT id_plage FROM plages_etude WHERE id_etude=:id_studie", [":id_studie" => $data_post["studie_id"]], $conn);

            $beach_studie_id = [];
            foreach ($request_beach_studie_db as $element) {
                $id = $element['id_plage'];
                if (in_array($id, $beach)) {
                    //plages ajouter à l'étude
                    $beach_studie_id[] = $id;
                } else {
                    //plage à supprimer à l'étude
                    $delete_beach_form[] = $id;
                }
            }
            echo "1";
            if (isset($delete_beach_form) == true) {
                foreach ($delete_beach_form as $value) {//supprime l'association de la plage et de l'étude
                    sqlCommand("DELETE FROM plages_etude WHERE id_etude=:id_studie AND id_plage = :id_beach", ["id_studie" => $data_post["studie_id"], "id_beach" => $value], $conn, false);
                }
            }
            echo "2";
            foreach ($beach as $s) {
                if (in_array($s, $beach_studie_id) == false) {//créer une association entre la plage et l'étude
                    sqlCommand("INSERT INTO plages_etude (id_plage, id_etude) VALUES (:id_beach,:id_studie)", ["id_studie" => $data_post["studie_id"], "id_beach" => $s], $conn, false);
                }
            }
            echo "3aaaaaaaaaaaaaaa";
            $_SESSION["id_studie"] = $data_post["studie_id"];
            $_SESSION["error_message"] = "étude modifier avec succès";
        }

        $_SESSION["error"] = false;
        $_SESSION["name"] = $data_post["event_name"];
    } else { //message d'erreur
        $_SESSION["error"] = true;
        $_SESSION["error_message"] = "Impossible de créer ou de modifier l'étude, les données ne sont pas valide";
    }
    header("Location: ../../raminaplaya/studies.php");

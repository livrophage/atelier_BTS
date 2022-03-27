<?php
$title = "Données étude";
$id = filter_input(INPUT_GET, 'id');
$search = filter_input(INPUT_GET, 'search');
if (isset($id)) {//page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
    $redirect = basename(__FILE__) . "?id=" . $id;
    if (isset($search)) {
        $redirect = $redirect . "&search=$search";
    }
}
$page = basename(__FILE__);
include_once "../src/actions/check_connection_user.php";
include_once "../src/actions/function.php";
include_once "../src/config.php";
include_once "../src/actions/database-connection.php";


$id = filter_input(INPUT_GET, 'id');
if (isset($id)) {
    $exist = sqlCommand("SELECT count(id) FROM etudes WHERE id=:id", [":id" => $id], $conn)[0]['count(id)'];
    if ($exist != 1) { //si l'étude n'existe pas
        header("Location: ./studies.php");
        exit();
    } else { //si l'étude existe
        if (isset($search)) {
            $study_data = sqlCommand("
        SELECT especes.nom as specie, especes.id as specie_id,
        prelevements.nbr_specimenes as nbr_specimens, plages.nom as beach, plages.id as id_beach, communes.nom as town,
        groupes.coordonnee_depart_1_longitude as gps_long1, groupes.coordonnee_depart_1_latitude as gps_lat1,
        groupes.coordonnee_depart_2_longitude as gps_long2, groupes.coordonnee_depart_2_latitude as gps_lat2,
        groupes.coordonnee_arrivee_1_longitude as gps_long3, groupes.coordonnee_arrivee_1_latitude as gps_lat3,
        groupes.coordonnee_arrivee_2_longitude as gps_long4, groupes.coordonnee_arrivee_2_latitude as gps_lat4,
        plages.superficie_etude as area FROM prelevements   
        JOIN groupes on prelevements.groupe_id = groupes.id
        JOIN plages_etude ON groupes.id_plage_etude = plages_etude.id 
        JOIN plages ON plages_etude.id_plage = plages.id
        JOIN communes on communes.id = plages.id_commune 
        JOIN especes ON especes.id = prelevements.espece_id
        WHERE plages_etude.id_etude=:id AND (prelevements.nbr_specimenes LIKE :search OR plages.nom LIKE :search OR communes.nom LIKE :search)
        ", [":id" => $id, ":search" => "%" . $search . "%"], $conn);
        } else {
            $study_data = sqlCommand("
        SELECT especes.nom as specie, especes.id as specie_id,
        prelevements.nbr_specimenes as nbr_specimens, plages.nom as beach, plages.id as id_beach, communes.nom as town,
        groupes.coordonnee_depart_1_longitude as gps_long1, groupes.coordonnee_depart_1_latitude as gps_lat1,
        groupes.coordonnee_depart_2_longitude as gps_long2, groupes.coordonnee_depart_2_latitude as gps_lat2,
        groupes.coordonnee_arrivee_1_longitude as gps_long3, groupes.coordonnee_arrivee_1_latitude as gps_lat3,
        groupes.coordonnee_arrivee_2_longitude as gps_long4, groupes.coordonnee_arrivee_2_latitude as gps_lat4,
        plages.superficie_etude as area FROM prelevements   
        JOIN groupes on prelevements.groupe_id = groupes.id
        JOIN plages_etude ON groupes.id_plage_etude = plages_etude.id 
        JOIN plages ON plages_etude.id_plage = plages.id
        JOIN communes on communes.id = plages.id_commune 
        JOIN especes ON especes.id = prelevements.espece_id
        WHERE plages_etude.id_etude=:id
        ", [":id" => $id], $conn);
        }


        $data = [];
        $data_result = array("area" => 0, "area_studied" => 0, "species" => array());
        foreach ($study_data as $d) {
            if (array_key_exists($d["id_beach"], $data) == false) {
                $data[$d["id_beach"]] = array("beach_name" => $d["beach"], "beach_area" => intval($d["area"]), "area_studied" => 0, "town" => $d["town"], "species" => array());
                $data_result["area"] += $d["area"];
            }

            $area = areaCalculGPS($d["gps_lat1"], $d["gps_long1"], $d["gps_lat2"], $d["gps_long2"], $d["gps_lat3"], $d["gps_long3"], $d["gps_lat4"], $d["gps_long4"]);
            $data[$d["id_beach"]]["area_studied"] += $area;
            $data_result["area_studied"] += $area;

            if (array_key_exists($d["specie"], $data[$d["id_beach"]]["species"]) == false) {
                $data[$d["id_beach"]]["species"][$d["specie"]] = intval($d["nbr_specimens"]);
            } else {
                $data[$d["id_beach"]]["species"][$d["specie"]] += $d["nbr_specimens"];
            }

            if (array_key_exists($d["specie"], $data_result["species"]) == false) {
                $data_result["species"][$d["specie"]] = intval($d["nbr_specimens"]);
                $data_result["species"][$d["specie"]] = 0;
            }
        }
        foreach ($data as $d) {
            foreach (array_keys($d["species"]) as $s) {
                $data_result["species"][$s] += round($d["species"][$s] / $d["area_studied"] * $d["beach_area"]);
            }
        }
        include_once "../src/layout/header.php";
        ?>

        <div class="container">


            <?php searchTitle('Données de l\'étude', $search, "study_data.php?id=" . textSafe($id), "study_data.php?id=" . textSafe($id), textSafe($id)) ?>

            <div class="z-flex mb-4 mt-5">
                <a href="./export.php?id=<?= textSafe($id); ?>" class="btn btn-success"><span
                            class="fad fa-download"></span> Télécharger les données</a><!--TODO-->
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle table-hover"><!-- tableau avec les données-->
                    <thead>
                    <tr>
                        <th scope="col" style="width: 1rem;">Plages</th>
                        <th scope="col" class="text-wrap" style="width: 2rem;">nbr espèces</th>
                        <?php foreach (array_keys($data_result["species"]) as $specie) {
                            echo '<th scope="col" class="text-wrap" style="width: 4rem;">' . $specie . '</th>';
                        } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($study_data) == 0) {
                        ?>
                        <tr>
                            <th colspan="<?= count($data_result["species"]) + 2 ?>" class="text-center">Aucune donnée
                            </th>
                        </tr>
                        <?php
                    } else {
                        foreach ($data as $beach) { //ajoute une ligne au tableau pour chaque plage étudiée
                            ?>
                            <tr>
                                <th class="table-list"><?= $beach["beach_name"] ?></th>
                                <td class="table-list"><?= count($beach["species"]) ?></td>
                                <?php foreach (array_keys($data_result["species"]) as $specie) {
                                    if (isset($beach["species"][$specie])) {
                                        echo '<td class="table-list">' . round($beach["species"][$specie] / $beach["area_studied"] * $beach["beach_area"]) . ' (' . round($beach["species"][$specie] / $beach["area_studied"], 2) . ' Nb/m²)</td>';
                                    } else {
                                        echo '<td class="table-list">0</td>';
                                    }
                                } ?>
                            </tr>
                            <?php
                        }
                    } ?>
                    </tbody>
                    <?php if (count($study_data) != 0) { ?>
                        <tfoot>
                        <tr>
                            <th class="table-list">Total</th>
                            <td class="table-list"><?= count($data_result["species"]) ?></td>
                            <?php foreach ($data_result["species"] as $study) {
                                echo '<td class="table-list">' . $study . ' (' . round($study / $data_result["area"], 2) . ' Nb/m²)</td>';
                            } ?>
                        </tr>
                        </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
        <?php
    }
    include_once "../src/layout/footer.php";

} else {
    header("Location: ./studies.php");
    exit();
} ?>
<?php //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$search = filter_input(INPUT_GET, 'search');
if (isset($search)) {
    $redirect = basename(__FILE__) . "?search=$search";
}

$token = "aaaaa";
$title = "Liste des plages";
include_once "../src/actions/function.php";
include_once "../src/layout/header.php";
include_once "../src/config.php";
include_once "../src/actions/database-connection.php";

// Listing des plages déjà enregistrées dans la base de donnée
if (isset($search)) {
    $lines = sqlCommand("SELECT plages.id as id,plages.nom as nom, plages.superficie_etude as superficie,communes.nom as commune,communes.num_departement as departement FROM plages INNER JOIN communes ON plages.id_commune = communes.id WHERE plages.nom LIKE :search OR communes.nom LIKE :search OR communes.num_departement LIKE :search ORDER BY plages.nom", [":search" => "%" . $search . "%"], $conn);
} else {
    $lines = sqlCommand("SELECT plages.id as id,plages.nom as nom, plages.superficie_etude as superficie,communes.nom as commune,communes.num_departement as departement FROM plages INNER JOIN communes ON plages.id_commune = communes.id ORDER BY plages.nom", [], $conn);
}
$towns = sqlCommand("SELECT * from communes", [], $conn);
?>
    <section>
        <?php if (isset($_SESSION["error"])) {
            //afficher le message de l'erreur / succès
            if ($_SESSION["error"]) {
                echo "<div class='alert alert-danger'>"; //si erreur
            } else {
                echo "<div class='alert alert-success'>"; //si succès
            }
            echo $_SESSION["error_message"] . "</div>";
            unset($_SESSION["error"]);
            unset($_SESSION["error_message"]);
        } ?>
        <div class="container mt-5">
            <h1>Gestion/liste des plages</h1>
            <?php if (isset($search) and $search != "") {
                echo "<h2>Résultat de la recherche '" . textSafe($search) . "'</h2>";
            }

            searchInput($search, "beach_list.php", "beach_list.php");
            ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>Nom</th>
                    <th>Commune</th>
                    <th>N° département</th>
                    <th>Superficie étudiée (en m²)</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $nbr_line = 1;
                // création du tableau en fonction du nombre de plages enregistrées dans la base de donnée
                if (count($lines) == 0) {
                    echo "<tr><th class='text-center py-3' colspan='6'>Aucune donnée</th></tr>";
                } else {
                    foreach ($lines as $l) {
                        ?>
                        <tr>
                            <th><?= $nbr_line ?></th>
                            <td> <!-- affichage du nom de la plage -->
                                <?= textSafe($l["nom"]) ?>
                            </td>
                            <td>
                                <?= textSafe($l["commune"]) ?>
                            </td>
                            <td>
                                <?= textSafe($l["departement"]) ?>
                            </td>
                            <td>
                                <?= textSafe($l["superficie"]) ?>
                            </td>
                            <td> <!-- option applicable à la plage enregistrée dans la base de donnée-->
                                <div class="btn-group">
                                    <?php
                                    modalButton("<span class='fas fa-edit'></span>", "success", "modal_rename_" . $l['id']); //bouton modifier nom plage
                                    modalButton("<span class='fas fa-trash'></span>", "danger", "modal_delete_" . $l['id']); //bouton supprimer plage
                                    ?>
                                </div>

                                <?php modalRename($l["id"], "#", $token);
                                modalDelete($l["id"], "#", $token); ?>
                            </td>
                        </tr>
                        <?php
                        $nbr_line++;
                    }
                }
                ?>
                </tbody>
            </table>
            <hr>
            <div> <!-- ajout d'une nouvelle plage dans la base de donnée-->
                <h2 class="mt-5" id="add_beach">Ajouter une plage</h2>
                <form action="../src/actions/insert_beach.php" method="POST" class="mt-4 needs-validation w-50"
                      novalidate>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating mb-2">
                                <input type="text" name="name" placeholder="Nom" id="input_add_beach"
                                       class="form-control"
                                       maxlength="32" required> <!-- nommage de la plage -->
                                <label for="input_add_beach">Nom de la plage</label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-floating mb-2">
                                <input type="number" name="area" placeholder="superficie" id="input_area"
                                       class="form-control" max="65535" min="1" required>
                                <!-- surface étudiée de la plage -->
                                <label for="input_area">Superficie étudiée (en m²)</label>
                                <div class="invalid-feedback">
                                    La superficie étudiée doit être entre 1 et 65535 m²
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" list="datalistTown" id="town" placeholder="commune"
                               maxlength="32" required>
                        <datalist id="datalistTown">
                            <?php
                            foreach ($towns as $town) {
                                echo "<option value=" . $town['nom'] . ">";
                            } ?>
                        </datalist>
                        <label for="town">Nom de la commune</label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input class="form-control" list="departmentName" id="department"
                                       placeholder="Département" required pattern="<?php
                                $first_item = true;
                                foreach ($department_list as $d){
                                    if ($first_item == false){
                                        echo "|";
                                    }else{
                                        $first_item = false;
                                    }
                                    echo "$d";
                                } ?>">
                                <datalist id="departmentName">
                                    <?php
                                    foreach ($department_list as $d){
                                        echo "<option value='".$d."'>";
                                    }
                                    ?>
                                </datalist>
                                <label for="department">Département</label>
                                <div class="invalid-feedback">
                                    Département invalide
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-floating">
                                <input class="form-control" id="nbr_department" list="departmentNumber"
                                       placeholder="n° département" maxlength="4" required
                                pattern="<?php
                                $first_item = true;
                                foreach (array_keys($department_list) as $d){
                                    if ($first_item == false){
                                        echo "|";
                                    }else{
                                        $first_item = false;
                                    }
                                    echo "$d";
                                } ?>">
                                <label for="nbr_department">n° département</label>
                                <datalist id="departmentNumber">
                                    <?php
                                    foreach (array_keys($department_list) as $d){
                                        echo "<option value='".$d."'>";
                                    }
                                    ?>
                                </datalist>
                                <div class="invalid-feedback">
                                    n° département invalide
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <button type="submit" class="btn btn-success w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </section>
    <script>
        <?php jsFormValidatation(); ?>
        var list_towns = new Map();
        var list_department_inversed = new Map();
        var list_department = new Map();
        <?php foreach ($towns as $town) {
            echo "list_towns.set('" . strtolower($town["nom"]) . "','" . strtolower($town["num_departement"]) . "');";
        }
        foreach ($department_list as $department_nbr => $department_name) {
            echo 'list_department.set("' . $department_name . '","' . $department_nbr . '");';
            echo 'list_department_inversed.set("' . $department_nbr . '","' . $department_name . '");';
        }?>



        var input_nbr_department = document.getElementById("nbr_department");
        var input_department = document.getElementById("department");
        var input_town = document.getElementById("town")
        var last_value_department_nbr = "";
        var last_value_department_name = "";
        var value_auto_change = false;
        var department_autocomplete = "null";


        input_town.addEventListener('input', () => {
            var town = input_town.value.toLowerCase();
            if (list_towns.has(town)) {
                if (value_auto_change === false) {
                    last_value_department_nbr = input_nbr_department.value;
                    last_value_department_name = input_department.value;
                    value_auto_change = true;
                }
                input_nbr_department.value = list_towns.get(town);
                updateDepartmentName();
                input_nbr_department.setAttribute("disabled", "");
                input_department.setAttribute("disabled", "");
            } else {
                if (value_auto_change === true) {
                    input_nbr_department.value = last_value_department_nbr;
                    updateDepartmentName();
                }
                input_nbr_department.removeAttribute("disabled");
                input_department.removeAttribute("disabled");
            }
        });


        function updateDepartmentName(){
            if (list_department_inversed.has(input_nbr_department.value)){
                input_department.value = list_department_inversed.get(input_nbr_department.value);
            }else{
                input_department.value = "";
            }
        }

        function updateDepartmentNbr(){
            if (list_department.has(input_department.value)){
                input_nbr_department.value = list_department.get(input_department.value);
            }else{
                input_nbr_department.value = "";
            }
        }

        input_nbr_department.addEventListener("input",updateDepartmentName);
        input_department.addEventListener("input",updateDepartmentNbr);
    </script>
<?php
include "../src/layout/footer.php";
?>
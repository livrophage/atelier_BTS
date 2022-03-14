<?php //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$search = filter_input(INPUT_GET, 'search');
if (isset($search)) {
    $redirect = basename(__FILE__) . "?search=$search";
}else{
    $redirect = basename(__FILE__);
}
$page = basename(__FILE__);
$title = "Liste des communes";
include_once "../src/actions/security_token.php";
include_once "../src/actions/function.php";
include_once "../src/layout/header.php";
include_once "../src/config.php";
include_once "../src/actions/database-connection.php";

// Listing des communes déjà enregistrées dans la base de donnée
if (isset($search)) {
    $lines = sqlCommand("SELECT * FROM communes WHERE nom LIKE :search OR num_departement LIKE :search ORDER BY num_departement", [":search" => "%" . $search . "%"], $conn);
} else {
    $lines = sqlCommand("SELECT * FROM communes ORDER BY num_departement", [], $conn);
}

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
            <h1 id="town_list"><?php printIfAdmin("Gestion/liste des communes","Liste des communes") ?></h1>
            <?php if (isset($search) and $search != "") {
                echo "<h2>Résultat de la recherche '" . textSafe($search) . "'</h2>";
            }

            searchInput($search, "town.php", "town.php");
            ?>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>Commune</th>
                    <th>Département</th>
                    <?php printIfAdmin("<th>Action</th>") ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $nbr_line = 1;
                // création du tableau en fonction du nombre de communes enregistrées dans la base de donnée
                if (count($lines) == 0) {
                    echo "<tr><th class='text-center py-3' colspan='6'>Aucune donnée</th></tr>";
                } else {
                    foreach ($lines as $l) {
                        ?>
                        <tr>
                            <th><?= $nbr_line ?></th>
                            <td> <!-- affichage du nom de la commune -->
                                <?= textSafe($l["nom"]) ?>
                            </td>
                            <td>
                                <?= textSafe($l["num_departement"]) ?> - <?= $department_list[$l["num_departement"]] ?>
                            </td>
                        <?php if (isAdmin()){ ?>
                            <td> <!-- option applicable à la commune enregistrée dans la base de donnée-->
                                <div class="btn-group">
                                    <?php
                                    modalButton("<span class='fas fa-edit'></span>", "success", "modal_" . $l['id']); //bouton modifier nom commune
                                    modalButton("<span class='fas fa-trash'></span>", "danger", "modal_delete_" . $l['id']); //bouton supprimer commune
                                    ?>
                                </div>
                                <?php
                                $input = "<div class='form-floating mb-3'>
                                            <input type='text' placeholder='Nom' name='new_name'
                                                   id='name_" . $l["id"] . "' class='form-control'
                                                   maxlength='32' value='".$l["nom"]."' required>
                                            <label for='name_" . $l["id"] . "'>Nom</label>
                                        </div>";
                                modalModificationData($l["id"], "modify_data_town.php", $token, $input);
                                modalDelete($l["id"], "delete_town.php", $token); ?>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php
                        $nbr_line++;
                    }
                }
                ?>
                </tbody>
            </table>
            <hr>
            <?php if (isAdmin()){ ?>
            <div> <!-- ajout d'une nouvelle commune dans la base de donnée-->
                <h2 class="mt-5" id="add_town">Ajouter une commune</h2>
                <form action="../src/actions/add_town.php" method="POST" class="mt-4 needs-validation w-50"
                      novalidate>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="town" placeholder="commune" name="town"
                               maxlength="32" required>
                        <label for="town">Nom de la commune</label>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input class="form-control" list="departmentName" id="department"
                                       placeholder="Département" required pattern="<?php
                                $first_item = true;
                                foreach ($department_list as $d) {
                                    if ($first_item == false) {
                                        echo "|";
                                    } else {
                                        $first_item = false;
                                    }
                                    echo "$d";
                                } ?>">
                                <datalist id="departmentName">
                                    <?php
                                    foreach ($department_list as $d) {
                                        echo '<option value="' . $d . '">';
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
                                <input class="form-control" id="nbr_department" list="departmentNumber" name="nbr_department"
                                       placeholder="n° département" maxlength="4" required
                                       pattern="<?php
                                       $first_item = true;
                                       foreach (array_keys($department_list) as $d) {
                                           if ($first_item == false) {
                                               echo "|";
                                           } else {
                                               $first_item = false;
                                           }
                                           echo "$d";
                                       } ?>">
                                <label for="nbr_department">n° département</label>
                                <datalist id="departmentNumber">
                                    <?php
                                    foreach (array_keys($department_list) as $d) {
                                        echo "<option value='" . $d . "'>";
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
            <?php } ?>
        </div>
    </section>
    <script>
        <?php jsFormValidatation();
        if (isAdmin()){ ?>
        var list_department_inversed = new Map();
        var list_department = new Map();
        <?php
        foreach ($department_list as $department_nbr => $department_name) {
            echo 'list_department.set("' . $department_name . '","' . $department_nbr . '");';
            echo 'list_department_inversed.set("' . $department_nbr . '","' . $department_name . '");';
        }?>

        var input_nbr_department = document.getElementById("nbr_department");
        var input_department = document.getElementById("department");


        function updateDepartmentName() {
            input_nbr_department.value = input_nbr_department.value.toUpperCase();
            if (list_department_inversed.has(input_nbr_department.value)) {
                input_department.value = list_department_inversed.get(input_nbr_department.value);
            } else {
                input_department.value = "";
            }
        }

        function updateDepartmentNbr() {
            if (input_department.value.length !== 0) {
                while (input_department.value.charAt(0) === " ") {
                    input_department.value = input_department.value.slice(1);
                }
                input_department.value = input_department.value.charAt(0).toUpperCase() + input_department.value.slice(1);
            }
            if (list_department.has(input_department.value)) {
                input_nbr_department.value = list_department.get(input_department.value);
            } else {
                input_nbr_department.value = "";
            }
        }

        input_nbr_department.addEventListener("input", updateDepartmentName);
        input_department.addEventListener("input", updateDepartmentNbr);
        <?php } ?>

    </script>
<?php
include "../src/layout/footer.php";
?>
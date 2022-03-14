<?php //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$search = filter_input(INPUT_GET, 'search');
if (isset($search)) {
    $redirect = basename(__FILE__) . "?search=$search";
} else {
    $redirect = basename(__FILE__);
}
$page = basename(__FILE__);
$title = "Liste des utilisateurs";
include_once "../src/actions/security_token.php";
include_once "../src/actions/function.php";
if (isAdmin() == false) {
    header("location: studies.php");
} else {
    include_once "../src/layout/header.php";
    include_once "../src/config.php";
    include_once "../src/actions/database-connection.php";

// Listing des utilisateurs déjà enregistrés dans la base de donnée
    if (isset($search)) {
        $lines = sqlCommand("SELECT * FROM utilisateur WHERE nom LIKE :search OR email LIKE :search OR prenom LIKE :search OR type_utilisateur LIKE :search ORDER BY nom", [":search" => "%" . $search . "%"], $conn);
    } else {
        $lines = sqlCommand("SELECT * FROM utilisateur ORDER BY nom", [], $conn);
    }

    function input_form($id, $default_value = ["", "", "", "Utilisateur"])
    {
        $input = '<div class="row">
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name_' . $id . '" placeholder="nom" name="name"
                                       maxlength="32" required value="' . $default_value[0] . '">
                                <label for="name_' . $id . '">Nom</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="firstname_' . $id . '" placeholder="prénom"
                                       name="firstname" maxlength="32" required value="' . $default_value[1] . '">
                                <label for="firstname_' . $id . '">Prénom</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email_' . $id . '" placeholder="email" name="email"
                               maxlength="320" required  value="' . $default_value[2] . '"
                               pattern="^([a-z0-9]+(?:[._-][a-z0-9]+)*)@([a-z0-9]+(?:[.-][a-z0-9]+)*\.[a-z]{2,})$">
                        <label for="firstname_' . $id . '">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="user_type_' . $id . '" name="user_type" required>';


        foreach (["utilisateur", "administrateur", "superadministrateur"] as $type) {
            if (isSuperadmin()==false and $type=="superadministrateur"){
                $input .= '<option value=' . $type . ' disabled>' . $type . '</option>';
            } else if ($default_value[3] == $type) {
                $input .= '<option value=' . $type . ' selected>' . $type . '</option>';
            } else {
                $input .= '<option value=' . $type . '>' . $type . '</option>';
            }
        }
        $input .= '</select>
                        <label for="user_type_' . $id . '">Privilèges</label>
                    </div>';

        return $input;
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
            <h1 id="user_list">Gestion/liste des utilisateurs</h1>
            <?php if (isset($search) and $search != "") {
                echo "<h2>Résultat de la recherche '" . textSafe($search) . "'</h2>";
            }

            searchInput($search, "user.php", "user.php");
            ?>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Type utilisateur</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $nbr_line = 1;
                // création du tableau en fonction du nombre de comptes enregistrés dans la base de donnée
                if (count($lines) == 0) {
                    echo "<tr><th class='text-center py-3' colspan='6'>Aucune donnée</th></tr>";
                } else {
                    foreach ($lines as $l) {
                        ?>
                        <tr>
                            <th><?= $nbr_line ?></th>
                            <td> <!-- affichage du nom de l'utilisateur -->
                                <?= textSafe($l["nom"]) ?>
                            </td>
                            <td> <!-- affichage du prénom de l'utilisateur -->
                                <?= textSafe($l["prenom"]) ?>
                            </td>
                            <td> <!-- affichage de l'email de l'utilisateur -->
                                <?= textSafe($l["email"]) ?>
                            </td>
                            <td> <!-- affichage du type de l'utilisateur -->
                                <?= textSafe($l["type_utilisateur"]) ?>
                            </td>
                            <td> <!-- option applicable à l'utilisateur enregistré dans la base de donnée-->
                                <div class="btn-group">
                                    <?php
                                    if ((isSuperadmin() or $l["type_utilisateur"] != "superadministrateur") and $l["id"] != $_SESSION["user_id"]) { //Empêche un administrateur de supprimer ou de modifier le compte d'un superadministrateur ou de pouvoir modifier son propre compte
                                        modalButton("<span class='fas fa-edit'></span>", "success", "modal_" . $l['id']); //bouton modifier utilisateur
                                        modalButton("<span class='fas fa-trash'></span>", "danger", "modal_delete_" . $l['id']); //bouton supprimer utilisateur
                                    } else { ?>
                                        <button type='button' class='btn btn-success disabled hidden'><span
                                                    class='fas fa-edit'></span></button>
                                        <button type='button' class='btn btn-danger disabled'><span
                                                    class='fas fa-trash'></span></button>

                                    <?php } ?>
                                </div>
                                <?php
                                modalModificationData($l["id"], "modify_data_user.php", $token, input_form($l["id"], [$l["nom"], $l["prenom"], $l["email"], $l["type_utilisateur"]]));
                                modalDelete($l["id"], "delete_user.php", $token, "La suppression d'un utilisateur supprime toutes les données qui lui sont associées"); ?>
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
            <div> <!-- ajout d'un nouvel utilisateur dans la base de donnée-->
                <h2 class="mt-5" id="add_user">Ajouter un utilisateur</h2>
                <form action="../src/actions/add_user.php" method="POST" class="mt-4 needs-validation w-50"
                      novalidate>
                    <?= input_form("add_user") ?>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <button type="submit" class="btn btn-success w-100">Ajouter</button>
                </form>
            </div>
        </div>
    </section>
    <script>
        <?php jsFormValidatation(); ?>
    </script>
    <?php
    include "../src/layout/footer.php";
} ?>
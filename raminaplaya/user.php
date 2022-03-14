<?php //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$search = filter_input(INPUT_GET, 'search');
if (isset($search)) {
    $redirect = basename(__FILE__) . "?search=$search";
}else{
    $redirect = basename(__FILE__);
}
$page = basename(__FILE__);
$title = "Liste des utilisateurs";
include_once "../src/actions/security_token.php";
include_once "../src/actions/function.php";
if (isAdmin()==false){
    header("location: studies.php");
}else{
include_once "../src/layout/header.php";
include_once "../src/config.php";
include_once "../src/actions/database-connection.php";

// Listing des utilisateurs déjà enregistrés dans la base de donnée
if (isset($search)) {
    $lines = sqlCommand("SELECT * FROM utilisateur WHERE nom LIKE :search OR email LIKE :search OR prenom LIKE :search OR type_utilisateur LIKE :search ORDER BY nom", [":search" => "%" . $search . "%"], $conn);
} else {
    $lines = sqlCommand("SELECT * FROM utilisateur ORDER BY nom", [], $conn);
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
            <h1 id="species_list">Gestion/liste des utilisateurs</h1>
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
                                    modalButton("<span class='fas fa-edit'></span>", "success", "modal_" . $l['id']); //bouton modifier nom espèce
                                    modalButton("<span class='fas fa-trash'></span>", "danger", "modal_delete_" . $l['id']); //bouton supprimer espèce
                                    ?>
                                </div>
                                <?php
                                $input = "<div class='form-floating mb-3'>
                                            <input type='text' placeholder='Nom' name='new_name'
                                                   id='name_" . $l["id"] . "' class='form-control'
                                                   maxlength='32' value='".$l["nom"]."' required>
                                            <label for='name_" . $l["id"] . "'>Nom</label>
                                        </div>";
                                modalModificationData($l["id"], "modify_data_specie.php", $token, $input);
                                modalDelete($l["id"], "delete_specie.php", $token,"La suppression d'une espèce supprime toutes les données qui lui sont associées"); ?>
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
            <div> <!-- ajout d'une nouvelle espèce dans la base de donnée-->
                <h2 class="mt-5" id="add_specie">Ajouter une espèce d'annélide</h2>
                <form action="../src/actions/add_specie.php" method="POST" class="mt-4 needs-validation w-50"
                      novalidate>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="specie" placeholder="nom de l'espèce" name="specie"
                               maxlength="32" required>
                        <label for="specie">Nom de l'espèce</label>
                    </div>
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
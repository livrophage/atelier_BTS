<?php //page redirection après la connexion de l'utilisateur s'il n'était pas encore connecté
$id = filter_input(INPUT_GET, "id");
if (isset($id)){
    $redirect = basename(__FILE__)."?id=".$id;
}else{
    $redirect = basename(__FILE__);
}
include_once "../src/actions/security_token.php";
include_once "../src/config.php";
include_once "../src/actions/database-connection.php";
include_once "../src/actions/function.php";

if (isAdmin()==false){
    header("location: studies.php");
}else{

$beach = sqlCommand("SELECT * FROM plages ORDER BY nom", [], $conn); //liste des plages
if (isset($id) == true) {
    //modification d'une étude
    $data = sqlCommand("SELECT * FROM etudes WHERE id = :id", [":id" => $id], $conn)[0];
    if (count($data) == 0) {//vérifie si l'étude existe
        header("Location: ./studie.php"); //si non, la page se rafraichit sans l'id étude et donc la page pour créer une étude
    }
    $id = $data["id"];
    $beach_select = sqlCommand("SELECT id_plage FROM plages_etude WHERE id_etude = :id", [":id" => $id], $conn); //attribue les valeurs par défaut pour l'autocomplétion
    $beach_checked = [];
    foreach ($beach_select as $sector) {
        $beach_checked[] = $sector[0];
    }
    $title = "Modification étude";
} else {
    //ajout d'une étude
    //attribue
    $title = "Nouvelle étude";
    $beach_checked = [];
    $data['nom'] = "";
}
$page = basename(__FILE__);
include "../src/layout/header.php";

?>

<main>
    <section id="home-hero">
        <div class="container my-5 border border-4 px-5 py-5 bg-light">
            <div class="container-form">
                <form class="needs-validation" novalidate action="../src/actions/add_modify_studie.php" id="studie" name="studie"
                      method="POST" enctype="multipart/form-data">
                    <div class="form-floating mb-4"> <!--nom de l'étude-->
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="name"
                               value="<?php echo $data['nom']; ?>"
                               maxlength="32"
                               placeholder="nom de l'étude"
                               required>
                        <label for="name">Nom de l'étude</label>
                    </div>
                    <fieldset class="form-control"><!--sélection des plages étudiées-->
                        <legend>Sélection des plages</legend>
                        <?php
                        $item = 6;
                        echo "<div class='col-12 btn-group-vertical'>";
                        for ($y = 0; $y <= intdiv(count($beach), $item); $y++) {
                            echo "<div class='btn-group flex-wrap'>";
                            $nbr_x = (count($beach) - $y * $item >= $item) ? $item : count($beach) - $y * $item;
                            for ($x = 1; $x <= $nbr_x; $x++) {
                                $value = $beach[($x - 1) + $y * $item]["id"];
                                $name = "checkbox_beach_" . $value;
                                $beach_name = $beach[($x - 1) + $y * $item]['nom'];
                                $checked = (in_array($value, $beach_checked)) ? " checked" : "";
                                echo "<input type='checkbox'
                                        class='group-checkbox btn-check'
                                        id='$value'
                                        name='$name'
                                        onchange='checkbox_count(this)'
                                        $checked>
                                        <label class='btn btn-outline-success col-md-2 py-3 overflow-hidden rounded-0' for='$value' id='label_$value'>$beach_name</label>
                                      ";
                            }
                            echo "</div>";
                        }
                        echo "<input type='checkbox' id='checkbox_required' hidden required></div>";
                        ?>
                    </fieldset>
                    <div class="invalid-feedback">Vous devez sélectionner au moins une plage</div>
                    <input type="hidden" name="studie_id" value="<?= $id ?>">
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <div class="mt-5">
                        <input class="btn btn-outline-primary px-4" type="submit" value="Valider">
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<script>
    var checkbox = document.getElementById("checkbox_required");
    var count_checkbox = countCheckebox()
    validateCheckbox()

    function checkbox_count (input) {//ajoute 1 ou -1 en fonction de si une plage est coché
        if (input.checked) {
            count_checkbox ++;
        }else{
            count_checkbox --;
        }
        validateCheckbox()
    }

    function validateCheckbox () {//vérifie si au moins une checkbox est cochée
        if (count_checkbox >= 1 && checkbox.hasAttribute("required")){
            checkbox.removeAttribute("required");
        }else if (count_checkbox === 0 && checkbox.hasAttribute("required") === false){
            checkbox.setAttribute("required","required");
        }
    }

    function countCheckebox() {//compte le nombre de checkbox cochée
        var elements = document.getElementsByClassName("group-checkbox"), i, count = 0;
        for (i = 0; i < elements.length; i++) {
            if (elements[i].checked) {
                count++;
            }
        }
        return count;
    }

    <?php jsFormValidatation(); ?>

</script>
<?php
include "../src/layout/footer.php";
}
?>
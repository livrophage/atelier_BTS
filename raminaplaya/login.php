<?php
include_once "../src/actions/security_token.php";
if (isset($_SESSION["username"])){
    header("location: studies.php");
}
$title = "Connexion";
$class = "d-flex p-2 flex-column";
$navbar = false; //cache la navbar
include_once "../src/layout/header.php";
include_once "../src/actions/function.php";
if (isset($_SESSION["error_message_connection"])){//vérifie si une erreur est survenue lors d'une tentative de connexion
    $error_message = $_SESSION["error_message_connection"];
    $error = true;
    unset($_SESSION["error_message_connection"]);
}else{
    $error=false;
}
?>
<main class="form-signin text-center mx-auto">
    <div class="card bg-light">
        <div class="card-body">
            <h1 class="h2 mb-4 fw-normal">Se connecter</h1>
            <form action="../src/actions/login.php" id="register" method="POST" class="needs-validation" novalidate>
                <div class="form-floating mb-1">
                    <input type="text"
                           class="form-control form-login"
                           id="email"
                           name="email"
                           placeholder="jean@raminagrobis.fr"
                           value=""
                           maxlength="64"
                           required>
                    <label for="email">Adresse email</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password"
                           class="form-control form-login"
                           id="password"
                           name="password"
                           placeholder="password"
                           value=""
                           required>
                    <label for="password">Mot de passe</label>
                </div>
                <?php if($error){ //afficher le message d'erreur s'il y a eu une erreur
                    echo "<div class='alert alert-danger mb-4'><span class='fal fa-exclamation-triangle'></span> $error_message</div>";
                } ?>
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <button type="submit" class="btn btn-primary mb-4 w-100 py-2">
                    <span class="fas fa-sign-in-alt"></span> Se connecter
                </button>
            </form>
        </div>
    </div>
</main>
<script>
    <?php jsFormValidatation(); ?>
</script>
<?php
include "../src/layout/footer.php";
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="shortcut icon" href="../assets/img/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/da7397688c.js" crossorigin="anonymous"></script>
    <title><?= $title ?></title>
</head>

<?php
if (isset($class) == false) {
    $class = "";
}
if (isset($navbar) == false) {
    $navbar = true;
}
echo "<body class='$class'>";
if ($navbar){
    include_once "../src/actions/check_connection_user.php";
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand me-5 fw-bold" href="studies.php">
        <img src="../assets/img/logo.png" alt="" height="50px" class="">
            Raminaplaya
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (in_array($_SESSION["user_type"],["superadministrateur","administrateur"])){
                    navbarDropdown("Utilisateurs",[["Liste des utilisateurs","user.php#user_list"],["Ajouter un utilisateur","user.php#add_user"]],$page);
                    navbarDropdown("Plages",[["Liste des plages","beach.php#beach_list"],["Ajouter une plage","beach.php#add_beach"]],$page);
                    navbarDropdown("Communes",[["Liste des communes","town.php#town_list"],["Ajouter une communes","town.php#add_town"]],$page);
                    navbarDropdown("Espèces",[["Liste des espèces","species.php#species_list"],["Ajouter une espèce","species.php#add_specie"]],$page);
                    navbarDropdown("Études",[["Liste des études","studies.php"],["Nouvelle études","studie.php"]],$page);
                }else {
                    navbarLink("Plages", "beach.php",$page);
                    navbarLink("Communes", "town.php",$page);
                    navbarLink("Espèces", "species.php",$page);
                    navbarLink("Études", "studies.php",$page);
                }?>
            </ul>
            <div class="d-flex"></div>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <p class="navbar-text fs-4 my-auto me-3"><span
                                class="fad fa-user-circle"></span><?= $_SESSION["username"] ?></p>
                </li>
                <li class="nav-item mt-2">
                    <form action="../src/actions/logout.php" method="post">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <button type="submit" class="btn btn-primary"><span class="fas fa-sign-out-alt"></span> Se
                            déconnecter
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main>
<?php } ?>
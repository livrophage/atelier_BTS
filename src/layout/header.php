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
    <link rel="shortcut icon" href="../assets/img/logo-raminagrobis.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/da7397688c.js" crossorigin="anonymous"></script>
    <title><?= $title ?></title>
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <img src="../assets/img/logo-raminagrobis.png" alt="" height="40px" class="me-5">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item me-3">
                    <a class="nav-link active" href="#">Ajouter un compte</a>
                </li>
                <li class="nav-item dropdown me-3">
                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Plages</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../admin/beach.php#beach_list">Liste des plages</a></li>
                        <li><a class="dropdown-item" href="../admin/beach.php#add_beach">Ajouter une plage</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown me-3">
                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Communes</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../admin/town.php#town_list">Liste des communes</a></li>
                        <li><a class="dropdown-item" href="../admin/town.php#add_town">Ajouter une commune</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown me-3">
                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Espèces</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="species_list.php">Liste des espèces</a></li>
                        <li><a class="dropdown-item" href="#">Ajouter une espèce</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown me-3">
                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Études</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Liste des études</a></li>
                        <li><a class="dropdown-item" href="#">Nouvelle étude</a></li>
                    </ul>
                    </li>
            </ul>
            <div class="d-flex"></div>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <p class="navbar-text fs-4 my-auto me-3"><span class="fad fa-user-circle"></span>test</p>
                </li>
                <li class="nav-item mt-2">
                    <!--
                    <form action="../src/actions/logout.php" method="post">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <button type="submit" class="btn btn-primary"><span class="fas fa-sign-out-alt"></span> Se
                            déconnecter
                        </button>
                    </form>
                    -->
                </li>
            </ul>
        </div>
    </div>
</nav>
<main>

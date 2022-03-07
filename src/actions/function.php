<?php

// Tableau des départements français !

$department_list = array();
$department_list['01'] = 'Ain';
$department_list['02'] = 'Aisne';
$department_list['03'] = 'Allier';
$department_list['04'] = 'Alpes de Haute Provence';
$department_list['05'] = 'Hautes Alpes';
$department_list['06'] = 'Alpes Maritimes';
$department_list['07'] = 'Ardèche';
$department_list['08'] = 'Ardennes';
$department_list['09'] = 'Ariège';
$department_list['10'] = 'Aube';
$department_list['11'] = 'Aude';
$department_list['12'] = 'Aveyron';
$department_list['13'] = 'Bouches du Rhône';
$department_list['14'] = 'Calvados';
$department_list['15'] = 'Cantal';
$department_list['16'] = 'Charente';
$department_list['17'] = 'Charente Maritime';
$department_list['18'] = 'Cher';
$department_list['19'] = 'Corrèze';
$department_list['2A'] = 'Corse du Sud';
$department_list['2B'] = 'Haute Corse';
$department_list['21'] = 'Côte d\'Or';
$department_list['22'] = 'Côtes d\'Armor';
$department_list['23'] = 'Creuse';
$department_list['24'] = 'Dordogne';
$department_list['25'] = 'Doubs';
$department_list['26'] = 'Drôme';
$department_list['27'] = 'Eure';
$department_list['28'] = 'Eure et Loir';
$department_list['29'] = 'Finistère';
$department_list['30'] = 'Gard';
$department_list['31'] = 'Haute Garonne';
$department_list['32'] = 'Gers';
$department_list['33'] = 'Gironde';
$department_list['34'] = 'Hérault';
$department_list['35'] = 'Ille et Vilaine';
$department_list['36'] = 'Indre';
$department_list['37'] = 'Indre et Loire';
$department_list['38'] = 'Isère';
$department_list['39'] = 'Jura';
$department_list['40'] = 'Landes';
$department_list['41'] = 'Loir et Cher';
$department_list['42'] = 'Loire';
$department_list['43'] = 'Haute Loire';
$department_list['44'] = 'Loire Atlantique';
$department_list['45'] = 'Loiret';
$department_list['46'] = 'Lot';
$department_list['47'] = 'Lot et Garonne';
$department_list['48'] = 'Lozère';
$department_list['49'] = 'Maine et Loire';
$department_list['50'] = 'Manche';
$department_list['51'] = 'Marne';
$department_list['52'] = 'Haute Marne';
$department_list['53'] = 'Mayenne';
$department_list['54'] = 'Meurthe et Moselle';
$department_list['55'] = 'Meuse';
$department_list['56'] = 'Morbihan';
$department_list['57'] = 'Moselle';
$department_list['58'] = 'Nièvre';
$department_list['59'] = 'Nord';
$department_list['60'] = 'Oise';
$department_list['61'] = 'Orne';
$department_list['62'] = 'Pas de Calais';
$department_list['63'] = 'Puy de Dôme';
$department_list['64'] = 'Pyrénées Atlantiques';
$department_list['65'] = 'Hautes Pyrénées';
$department_list['66'] = 'Pyrénées Orientales';
$department_list['67'] = 'Bas Rhin';
$department_list['68'] = 'Haut Rhin';
$department_list['69'] = 'Rhône-Alpes';
$department_list['70'] = 'Haute Saône';
$department_list['71'] = 'Saône et Loire';
$department_list['72'] = 'Sarthe';
$department_list['73'] = 'Savoie';
$department_list['74'] = 'Haute Savoie';
$department_list['75'] = 'Paris';
$department_list['76'] = 'Seine Maritime';
$department_list['77'] = 'Seine et Marne';
$department_list['78'] = 'Yvelines';
$department_list['79'] = 'Deux Sèvres';
$department_list['80'] = 'Somme';
$department_list['81'] = 'Tarn';
$department_list['82'] = 'Tarn et Garonne';
$department_list['83'] = 'Var';
$department_list['84'] = 'Vaucluse';
$department_list['85'] = 'Vendée';
$department_list['86'] = 'Vienne';
$department_list['87'] = 'Haute Vienne';
$department_list['88'] = 'Vosges';
$department_list['89'] = 'Yonne';
$department_list['90'] = 'Territoire de Belfort';
$department_list['91'] = 'Essonne';
$department_list['92'] = 'Hauts de Seine';
$department_list['93'] = 'Seine St Denis';
$department_list['94'] = 'Val de Marne';
$department_list['95'] = 'Val d\'Oise';
$department_list['971'] = 'Guadeloupe';
$department_list['972'] = 'Martinique';
$department_list['973'] = 'Guyane';
$department_list['974'] = 'Réunion';
$department_list['976'] = 'Mayotte';
return $department_list;


function textSafe($data) //sécurise un string pour éviter l'injection de code à travers l'affichage d'un texte en php
{
    return htmlspecialchars($data, ENT_SUBSTITUTE, 'UTF-8');
}

function searchInput($search, $link1, $link2, $inputIdHidden = null) //formulaire pour la barre de recherche
{
    echo "<form action='$link1' method='get'>
        <div class='input-group mb-3'>
            <div class='form-floating'>
                <input type='text' class='form-control' placeholder='recherche un formulaire' name='search' id='search'>
                <label for='search'>Rechercher</label>";
    if ($inputIdHidden != null) {
        echo "<input type='hidden' name='id' value='$inputIdHidden'>";
    }
    echo "</div><button class='btn btn-outline-secondary fs-5' type='submit'><span class='fad fa-search'></span></button>";
    if (isset($search) and $search != "") {
        echo "<a href='$link2' class='btn btn-outline-danger text-center fs-4'><span class='fad fa-times-circle text-center'></span></a>";
    }
    echo "</div></form>";
}

function searchTitle($title, $search, $link1, $link2, $inputHidden = null) //affichage du titre de la page + "résultat de la requête"
{
    echo "<h1>" . $title . "</h1>";
    if (isset($search) and $search != "") {
        echo "<h2>Résultat de la recherche '" . textSafe($search) . "'</h2>";
    }
    searchInput($search, $link1, $link2, $inputHidden);
}

function jsFormValidatation() //javascript utilisé pour la validation des formulaires
{
    echo "(function () {
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                            event.stopPropagation()
                        }
                form.classList.add('was-validated')
                    }, false)
                })
        })();";
}

function modalButton($text, $color, $target) //bouton pour afficher une popup
{
    echo "<button type = 'button' class='btn btn-$color' data-bs-toggle='modal' data-bs-target = '#$target'>$text</button>";
}


function modalModificationData($id, $modify_page, $token, $form_input) //popup pour modifier un/des élément(s)
{
    echo "<div class='modal fade' id='modal_$id' data-bs-keyboard='false' tabindex='-1' data-bs-backdrop='static'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
<form action='../src/actions/$modify_page' class='needs-validation' method='post' novalidate>
        <div class='modal-header'>
            <h5 class='modal-title'>Modifier l'élément</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
            ".$form_input."
            <input type='hidden' name='token' value='$token'>
            <input type='hidden' name='id' value='$id'>
        </div>
        <div class='modal-footer'>
            <button type='button' class='btn btn-danger'
                    data-bs-dismiss='modal'>Annuler
            </button>
            <button type='submit' class='btn btn-success'>Modifier</button>
        </div>
    </form>
    </div></div></div>";
}

function modalDelete($id, $delete_page, $token,$warning_message="La suppression échouera si l'élément est utilisé dans des données") //popup pour supprimer un élément
{
    echo "<div class='modal fade' id='modal_delete_$id' data-bs-keyboard='false' tabindex='-1' data-bs-backdrop='static'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
        <form action='../src/actions/$delete_page' method='post'>
        <div class='modal-header'>
            <h5 class='modal-title'>Supprimer un élément</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
            <div class='form-floating'>
                <p class='text-danger'>Souhaitez-vous vraiment supprimer l'élément ?
                    <br><br>
                    <span class='far fa-exclamation-triangle'></span>
                    ".$warning_message."
                    <span class='far fa-exclamation-triangle'></span></p>
            </div>
            <input type='hidden' name='token' value='$token'>
            <input type='hidden' name='id' value='$id'>
        </div>
        <div class='modal-footer'>
            <button type='button' class='btn btn-danger'
                    data-bs-dismiss='modal'>Annuler
            </button>
            <button type='submit' class='btn btn-success'>Supprimer</button>
        </div>
    </form>
    </div></div></div>";
}

function getPost($args) //récupères toutes les données envoyées en post portant dont le nom est entrée en paramètre
{
    $result = [];
    foreach ($args as $varName) {
        $result[$varName] = filter_input(INPUT_POST, $varName);
    }
    return $result;
}

function checkDepartment($nbr_department){
    return preg_match("/^([02][1-9]|2[AB]|(1|[3-8])[0-9]|9[0-5]|97[12346])$/",$nbr_department);
}

function checkLenString($valueCheck, $length_max, $length_min = 1) //vérifie la longueur d'une chaîne de caractère
{
    return strlen($valueCheck) <= $length_max && strlen($valueCheck) >= $length_min;
}

function checkInt($value,$min,$max){ //vérifie la valeur d'un int
    return ($value>=$min and ($value<=$max or $max==0) and is_int($value));
}
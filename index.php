<?php
session_name("Session21nefontqu1"); #TODO : changer le nom ? A quoi sert le nom ? 
// ne pas mettre d'espace dans le nom de session !
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
// Décommenter la ligne suivante pour afficher le tableau $_SESSION pour le debuggage
// var_dump($_SESSION);

require("forms/utils.php");
require("forms/printForms.php");
require("contents/accueil.php");
require("database/database.php");
require("class/Utilisateur.php");


$dbh= Database::connect();

generateHTMLHeader("fourbiX", "css/style.css");
                    
generateNavBar(false);


if (!isset($_GET["page"]) || $_GET["page"]=="accueil"){
    printAccueil(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"], "accueil");
}

generateHTMLFooter();


?>


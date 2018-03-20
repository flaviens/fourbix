<?php
session_name("Session21nefontqu1"); #TODO : changer le nom ? A quoi sert le nom ? 
// ne pas mettre d'espace dans le nom de session !
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
// Décommenter la ligne suivante pour afficher le tableau $_SESSION pour le debuggage
var_dump($_SESSION);

require("forms/utils.php");
require("forms/printForms.php");
require("database/database.php");
require("class/Utilisateur.php");
require("class/Item.php");
require("forms/logInOut.php");


$askedPage = isset($_GET['page']) ? $_GET['page'] : 'accueil';
$authorized = checkPage($askedPage);
$pageTitle = $authorized ? getPageTitle('askedPage') : 'Erreur';

$dbh = Database::connect();

generateHTMLHeader("fourbiX", "css/style.css");

if (isset($_GET["todo"])){
    if ($_GET["todo"]=="login"){
        logIn($dbh);
    } else if ($_GET["todo"]=="logout"){
        logOut();
    }
}

generateNavBar($dbh, isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]);

?>

<div id="content">
	<?php 
		if($authorized)
			require("contents/$askedPage.php");
		else
			require("contents/erreur.php");
	?>
</div>

<?php

/*if (!isset($_GET["page"]) || $_GET["page"]=="accueil"){
    printAccueil(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"], "accueil");
}*/

generateHTMLFooter();

$dbh=null;
?>


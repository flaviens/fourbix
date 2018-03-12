<?php
function generateHTMLHeader($titre, $linkCSS) {

    echo <<<CHAINE_DE_FIN
<!DOCTYPE html>
<html>         
   <head>
        <meta charset="UTF-8"/>
    <!-- CSS Bootstrap -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="$linkCSS">
        <title>$titre</title>
    </head>
        <body>
            
CHAINE_DE_FIN;
}

function generateHTMLFooter(){
    echo "</body>
</html>";
}

#Doc : génère la barre de navigation différemment en fonction de si l'utilisateur est connecté ou pas.
function generateNavBar($isLogged){ //TODO genere la navBar
    if (!$isLogged){
        echo <<< CHAINE_DE_FIN
    <!-- Static navbar -->
    <div class="navbar navbar-default" role="navigation" style=margin:2px>
        <div class="container-fluid">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Accueil</a></li>
                    <li><a href="#">Catalogue</a></li>
                    <li style=margin-top:10px>
                    <form class="form-inline" method="post">
    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button></li>
    </form>
                    <li><a href="#">Connexion</a></li>

                </ul>
            </div>
        </div>
    </div>
CHAINE_DE_FIN;
    } else{
            echo <<< CHAINE_DE_FIN
    <!-- Static navbar -->
    <div class="navbar navbar-default" role="navigation" style=margin:2px>
        <div class="container-fluid">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Accueil</a></li>
                    <li><a href="#">Catalogue</a></li>
                    <li style=margin-top:10px>
                    <form class="form-inline" method="post" action="index.php?page=search">
    <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button>
    </form></li>
        <li><a href="#">Mes binets</a></li>
        <li><a href="#">Demande</a></li>
        <li><a href="index.php?todo=logout&page=accueil">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </div>
CHAINE_DE_FIN;
    }
}

$page_list = array(
    array(
        "name"=>"accueil",
        "title"=>"Accueil de notre site",
        "menutitle"=>"Accueil"),
    array(
        "name"=>"catalogue",
        "title"=>"Catalogue",
        "menutitle"=>"Catalogue"),
    array(
        "name"=>"binets",
        "title"=>"Binets",
        "menutitle"=>"Mes Binets"),
    array(
        "name"=>"search",
        "title"=>"Recherche de matériel",
        "menutitle"=>"Recherche"),


);

function checkPage($askedPage){
    global $page_list;
    foreach ($page_list as $page) {
        if($page['name'] == $askedPage)
            return true;
    }
    return false;
}

function getPageTitle($askedPage){
    global $page_list;
    foreach ($page_list as $page) {
        if($page['name'] == $askedPage)
            return $page['title'];
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>


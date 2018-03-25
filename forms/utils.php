<?php
function generateHTMLHeader($titre, $linkCSS) {

    echo <<<CHAINE_DE_FIN
<!DOCTYPE html>
<html>         
   <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS Bootstrap -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="$linkCSS">
        <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="js/code.js"></script>
        <script type="text/javascript" src="js/sorttable.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
            <script type="text/javascript" src="bootstrap/js/jquery.js"></script>
            <script type="text/javascript" src="bootstrap/js/npm.js"></script>
        <title>$titre</title>
    </head>
        <body>
            
CHAINE_DE_FIN;
}

function generateHTMLFooter(){
    echo <<< CHAINE_DE_FIN
    </body>
    <footer class="page-footer font-small pt-4 mt-4">
        <div class="footer-copyright py-3 text-center">
            Ce site a été réalisé en 2018 par des X2016 en modal Web.
    </footer>
</html>
CHAINE_DE_FIN;
}

#Doc : génère la barre de navigation différemment en fonction de si l'utilisateur est connecté ou pas.
function generateNavBar($dbh, $isLogged){ //TODO genere la navBar
    if (!$isLogged){
        echo <<< CHAINE_DE_FIN
    <!-- Static navbar -->
    <nav class="navbar navbar-default" role="navigation" style="margin:2px">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    <span class="sr-only">Toggle navitagion</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">FroubiX</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php?page=accueil">Accueil</a></li>
                    <li><a href="index.php?page=catalogue">Catalogue</a></li>
                    <li style=margin-top:10px>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
CHAINE_DE_FIN;
    } else{
        $isAdmin= Utilisateur::isAdmin($dbh, $_SESSION["login"]);
        
            echo <<< CHAINE_DE_FIN
    <!-- Static navbar -->
    <nav class="navbar navbar-default" role="navigation" style="margin:2px">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    <span class="sr-only">Toggle navitagion</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">FroubiX</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav navbar-left">
                    <li class="active"><a href="index.php?page=accueil">Accueil</a></li>
                    <li><a href="index.php?page=catalogue">Catalogue</a></li>
                    <li style=margin-top:10px>
                    <form class="form-inline" method="post" action="index.php?page=search">
                        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button>
                    </form></li>
                    <li><a href="#">Mes binets</a></li>
                    <li><a href="#">Demande</a></li>
CHAINE_DE_FIN;

            if ($isAdmin){
                echo <<< CHAINE_DE_FIN
                    <li><a href="index.php?page=administration">Administration</a></li>
CHAINE_DE_FIN;
            }
            
            echo <<< CHAINE_DE_FIN
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php?todo=logout&page=accueil"><span class="glyphicon glyphicon-log-out"></span> Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
CHAINE_DE_FIN;
    }
}

$page_list = array(
    array(
        "name" => "accueil",
        "title" => "Accueil",
        "menutitle" => "Accueil",
        "loggedIn" => false),
    array(
        "name" => "catalogue",
        "title" => "Catalogue",
        "menutitle" => "Catalogue",
        "loggedIn" => false),
    array(
        "name" => "binets",
        "title" => "Binets",
        "menutitle" => "Mes Binets",
        "loggedIn" => false),
    array(
        "name" => "search",
        "title" => "Recherche de matériel",
        "menutitle" => "Recherche",
        "loggedIn" => true),
    array(
        "name" => "demande",
        "title" => "Mes demandes",
        "menutitle" => "Demande",
        "loggedIn" => false),
    array(
        "name" => "administration",
        "title" => "Panneau d'administration",
        "menutitle" => "Administration",
        "loggedIn" => true),
    array("name" => "inscription",
        "title" => "Inscription",
        "menutitle" => "S'inscrire",
        "loggedIn" => false),
    array("name" => "stock",
        "title" => "Stock",
        "menutitle" => "Stock",
        "loggedIn" => true)
);

function checkPage($askedPage, $logged){
    global $page_list;
    foreach ($page_list as $page) {
        if($page['name'] == $askedPage){
            if($page['loggedIn'] and !$logged)
                return false;
            return true;
        }
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


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
        <link rel="icon" type="image/x-icon" href="images/logo/favicon.ico" />
        <link rel="icon" type="image/png" href="images/logo/favicon.png" />
        <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="js/code.js"></script>
        <script type="text/javascript" src="js/sorttable.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="bootstrap/js/jquery.js"></script>
        <script type="text/javascript" src="bootstrap/js/npm.js"></script>
        <title>$titre</title>
    </head>
        <body>
            <div class='upperNavBar'>
            
            </div>
            
CHAINE_DE_FIN;
}

function generateHTMLFooter(){
    echo <<< CHAINE_DE_FIN
    </body>
    <footer class="page-footer font-small pt-4 mt-4">
        <div class="footer-copyright py-3 text-center">
            Ce site a été réalisé en 2018 par des X2016 en modal Web.
        </div>
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
                <a class="navbar-brand titreSite" href="index.php">FourbiX</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li><a href="index.php?page=accueil">Accueil</a></li>
                    <li style=margin-top:10px>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php?page=inscription"><span class="glyphicon glyphicon-user"></span> S'inscrire</a></li>
                    <li><a href="index.php?page=accueil"><span class="glyphicon glyphicon-log-in"></span> Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
CHAINE_DE_FIN;
    } else{
        $login= htmlspecialchars($_SESSION["login"]);
        $isAdmin= Utilisateur::isAdmin($dbh, $login);
        $binets= Binet::generateBinetsByMember($dbh, $login);
        
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
                <a class="navbar-brand titreSite" href="index.php">FourbiX</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="index.php?page=accueil">Accueil</a></li>
                    <li><a href="index.php?page=catalogue">Catalogue</a></li>
                    <li style=margin-top:10px>
                    <form class="form-inline" method="get" action="index.php">
                        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Cherchez un item" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" value="search" name="page"><span class="glyphicon glyphicon-search"></span></button>
                    </form></li>
                    <li class='dropdown'><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mes binets<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        
CHAINE_DE_FIN;
            $i=0;
            foreach ($binets as $binet) {
                $nom= htmlspecialchars($binet->binet);
                if ($i>0) echo '<li role="separator" class="divider"></li>' ;
                echo <<< CHAINE_DE_FIN
                <li>
                    <form class="navbar-form" method=get action=index.php?>
                        <input type='hidden' name=pageBinet value="$nom">
                        <input type='hidden' name="page" value="binet">
                        <button type="submit" class="btn btn-default dropdownFont">$nom</button>
                    </form>
                </li>
                
CHAINE_DE_FIN;
                $i++;
            }
            
            echo <<< CHAINE_DE_FIN
                        </ul>
                    </li>
                    <li><a href="index.php?page=demandes">Demandes</a></li>
CHAINE_DE_FIN;

            if ($isAdmin){
                echo <<< CHAINE_DE_FIN
                    <li><a href="index.php?page=administration">Administration</a></li>
CHAINE_DE_FIN;
            }
            $prenom = Utilisateur::getUtilisateur($dbh, $login)->prenom;
            echo <<< CHAINE_DE_FIN
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php?page=utilisateur"><span class="glyphicon glyphicon-user"></span> $prenom</a></li>
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
    array( //NE PAS SUPPRIMER !!
        "name" => "binet",
        "title" => "Binet",
        "menutitle" => "Page Binet",
        "loggedIn" => false),
    array(
        "name" => "search",
        "title" => "Recherche de matériel",
        "menutitle" => "Recherche",
        "loggedIn" => true),
    array(
        "name" => "demandes",
        "title" => "Mes demandes",
        "menutitle" => "Demandes",
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
        "loggedIn" => true),
    array("name" => "item",
        "title" => "Inventaire",
        "menutitle" => "Inventaire",
        "loggedIn" => true),
    array("name" => "utilisateur",
        "title" => "Utilisateur",
        "menutitle" => "Votre page personnelle",
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


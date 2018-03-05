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
                    <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button></li>
        <li><a href="#">Mes binets</a></li>
                    <li><a href="#">Déconnexion</a></li>
  </form>
                </ul>
            </div>
        </div>
    </div>
CHAINE_DE_FIN;
    }
}



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>


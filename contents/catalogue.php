<?php

function printHeaderPage(){
    echo <<< CHAINE_DE_FIN
    <div class="container">
    <div class="jumbotron">
        <h1>Catalogue</h1>
        <p>Consultez ici le matériel que mettent à disposition les binets !</p>
    </div>
</div>
CHAINE_DE_FIN;
}

function printRechercheForm(){
    echo <<< CHAINE_DE_FIN
    <div class="container">
     <div class="panel panel-info">
            <div class="panel-heading">Quels binets vous intéressent ?</div>
            <div class="panel-body">
        <form class="form-inline" method="post" id="rechercheBinetsCatalogue">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="searchBinets">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button></li>
        </form>
    </div>
    </div>
    </div>
CHAINE_DE_FIN;
}

function resultSearch($dbh){
    if (isset($_POST["searchBinets"]) && strlen($_POST["searchBinets"])>0){
    $binets=Binet::getBinetResearchFunction($dbh, $_POST["searchBinets"]);
} else {
    $binets=array();
}
return $binets;
}

function printBinets($binets){ //TODO : imprimer ce qu'on a dans notre tableau : un gros tableau avec le binet + image, puis avec un caroussel sympa des objets de ce binet
   
    
}


printHeaderPage();

printRechercheForm();

$binets=resultSearch($dbh);

printBinets($binets);


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


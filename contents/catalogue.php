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

function getItemsFromBinets($dbh, $nomBinet){
        $query="SELECT `item`, `description`, `image` FROM `stock` WHERE `binet`=?";
        $sth=$dbh->prepare();
        $sth->execute(array($nomBinet));
        $items=array();
        $i=0;
        while ($item=$sth->fetch()){
            $items[$i]= clone $item;
            $i++;
        }
        return $items;
    }

function printBinets($dbh, $binet){
   $query="SELECT image FROM `binets` WHERE `nom`=?";
   $sth=$dbh->prepare($query);
   $sth->excute(array($binet.nom));
   $imageBinet=$sth->fetch();
   
   echo <<< CHAINE_DE_FIN
   
   
CHAINE_DE_FIN;
   
}


function printAllBinets($dbh, $binets){ //TODO : imprimer ce qu'on a dans notre tableau : un gros tableau avec le binet + image, puis avec un caroussel sympa des objets de ce binet
   echo <<< CHAINE_DE_FIN
    <div class="container">
     <div class="panel panel-info">
            <div class="panel-heading">Binets</div>
            <div class="panel-body">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <th scope="col" >Binet</th>
            <th scope="col" >Ce qu'on a à vous proposer !</th>
        </thead>
CHAINE_DE_FIN;

   
    echo <<< CHAINE_DE_FIN
    </table>
    </div>
    </div>
    </div>
CHAINE_DE_FIN;
    
}


printHeaderPage();

printRechercheForm();

$binets=resultSearch($dbh);

printBinets($dbh, $binets);


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


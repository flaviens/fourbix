<?php

function printHeaderPage(){
    echo <<< CHAINE_DE_FIN
    <div class="container">
    <div class="jumbotron">
        <img src='images/logo/catalogue-logo.png' alt='catalogue-logo.png' class='pageLogo'>
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
        <form class="form-inline" method="get" id="rechercheBinetsCatalogue">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="searchBinets">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="page" value="catalogue"><span class="glyphicon glyphicon-search"></span></button></li>
        </form>
    </div>
    </div>
    </div>
CHAINE_DE_FIN;
}

function resultSearch($dbh){
  if (isset($_GET["searchBinets"]) && strlen($_GET["searchBinets"])>0){
    $binets=Binet::getBinetResearchFunction($dbh, $_GET["searchBinets"]);
  } else {
    $binets=array();
  }
  return $binets;
}

function printBinets($dbh, $binet, $indexCarousel){
   $query="SELECT image FROM `binets` WHERE `nom`=?";
   $sth=$dbh->prepare($query);
   $sth->execute(array($binet->nom));
   $imageBinet=$sth->fetch();
   $imageBinet=$imageBinet["image"];
   
   echo <<< CHAINE_DE_FIN
   <tr><th scope='row' height=400>
   <span style="text-align:center">$binet->nom </span> <br/> <img src='images/binets/$imageBinet' alt='$imageBinet' class='image-binet-catalogue' />
           <form action=index.php method=get>
           <p>
           <input type="hidden" name="page" value="binet">
           <input type="hidden" name="pageBinet" value="$binet->nom"></p>
           <p style="text-align:center">
           <input type=submit class="btn btn-primary" value="Voir la page">
           </p>
           </form>
   </th>      
CHAINE_DE_FIN;
   echo "<td>";
   $items = Item::getItemsFromBinetsWithImage($dbh, $binet->nom);
   $length = sizeof($items);
   
   
   echo <<< CHAINE_DE_FIN
   <div id="myCarousel$indexCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
<ol class="carousel-indicators">
CHAINE_DE_FIN;
   
   for ($j=0; $j<$length; $j++){
       if ($j==0){
           echo "<li data-target='#myCarousel$indexCarousel' data-slide-to='0' class='active'></li>";
       }else{
           echo <<< CHAINE_DE_FIN
       <li data-target="#myCarousel$indexCarousel" data-slide-to="$j"></li>
CHAINE_DE_FIN;
       }
   }
   
          echo <<< CHAINE_DE_FIN
         </ol>
     <div class="carousel-inner">
CHAINE_DE_FIN;
   
    $i=0;
   foreach ($items as $item) { //each item is an Item object
    $imageMatos=$item->image;
    $descriptionMatos=$item->description;
    if ($i==0){ //TODO : la description peut être améliorée : si elle est trop longue, la couper.
        echo <<< CHAINE_DE_FIN
      <!-- Wrapper for slides -->
    <div class="item active">
      <img src="images/items/$imageMatos" alt="$imageMatos" class="image-caroussel">
      <div class="carousel-caption">
        <h3><a href='index.php?page=item&id=$item->id'>$item->nom</a></h3>
        <p>$descriptionMatos</p>
      </div>
    </div>

CHAINE_DE_FIN;
    } else{
        echo <<< CHAINE_DE_FIN
      <!-- Wrapper for slides -->
    <div class="item">
      <img src="images/items/$imageMatos" alt="$imageMatos" class="image-caroussel">
        <div class="carousel-caption">
        <h3><a href='index.php?page=item&id=$item->id'>$item->nom</a></h3>
        <p>$descriptionMatos</p>
      </div>
    </div>

CHAINE_DE_FIN;
    }
      
   $i++;
   }
   echo <<< CHAINE_DE_FIN
     <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel$indexCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel$indexCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
   </td>
CHAINE_DE_FIN;
   
}


function printAllBinets($dbh, $binets){ 
  echo '<div class="container">';
  echo "<h4 style='text-align:center'> Voici les binets trouvés pour : <i>\"" . htmlspecialchars($_GET["searchBinets"]) . "\"</i></h4>";
  echo <<< CHAINE_DE_FIN
     <div class="panel panel-info">
            <div class="panel-heading">Binets</div>
            <div class="panel-body">
    <table class="table table-striped table-bordered sortable" style="table-layout:fixed">
        <thead class="thead-dark" style="texte-align:center">
            <th scope="col" width=170px>Binet</th>
            <th scope="col">Ce qu'on propose</th>
        </thead>
        <tbody>
CHAINE_DE_FIN;
   $i=0; //numéro pour le carousel
   foreach ($binets as $binet) {
       //var_dump($binet);
       if ($binet->nom!="Administrateurs"){ //Les administrateurs ne prêtent rien.
        printBinets($dbh, $binet, $i);
       }
       $i++;
   }
   
    echo <<< CHAINE_DE_FIN
   </tbody>
    </table>
    </div>
    </div>
    </div>
CHAINE_DE_FIN;
    
}


printHeaderPage();

printRechercheForm();

$binets=resultSearch($dbh);

if ($binets!=NULL){
    printAllBinets($dbh, $binets);
}
else if (isset($_GET["searchBinets"]) && strlen($_GET["searchBinets"])>0){
    echo "<h4 style='text-align:center'> Votre recherche pour <i>\"" . htmlspecialchars($_GET["searchBinets"]) . "\"</i> n'a rien donné ! Désolé...</h4>";
}



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


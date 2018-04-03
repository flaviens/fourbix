<?php

if (strlen($_GET["search"])>0){
    //$items=Item::getItemResearchFunction($dbh, $_POST["search"]);
    $items=Item::getItemMultipleResearch($dbh, $_GET["search"]);
} else {
    $items=array();
}

//echo var_dump($items);

function printItem($dbh, $item){ //TODO : génère le format créé par un objet. Le format a generer doit être : le nom de l'objet, sa marque si non NULL, son type si non NULL, l'image de l'objet, la description, le binet qui le prête si offre=true, le stock si isstockpublic=true
    //selectionner dans la base de donnee les sotcks disponibles
    $query="SELECT image FROM `binets` WHERE `nom`=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($item->binet));
    $imageBinet=$sth->fetch();
    $sth->closeCursor();
    //var_dump($resultat);
    //var_dump($imageBinet);
    if ($item->offre){
        echo"<tr><th scope='row'><a href='index.php?page=item&id={$item->id}'>";
        echo htmlspecialchars($item->nom);
        echo "</a></th> <td>";
        echo htmlspecialchars($item->marque);
        echo "</td><td>";
        echo htmlspecialchars($item->type);
        echo "</td><td>";
            echo "<img src=images/items/";
            echo htmlspecialchars($item->image);
            echo " alt='";
            echo htmlspecialchars($item->image);
            echo "' class='image-item-search'/>";
        echo "</td><td class='description-search'>";
        echo htmlspecialchars($item->description);
        echo "</td><td style='text-align:center'><a href='index.php?page=binet&pageBinet=" . htmlspecialchars($item->binet) . "'>";
        echo htmlspecialchars($item->binet);
            echo "</a><br /><a href='index.php?page=binet&pageBinet=" . htmlspecialchars($item->binet) . "'><img src='images/binets/";
            echo htmlspecialchars($imageBinet["image"]);
            echo "' alt='";
            echo htmlspecialchars($imageBinet["image"]);
            echo "' class='image-binet-search'/></a>";
        echo "</td><td>";
        if ($item->isstockpublic){
            echo htmlspecialchars($item->quantite);
            echo "</td><td>";
        } else {
            echo "Non renseigné</td><td>";
        }
        if (strlen($item->caution)>0){
            echo htmlspecialchars($item->caution);
            echo " &euro;</td>";
        }else {
            echo "Non renseigné</td>";
        }
        echo "</tr>";
    }
    $sth->closeCursor();
}

//mettre dans Binet le nom du binet et l'image
echo <<< CHAINE_DE_FIN

<div class="container">
    <div class="jumbotron">
    <img src='images/logo/recherche-logo.png' alt='recherche-logo.png' class='pageLogo'>
        <h1>Recherche</h1>
        <p>Recherchez ce dont vous avez besoin facilement !</p>
    </div>
</div>
CHAINE_DE_FIN;


//var_dump($items);
if (sizeof($items)>0){
    echo '<div class="container">';
    echo "<h4 style='text-align:center'> Voici les items trouvés pour : <i>\"" . htmlspecialchars($_GET["search"]) . "\"</i></h4>";
    echo <<< CHAINE_DE_FIN
    <table class="table table-striped table-bordered sortable">
        <thead class="thead-dark">
            <th scope="col" >Nom</th>
            <th scope="col" >Marque</th>
            <th scope="col" >Type</th>
            <th scope="col" >Image</th>
            <th scope="col" >Description</th>
            <th scope="col" >Binet</th>
            <th scope="col" >Stock disponible</th>
            <th scope="col" >Caution</th>
        </thead>
        <tbody>

CHAINE_DE_FIN;
    foreach ($items as $item){
        echo printItem($dbh, $item);
    }

    echo "</tbody>"
    .    "</table>"
    ."</div>";
} else{
    if (strlen($_GET["search"])>0){
        echo "<h4 style='text-align:center'> Votre recherche pour <i>\"" . htmlspecialchars($_GET["search"]) . "\"</i> n'a rien donné ! Désolé...</h4>";
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


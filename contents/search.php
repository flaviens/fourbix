<?php

if (strlen($_POST["search"])>0){
    $items=Item::getItemResearchFunction($dbh, $_POST["search"]);
} else {
    $items=array();
}

echo var_dump($items);

function printItem($dbh, $item){ //TODO : génère le format créé par un objet. Le format a generer doit être : le nom de l'objet, sa marque si non NULL, son type si non NULL, l'image de l'objet, la description, le binet qui le prête si offre=true, le stock si isstockpublic=true
    //selectionner dans la base de donnee les sotcks disponibles
    $query="SELECT * FROM `stock` WHERE `item`=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($item->id));
    $resultat=$sth->fetch();
    $query="SELECT image FROM `binets` WHERE `nom`=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($resultat["binet"]));
    $imageBinet=$sth->fetch();
    var_dump($resultat);
    var_dump($imageBinet);
    if ($resultat["offre"]){
        echo"<tr><th scope='row'>";
        echo $item->nom;
        echo "</th> <td>";
        echo $item->marque;
        echo "</td><td>";
        echo $item->type;
        echo "</td><td>";
            echo "<img src=images/items/";
            echo $resultat["image"];
            echo " alt='";
            echo $resultat["image"];
            echo "'/>";
        echo "</td><td>";
        echo $resultat["description"];
        echo "</td><td style='text-align:center'>";
        echo $resultat["binet"];
            echo "<br /><img src=images/binets/";
            echo $imageBinet["image"];
            echo " alt='";
            echo $imageBinet["image"];
            echo "'/>";
        echo "</td><td>";
        if ($resultat["isstockpublic"]){
            echo $resultat["quantite"];
            echo "</td><td>";
        } else {
            echo "Non renseigné</td><td>";
        }
        if (strlen($resultat["caution"])>0){
            echo $resultat["caution"];
            echo "€</td>";
        }else {
            echo "Non renseigné</td>";
        }
        echo "</tr>";
    }
}

//mettre dans Binet le nom du binet et l'image
echo <<< CHAINE_DE_FIN
    <table class="table table-striped table-bordered">
        <thead>
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
//var_dump($items);
echo printItem($dbh, $items);

echo "</tbody>";
echo "</table>";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


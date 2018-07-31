<?php

//TODO : rajouter une requête BDD pour connaître le rôle de la personne dans le binet.
//Division en 3 : pas membre du binet, inventory manager ou Admin. Si Inventory manager, ajustage des quantités possible. Si Admin, panneau d'administration pour rajouter des personnes dans le binet comme Inventory Manager.

function printHeaderPage($binet, $image){ //TODO : rajouter l'image du binet ?
    echo <<< CHAINE_DE_FIN
    <div class="container">
    <div class="jumbotron">
            <img src="images/binets/$image" alt=\"$image\" class='pageLogo'>
            <h1>$binet</h1>
        <p>Consultez ici ce que ce binet souhaite vous proposer.</p>
    </div>
CHAINE_DE_FIN;
}

function genereLeaderBoard($dbh, $binet){
    echo <<< CHAINE_DE_FIN
        <div class="panel panel-info toBeClicked">
            <div class="panel-heading isClickable center"><span class="glyphicon glyphicon-home"></span> Leaderboard</div>
            <div class="panel-body toBeToggled">
    
    
CHAINE_DE_FIN;
    
    genereRolesLeaderboard($dbh, $binet);
    
    
    echo <<< CHAINE_DE_FIN
    </div></div>
CHAINE_DE_FIN;
}

function genereRolesLeaderboard($dbh, $binet){
    $query="SELECT * FROM `role`;";
    $sth=$dbh->prepare($query);
    $sth->execute();
    $rolesToBePrinted=array("admin" => "Administrateurs", "matosManager" => "Respo Matériel", "membre" => "Membres");
    $userQueue=array();
    while($roles=$sth->fetch()){
        $role= htmlspecialchars($roles['nom']);
        $query="SELECT `utilisateur` FROM `membres` WHERE `binet`=? AND `role`=? GROUP BY `utilisateur`;";
        $sth2=$dbh->prepare($query);
        $sth2->execute(array(htmlspecialchars($binet), $role));
        $roleToBePrinted=$rolesToBePrinted[$role];
        
        echo <<< CHAINE_DE_FIN
        <div class="panel panel-info">
            <div class="panel-heading center roleLeaderboard"><h3>$roleToBePrinted</h3></div>
            <div class="panel-body">
CHAINE_DE_FIN;
        while ($user=$sth2->fetch()){
            $login= htmlspecialchars($user['utilisateur']);
            if (!in_array($login, $userQueue)){
                array_push($userQueue, $login);
                $query="SELECT `nom`, `prenom` FROM `utilisateurs` WHERE `login`=?";
                $sth3=$dbh->prepare($query);
                $sth3->execute(array($login));
                $resultat=$sth3->fetch();
                $nom=$resultat['nom'];
                $prenom=$resultat['prenom'];
                echo <<< CHAINE_DE_FIN
            <span class="glyphicon glyphicon-asterisk"></span> $prenom $nom (<span style="font-style:italic">$login</span>)<br/>
CHAINE_DE_FIN;
            }
        }
        echo "</div></div>";
    }
}

function genereRolesChoices($dbh){
    $sth=$dbh->prepare("SELECT `nom` FROM `role`");
    $sth->execute();
    while($role=$sth->fetch()){
        $toPrint=$role['nom'];
        echo "<option>$toPrint</option>";
    }
       
    $sth->closeCursor();
}

function genereTableDelete($dbh, $binet){
    $query="SELECT DISTINCT `utilisateur`, `role` FROM `membres` WHERE `binet`=?";
    $sth=$dbh->prepare($query);
    $sth->execute(array($binet));
    while ($tableContent = $sth->fetch()){
        $nomUtilisateur=$tableContent['utilisateur'];
        $nomRole=$tableContent['role'];
        echo <<< CHAINE_DE_FIN
        <tr>
                 <th scope="row">
                    $nomUtilisateur
                </th>
                <td>
                    $nomRole
                </td>
                <td>
                    <form action="index.php?page=binet&pageBinet=$binet" method=post>
                    <input type="hidden" name="loginRoleBinetDelete" value="$nomUtilisateur">
                    <input type="hidden" name="roleDelete" value="$nomRole">
                    <button type=submit class="btn btn-danger toBeWarnedDelete" style="text-align:center" onclick="return confirm('Confirmer la suppression.');"><span class="glyphicon glyphicon-remove"></span></button>
                    </form>
                </td>
        </tr>
CHAINE_DE_FIN;
    }
}

function printAdministration($dbh, $binet){
if (isset($_POST["loginRoleBinetAdd"])) $loginRole=$_POST["loginRoleBinetAdd"];
else $loginRole="''";

    echo <<< CHAINE_DE_FIN
<div class="panel panel-warning toBeClicked">
            <div class="panel-heading isClickable" style="text-align:center"><span class="glyphicon glyphicon-cog"></span> Administration des rôles</div>
            <div class="panel-body toBeToggled">
    <div class='row '>
        <div class='col-md-6 gris'>
            <div class="panel panel-success">
            <div class="panel-heading center"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter un rôle</div>
            <div class="panel-body">
                <form action="index.php?page=binet&pageBinet=$binet" method=post>
 <p>
  <label for="loginRole">login : </label>
  <input class="form-control" id="loginRole" type=text name=loginRoleBinetAdd value=$loginRole required>
 </p>

 <p>
     <label for="role">Role : </label>
      <select id="role" name=role class="form-control">
    
CHAINE_DE_FIN;

genereRolesChoices($dbh);

echo <<< CHAINE_DE_FIN
    </select>
 </p>
   
 <input type=submit class="btn btn-success" value="Ajouter le rôle">
 </form>
</div></div></div>
CHAINE_DE_FIN;

//TODO:suppression de rôle. Permet de virer quelqu'un.
echo<<< CHAINE_DE_FIN
    <div class='col-md-6 gris'>
            <div class="panel panel-danger">
            <div class="panel-heading center"><span class="glyphicon glyphicon-minus-sign"></span>Retirer un rôle</div>
            <div class="panel-body">
<table class="table table-striped table-bordered sortable" style="table-layout:fixed">
        <thead class="thead-dark">
            <th scope="col">login</th>
            <th scope="col">role</th>
            <th scope="col">delete ?</th>
        </thead>
        <tbody>
CHAINE_DE_FIN;

genereTableDelete($dbh, $binet);

echo "</tbody></table>";

echo"</div></div></div></div></div></div>";
}

function AddRole($dbh, $login, $binet, $role){
        $sth=$dbh->prepare("SELECT `login` FROM `utilisateurs` WHERE `login`=?;");
        $sth->execute(array($login));
        if ($sth->rowCount()==1){
            $sth->closeCursor();
            $sth=$dbh->prepare("INSERT INTO `membres` (`id`, `utilisateur`, `binet`, `role`) VALUES (NULL, ?, ?, ?)");
            $resultat=$sth->execute(array($login, $binet, $role));
            $sth->closeCursor();
            return $resultat;   
    } else return false;
}

function DeleteRole($dbh, $login, $binet, $role){
    $query="DELETE FROM `membres` WHERE `utilisateur`=? AND `binet`=? AND `role`=?";
    $sth=$dbh->prepare($query);
    $resultat=$sth->execute(array($login, $binet, $role));
    return $resultat;
}

function printItemsUser($dbh, $binet){
    $items = Item::getItemsFromBinets($dbh, $binet);
    $nothingToShow = true;
    echo "<div class='panel panel-primary'><div class='panel-heading center'>Inventaire</div>";
    echo "<ul class='list-group'>";
    foreach ($items as $item) {
        if($item->offre){
            $nothingToShow = false;
            echo "<li class='list-group-item'><div class='media'><div class='media-left media-middle' style='text-align: center;'>";
            echo "<a href='index.php?page=item&id={$item->id}'><img src=\"images/items/" . htmlspecialchars($item->image) . "\" alt=\"images/items/".htmlspecialchars($item->image)."\" style='max-width: 150px; max-height: 150px;'/></a><br/>";
            echo "</div><div class='media-body'>";
            echo "<h4 class='media-heading'><a href='index.php?page=item&id={$item->id}'>" . htmlspecialchars($item->nom) . "</a></h4>";
            echo "<div class='container-fluid'><div class='row'>";
            echo "<div class='col-md-4 col-sm-4'><p style='text-align: justify'>" . htmlspecialchars($item->description) . "</p></div>";
            echo "<div class='col-md-4 col-sm-4'><p><label>Type :</label> " . htmlspecialchars($item->type) . "</p>";
            echo "<p><label>Marque :</label> " . htmlspecialchars($item->marque) . "</p></div>";
            echo "<div class='col-md-4 col-sm-4'>";
            if ($item->isstockpublic)
                echo "<p><label>Quantité disponible :</label> " . htmlspecialchars($item->quantite) . "</p>";
            if (strlen($item->caution)>0)
                echo "<p><label>Caution :</label> " . htmlspecialchars($item->caution) . " &euro;</p></div>";
            else
                echo "<p><label>Caution :</label> Non renseigné ou sans caution.</p></div>";
            echo "</div></div>";
            echo "</div>";
        }
    }
    if($nothingToShow)
        echo "<li class='list-group-item'><h4 style='text-align: center; font-style: italic'>Ce binet n'a pas d'items à afficher...</h4></li>";
    echo "</ul></div>";
}

function printItemsManager($dbh, $binet){
    $items = Item::getItemsFromBinets($dbh, $binet);
    $nothingToShow = true;
    echo "<div class='panel panel-primary'><div class='panel-heading center'>Inventaire</div>";
    echo "<ul class='list-group'>";
    foreach ($items as $item) {
        $nothingToShow = false;
        echo "<li class='list-group-item'><div class='media'>";
        echo "<div class='media-left media-middle' style='text-align: center;'><img src=\"images/items/" . htmlspecialchars($item->image) . "\" alt=\"images/items/".htmlspecialchars($item->image)."\" class='image-item-Manager'/>";
        echo "<a href='index.php?page=item&id={$item->id}' class='btn btn-primary' style='margin:5px'><span class='glyphicon glyphicon-book'></span> Voir la page</a><br/>";
        echo "<form action=\"index.php?page=binet&pageBinet=" . htmlspecialchars($binet) . "\" method='post'>";
        echo "<button type='submit' class='btn btn-danger' name='itemDeleteID' value='{$item->id}'><span class='glyphicon glyphicon-trash'></span> Supprimer</button></form>";
        echo "</div><div class='media-body'><div class='container-fluid'><div class='row'><form action=\"index.php?page=binet&pageBinet=" . htmlspecialchars($binet) . "\" method='post'>";
        echo "<div class='col-md-4 col-sm-4'><p><input type='text' name='nom' value=\"" . htmlspecialchars($item->nom) . "\" class='form-control' required></p>";
        echo "<p><label for='description'>Description :</label><textarea class='form-control' name='description' id='description' rows='5' required/>" . htmlspecialchars($item->description) . "</textarea></p></div>";
        echo "<div class='col-md-4 col-sm-4'><p><label for='type'>Type :</label><select class='form-control' id='type' name='type' required>";
        generateTypesSelected($dbh, $item->type);
        echo "</select></p";
        echo "<p><label for='marque'>Marque :</label> <input type='text' name='marque' value=\"" . htmlspecialchars($item->marque) . "\" class='form-control' required></p>";
        echo "<p><label>Offre publique ?</label> <label class='radio-inline'><input type='radio' value='oui' name=isOfferPublic id='OfferPublic' checked>Oui</label>";
        echo "<label class='radio-inline'><input type='radio' value='non' name=isOfferPublic id='OfferPrive'>Non</label></p>";
        echo "<p><label>Stock public ?</label> <label class='radio-inline'><input type='radio' value='oui' name=isStockPublic id='StockPublic' checked>Oui</label>";
        echo "<label class='radio-inline'><input type='radio' value='non' name=isStockPublic id='StockPrive'>Non</label></p></div>";
        echo "<div class='col-md-4 col-sm-4'>";
        echo "<p><label for='stockQuantity'>Quantité disponible :</label> <input class='form-control' type='number' value='" . htmlspecialchars($item->quantite) . "' min='0' name='stockQuantity' id='stockQuantity' required></p>";
        echo "<p><label for='caution'>Caution :</label> <div class='input-group'><input class='form-control' type='number' value='" . htmlspecialchars($item->caution) . "' step='0.01' name=caution id='caution' min='0' required><div class='input-group-addon'>&euro;</div></div></p>";
        echo "<p><button type='submit' class='btn btn-warning' name='itemUpdateID' value='{$item->id}'><span class='glyphicon glyphicon-edit'></span> Modifier</button></p></div></form>";
        echo "</div></div>";
        echo "</div></li>";
    }
    if($nothingToShow)
        echo "<li class='list-group-item'><h4 style='text-align: center; font-style: italic'>Ce binet n'a pas d'items à afficher...</h4></li>";
    echo "</ul></div>";
}

function printTableItems($dbh, $isManager, $binet){
     echo <<< CHAINE_DE_FIN
    <div class="container-fluid">
    <table class="table table-striped table-bordered sortable" style="table-layout:fixed">
        <thead class="thead-dark">
            <th scope="col" >Nom</th>
            <th scope="col" >Marque</th>
            <th scope="col" >Type</th>
            <th scope="col" >Image</th>
            <th scope="col" >Description</th>
            <th scope="col" >Stock</th>
            <th scope="col" >Caution</th>
CHAINE_DE_FIN;
     
     if($isManager){     
     echo "<th scope='col' > Modification </th>";
     }
     
     echo <<< CHAINE_DE_FIN
        </thead>
        <tbody>

CHAINE_DE_FIN;
     
     echo printItems($dbh, $isManager, $binet);

    echo "</tbody>"
    .    "</table>"
    ."</div>";
}

function printItems($dbh, $isManager, $binet){
    $query="SELECT * FROM items WHERE binet = ?";
    $sth = $dbh->prepare($query);
    $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
    $sth->execute(array($binet));
    while ($resultat = $sth->fetch()){
        //var_dump($resultat);
        //var_dump($imageBinet);
        $itemUpdateID=$resultat->id;
        if ($resultat->offre || $isManager){
        echo"<tr><th scope='row'><a href='index.php?page=item&id={$resultat->id}'>";
        echo htmlspecialchars($resultat->nom);
        echo "</a>";
        if ($isManager){
            echo <<< CHAINE_DE_FIN
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
                <br/><p style="text-align:center"><input type='hidden' name='pageBinet' value='$binet'>
                <input type='hidden' name='itemUpdateID' value='$itemUpdateID'>
                <input type='hidden' name='toDelete' value='true'>   
                <input type=submit class="btn btn-danger" value="Supprimer" onclick="return confirm('Confirmer la suppression.');"></p>
            </form>
CHAINE_DE_FIN;
        }
        echo "</th>";
        if($isManager){
            echo "<form action='index.php?page=binet&pageBinet=$binet' method=post><input type='hidden' name='pageBinet' value='$binet'><input type='hidden' name='itemUpdateID' value='$itemUpdateID'>";
        }
        echo "<td>";
        echo htmlspecialchars($resultat->marque);
        echo "</td><td>";
        echo htmlspecialchars($resultat->type);
        echo "</td><td>";
            echo "<img src=\"images/items/";
            echo htmlspecialchars($resultat->image);
            echo "\" alt=\"";
            echo htmlspecialchars($resultat->image);
            if ($isManager){
                echo "\" class='image-item-Manager'/>";
            }else{
                echo "\" class='image-item-search'/>";
            }
        echo "</td><td class='description-search'>";
        if ($isManager){
            $description= htmlspecialchars($resultat->description);
            echo "<label for='description'>Description modifiable : </label><textarea class='form-control' name='description' rows='5'/>$description</textarea>";
        } else{
        echo htmlspecialchars($resultat->description);
        }
        if (!$resultat->offre){
            echo"<br/> <p class='notPublicBinetItem'>L'offre n'est pas publique</p>";
        }
        if ($isManager){
            echo <<< CHAINE_DE_FIN
            <p> Offre Publique :<br/>
            <input type="radio" value='oui' name=isOfferPublic id="OfferPublic" checked><label for="OfferPublic">oui</label>
            <input type="radio" value='non' name=isOfferPublic id="OfferPrive"><label for="OfferPrive">non</label>
            </p>
CHAINE_DE_FIN;
        }
        echo "</td><td>";
        if (!$isManager){
        if ($resultat->isstockpublic){
            echo htmlspecialchars($resultat->quantite);
            
        } else {
            echo "Non renseigné";
        }
        } else{
            $stockNumber=$resultat->quantite;
            echo <<<CHAINE_DE_FIN
            <p>
                <label for="stockQuantity">Stock:</label>
                <input class="form-control" type='number' value='$stockNumber' step='any' name=stockQuantity id="stockQuantity">
            </p>
            <p>Stock Public :<br/>
            <input type="radio" value='oui' name=isStockPublic id="StockPublic" checked><label for="StockPublic">oui</label><br/>
            <input type="radio" value='non' name=isStockPublic id="StockPrive"><label for="StockPrive">non</label>
            </p>
            
CHAINE_DE_FIN;
            if (!$resultat->isstockpublic){
                echo"<br/> <p class='notPublicBinetItem'>Le stock n'est pas public.</p>";
            }
        }
        echo "</td><td>";
        if (!$isManager){
        if (strlen($resultat->caution)>0){
            echo htmlspecialchars($resultat->caution);
            echo " &euro;";
        }else {
            echo "Non renseigné ou sans caution.";
        }
        } else{
            $caution=htmlspecialchars($resultat->caution);
            echo <<< CHAINE_DE_FIN
            <label for="caution">Montant :</label>
            <div class="input-group">
            <input class="form-control" type=number value=$caution step='0.01' name=caution id="caution" min='0'>
            <div class="input-group-addon">&euro;</div></div>
CHAINE_DE_FIN;
            
        }
        echo "</td>";
        if ($isManager){
            echo <<< CHAINE_DE_FIN
        
        <td>
            <br/>
            <p style="text-align:center"><input type=submit class="btn btn-warning" value="Modifier"></p>            
        </td>     
            </tr>
                </form>

CHAINE_DE_FIN;
        } else{
            echo "</tr>";
        };
        }
    }
}

function updateStock($dbh, $nom, $marque, $type, $stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID){
    $query="UPDATE `items` SET nom = ?, marque = ?, type = ?, `quantite`=? , `description`=?, `offre`=?, `isstockpublic`=? , `caution`=? WHERE `id`=? ;";
    $sth=$dbh->prepare($query);
    $sth->execute(array($nom, $marque, $type, $stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID));
    $sth->closeCursor();
}

function deleteStock($dbh, $itemUpdateID){
    $query="DELETE FROM `items` WHERE `id`=?";
    $sth=$dbh->prepare($query);
    $resultat=$sth->execute(array($itemUpdateID));
    if ($resultat){
        echo "<div class='container'><span class='enregistrement-valide'>Objet retiré !</span></div><br/>";
    } else{
                echo "<div class='container'><span class='enregistrement-invalide'>Deletion impossible.</span></div><br/>";
    }
    $sth->closeCursor();
}

function genereTypes($dbh){
    $sth=$dbh->prepare("SELECT `nom` FROM `types`");
    $sth->execute();
    while($type=$sth->fetch()){
        $toPrint=$type['nom'];
        echo "<option>$toPrint</option>";
    }
       
    $sth->closeCursor();
}

function generateTypesSelected($dbh, $typeSelected){
    $sth=$dbh->prepare("SELECT `nom` FROM `types`");
    $sth->execute();
    while($type = $sth->fetch()){
        $toPrint = $type['nom'];
        if ($typeSelected == $toPrint)
            echo "<option selected>" . htmlspecialchars($toPrint) . "</option>";
        else
            echo "<option>" . htmlspecialchars($toPrint) . "</option>";
    }
       
    $sth->closeCursor();
}

function printAddItemForms($dbh, $binet){
    
    if (isset($_POST["nomItem"])) $nomItem= htmlspecialchars ($_POST["nomItem"]);
    else $nomItem="";
    
    if (isset($_POST["marqueItem"])) $marqueItem= htmlspecialchars ($_POST["marqueItem"]);
    else $marqueItem="";
        
    if (isset($_POST["quantiteItem"])) $quantiteItem= htmlspecialchars ($_POST["quantiteItem"]);
    else $quantiteItem="";
    
    if (isset($_POST["descriptionItem"])) $descriptionItem= htmlspecialchars ($_POST["descriptionItem"]);
    else $descriptionItem="";
    
    if (isset($_POST["cautionItem"])) $cautionItem= htmlspecialchars ($_POST["cautionItem"]);
    else $cautionItem="";
    
    echo <<< CHAINE_DE_FIN
    <form action="index.php?page=binet&pageBinet=$binet" method=post enctype='multipart/form-data'>
    <div class='col-md-6 gris'>    
 <p>
  <label for="nomItem">Nom :</label>
  <input class="form-control" id="nomItem" type=text name=nomItem value="$nomItem" required>
 </p>
 <p>
  <label for="marqueItem">Marque :</label>
  <input class="form-control" id="marqueItem" type=text name=marqueItem value="$marqueItem">
 </p>
 <p>
  <label for="typeItem">Type :</label>
  <select class="form-control" id="typeItem" name=typeItem required>
CHAINE_DE_FIN;
    
    genereTypes($dbh);
    
    echo <<< CHAINE_DE_FIN
    </select>
 </p>
 <p>
  <label for="quantiteItem">Quantité :</label>
  <input class="form-control" id="quantiteItem" type=number step='any' name=quantiteItem value='$quantiteItem'>
 </p>
 <p>
  <label>Stock public ? </label>
    <label class="radio-inline"><input type="radio" value='oui' name=isStockPublicItem id="StockPublicItem" checked>Oui</label>
    <label class="radio-inline"><input type="radio" value='non' name=isStockPublicItem id="StockPriveItem">Non</label>
 </p>
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />         
 <p>
   <label for="imageItem"> Image : (1 Mo max | format jpeg, jpg, gif ou png)</label>
   <input class="form-control" id="imageItem" type=file name=imageItem>
 </p>
</div>
<div class='col-md-6 gris'>
 <p>
  <label for="descriptionItem">Description :</label><br/>
  <textarea class="form-control" id="descriptionItem" rows=5 name="descriptionItem">$descriptionItem</textarea>
 </p>
 <p>
  <label>Offre publique ? </label>
    <label class="radio-inline"><input type="radio" value='oui' name=isOffrePublicItem id="OffrePublicItem" checked>Oui</label>
    <label class="radio-inline"><input type="radio" value='non' name=isOffrePublicItem id="OffrePriveItem">Non</label>
 </p>
 <p>
  <label for="cautionItem">Caution :</label>
  <div class="input-group">
  <input class="form-control" id="cautionItem" type=number step='0.01' name=cautionItem value='$cautionItem'>
  <div class="input-group-addon">&euro;</div></div>
 </p>
 <button type=submit class="btn btn-warning" ><span class="glyphicon glyphicon-plus-sign"></span> Ajouter l'objet</button>
 </form>
    </div>
CHAINE_DE_FIN;
}

function printGestionItemsForm($dbh, $binet){
    echo <<< CHAINE_DE_FIN
<div class="panel panel-warning toBeClicked ">
            <div class="panel-heading isClickable center"><span class="glyphicon glyphicon-wrench"></span> Gestion de l'inventaire</div>
            <div class="panel-body toBeToggled">
    <div class='row'>
CHAINE_DE_FIN;
    
    printAddItemForms($dbh, $binet);
    
    echo <<< CHAINE_DE_FIN
    </div></div></div>
CHAINE_DE_FIN;
    
}


//Ces fonctions ne sont plus valables après le changement de la BDD
/*function addItem($dbh, $nomItem, $marqueItem, $typeItem){
    $query="INSERT INTO `items` (`nom`, `marque`, `type`) VALUES (?, ?, ?);";
    $sth=$dbh->prepare($query);
    $sth->execute(array($nomItem, $marqueItem, $typeItem));
    return $dbh->lastInsertId();
     
}

function addStock($dbh, $binet, $idItem, $quantiteItem, $descriptionItem, $imageItem, $isOffrePublicItem, $isStockPublicItem, $cautionItem){
    $query="INSERT INTO `stock` (`id`, `binet`, `item`, `quantite`, `description`, `image`, `offre`, `isstockpublic`, `caution`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);";
    $sth=$dbh->prepare($query);
    $resultat=$sth->execute(array($binet, $idItem, $quantiteItem, $descriptionItem, $imageItem, $isOffrePublicItem, $isStockPublicItem, $cautionItem));
    $sth->closeCursor();
    return $resultat;
}*/

function addItem($dbh, $nomItem, $marqueItem, $typeItem, $binet, $quantiteItem, $descriptionItem, $imageItem, $isOffrePublicItem, $isStockPublicItem, $cautionItem){
    $query="INSERT INTO `items` (`nom`, `marque`, `type`, `binet`, `quantite`, `description`, `image`, `offre`, `isstockpublic`, `caution`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $sth=$dbh->prepare($query);
    $resultat=$sth->execute(array($nomItem, $marqueItem, $typeItem, $binet, $quantiteItem, $descriptionItem, $imageItem, $isOffrePublicItem, $isStockPublicItem, $cautionItem));
    $sth->closeCursor();
    return $resultat;
}


function printGestionDemandes($dbh, $binet){
    echo <<< CHAINE_DE_FIN
    <div class="panel panel-warning toBeClicked">
            <div class="panel-heading isClickable center"><span class="glyphicon glyphicon-list-alt"></span> Gestion des demandes et des prêts.</div>
            <div class="panel-body panel-collapse collapse toBeToggled">
    <div class='row'>
CHAINE_DE_FIN;
    
    printDemandeEnCours($dbh, $binet);
    printPretsEnCours($dbh, $binet);
    
    echo <<< CHAINE_DE_FIN
    </div></div></div>
CHAINE_DE_FIN;
}

function printDemandeEnCours($dbh, $binet){
    echo <<< CHAINE_DE_FIN
    <div class='col-md-6'>
        <div class="panel panel-success">
            <div class="panel-heading center"><span class="glyphicon glyphicon-time"></span> Demandes en cours </div>
            <ul class="list-group">    
CHAINE_DE_FIN;
    genereDemandeEnCours($dbh, $binet);
    echo <<< CHAINE_DE_FIN
    </ul>
    </div></div>
CHAINE_DE_FIN;
}

function genereDemandeEnCours($dbh, $binet){
    $query="SELECT `id`, `item`, `commentaire`, `utilisateur`, `quantite`, `debut`, `fin`, `binet_emprunteur`, nom, prenom FROM  `demandes`, utilisateurs WHERE `binet`=? AND `isAccepted`=0 AND login = utilisateur";
    $sth=$dbh->prepare($query);
    $sth->execute(array($binet));
    $demandes=array();
    while ($demande=$sth->fetch()){
        array_push($demandes, $demande);
    }
    
    foreach ($demandes as $demande) {
        $query="SELECT `nom` FROM `items` WHERE `id`=?";   
        $sth=$dbh->prepare($query);
        $sth->execute(array($demande['item']));
        $nomItem=$sth->fetch();
        $demandeID=$demande['id'];
        echo "<li class='list-group-item'><div class='media'><div class='media-left media-middle'>";
        echo <<< CHAINE_DE_FIN
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
            <input type='hidden' name='demandeID' value='$demandeID'>
            <input type='hidden' name='toAcceptDemande' value='true'>
            <button type=submit class="btn btn-success toBeWarnedDelete" style="text-align:center" onclick="return confirm('Accepter la demande.');"><span class="glyphicon glyphicon-ok"></span> Accepter</button>
        </form>
        <br/>        
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
            <input type='hidden' name='demandeID' value='$demandeID'>
            <input type='hidden' name='toRefuseDemande' value='true'>
            <button type=submit class="btn btn-danger toBeWarnedDelete" style="text-align:center" onclick="return confirm('Confirmer le refus.');"><span class="glyphicon glyphicon-remove"></span> Refuser</button>
        </form>
CHAINE_DE_FIN;
        echo "</div><div class='media-body'><h4>";
        echo htmlspecialchars($nomItem['nom']);
        echo"</h4>Quantité : <strong>";
        echo htmlspecialchars($demande['quantite']);
        echo "</strong><br/> Pour <strong>" . htmlspecialchars($demande['prenom']) . " " . htmlspecialchars($demande['nom']) . "</strong>";
        echo " (<i>" . htmlspecialchars($demande['utilisateur']) . "</i>)";
        if ($demande['binet_emprunteur']!=NULL)
            echo " au nom de <strong>" . htmlspecialchars($demande['binet_emprunteur']) . "</strong>";
        echo '<br/>Debut : <strong>';
        if ($demande['debut']!=NULL){
            echo date_format(date_create(htmlspecialchars($demande['debut'])), 'd/m/Y');
        }
        echo '</strong><br/>';
        echo "Fin : <strong>";
        if ($demande['fin']!=NULL){
            echo date_format(date_create(htmlspecialchars($demande['fin'])), 'd/m/Y');
        }
        echo "</strong>";
        if ($demande['commentaire']!=NULL){
        echo "<br/>Commentaire : " . htmlspecialchars($demande['commentaire']);
        }
    }
}

function acceptDemandeEnCours($dbh, $demandeID){
    $query="SELECT `caution`, `quantite` FROM `items` WHERE `id` IN (SELECT `item` FROM `demandes` WHERE `id`=?);";
    $sth=$dbh->prepare($query);
    $sth->execute(array($demandeID));
    $item=$sth->fetch();
    $valeurCaution= htmlspecialchars($item['caution']);
    $quantite=htmlspecialchars($item['quantite']);
    $query="INSERT INTO `cautions` (`id`, `valeur`, `encaisse`, `date_encaissement`) VALUES (NULL, ?, '0', NULL);";
    $sth=$dbh->prepare($query);
    $sth->execute(array($valeurCaution));
    $idCaution=$dbh->lastInsertId();
    
    $query="SELECT `quantite`, `fin` FROM `demandes` WHERE `id`=?";
    $sth=$dbh->prepare($query);
    $sth->execute(array(htmlspecialchars($demandeID)));
    $result=$sth->fetch();
    $quantite_pret=$result['quantite'];
    $dateFin=$result['fin'];
    
    $today = date("Y-m-d");    
    $query="INSERT INTO `pretoperation` (`id`, `debut`, `date_rendu`, `deadline`, `quantite_pret`, `caution`, `demande`) VALUES (NULL, ?, NULL, ?, ?, ?, ?)";
    $sth=$dbh->prepare($query);
    $sth->execute(array($today, $dateFin ,$quantite_pret, $idCaution, $demandeID));
    
    $query="UPDATE `items` SET `quantite`=? WHERE `id` IN (SELECT `item` FROM `demandes` WHERE `id`=?);";
    $sth=$dbh->prepare($query);
    $sth->execute(array($quantite-$quantite_pret,htmlspecialchars($demandeID)));
    if ($quantite-$quantite_pret<=0){
        echo "<div class='container'><span class='enregistrement-invalide'>Attention : rupture de stock.</span></div><br/>";
    }
    
    $query="UPDATE `demandes` SET `isAccepted` = '1' WHERE `demandes`.`id` = ?;";
    $sth=$dbh->prepare($query);
    if ($sth->execute(array($demandeID))){
         echo "<div class='container'><span class='enregistrement-valide'>Vous avez accepté la demande !</span></div><br/>";
    } else{
        echo "<div class='container'><span class='enregistrement-invalide'>Erreur : impossible d'accepter.</span></div><br/>";
    }
}

function deleteDemandeEnCours($dbh, $demandeID){
    $sth=$dbh->prepare("DELETE FROM `demandes` WHERE `id`=?");
    if($sth->execute(array($demandeID))){
        echo "<div class='container'><span class='enregistrement-valide'>Déletion de la demande réussie !</span></div><br/>";
    } else{
        echo "<div class='container'><span class='enregistrement-invalide'>Erreur : impossible de refuser.</span></div><br/>";
    }
}



function printPretsEnCours($dbh, $binet){
    echo <<< CHAINE_DE_FIN
    <div class='col-md-6'>
        <div class="panel panel-danger">
            <div class="panel-heading center"><span class="glyphicon glyphicon-calendar"></span> Prêts en cours </div>
            <div class="panel-body panel-collapse">
    <table class="table table-striped table-bordered sortable" style="table-layout:fixed">
            <tr class="thead-dark">
            <th scope="col" >Informations</th>
            <th scope="col" class="redCell">Deadline</th>
            <th scope="col" >Rendu ?</th>
                </tr>
                <tbody>    
CHAINE_DE_FIN;
    
    generePretsEnCours($dbh, $binet);
    
    echo <<< CHAINE_DE_FIN
    </tbody>
    </table>
    </div></div></div>
CHAINE_DE_FIN;
}

function generePretsEnCours($dbh, $binet){
    $query="SELECT `id`, `debut`, `deadline`, `quantite_pret`, `caution`, `demande` FROM  `pretoperation` WHERE `demande` IN (SELECT `id` FROM `demandes` WHERE `binet`=?) AND (date_rendu IS NULL AND `caution` IN (SELECT `id` FROM `cautions` WHERE `encaisse`=0));";
    $sth=$dbh->prepare($query);
    $sth->execute(array($binet));
    $prets=array();
    while ($pret=$sth->fetch()){
        array_push($prets, $pret);
    }
    
    foreach ($prets as $pret) {
        $pretID=$pret["id"];
        $query="SELECT `id`, `item`, `utilisateur`, `binet_emprunteur` FROM  `demandes` WHERE `id`=?"; 
        $sth=$dbh->prepare($query);
        $sth->execute(array($pret['demande']));
        $demande=$sth->fetch();
        $query="SELECT `nom` FROM `items` WHERE `id`=?";   
        $sth=$dbh->prepare($query);
        $sth->execute(array($demande['item']));
        $nomItem=$sth->fetch();
        echo "<tr><td> <strong>";
        echo htmlspecialchars($nomItem['nom']);
        echo"</strong><br/> Quantité : <strong>";
        echo htmlspecialchars($pret['quantite_pret']);
        echo "</strong><br/> Pour <strong>";
        echo htmlspecialchars($demande['utilisateur']);
        echo "</strong> au nom de <strong>";
        if ($demande['binet_emprunteur']!=NULL){
        echo htmlspecialchars($demande['binet_emprunteur']);
        } else{
            echo 'Personnel';
        }
        echo "</strong><br/>";
        echo 'Depuis : <strong>';
        if ($pret['debut']!=NULL){
            echo htmlspecialchars($pret['debut']);
        }
        echo '</strong><br/>';
       echo "</td><td>";
        if ($pret['deadline']!=NULL){
            echo htmlspecialchars($pret['deadline']);
        } else{
            echo "<span style='font-style:italic'>Pas de deadline</span>";
        }
        echo "</td><td>";
      
        echo <<< CHAINE_DE_FIN
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
            <input type='hidden' name='pretID' value='$pretID'>
            <input type='hidden' name='toArchivePret' value='true'>
            <input type=submit class="btn btn-success toBeWarnedDelete" value="Archiver" style="text-align:center" onclick="return confirm('Archiver le pret.');">
        </form>
CHAINE_DE_FIN;
        $today=$datetime = date("Y-m-d");
        if ($today>$pret['deadline']){
        echo <<< CHAINE_DE_FIN
        <br/>        
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
            <input type='hidden' name='pretID' value='$pretID'>
            <input type='hidden' name='encaisserCaution' value='true'>
                <p style='color:darkred'>La deadline a été dépassé.</p>
            <input type=submit class="btn btn-danger toBeWarnedDelete" value="Encaisser caution" style="text-align:center" onclick="return confirm('Encaisser la caution ?');">
        </form>
        </td></tr>
CHAINE_DE_FIN;
        }
    }
}

function archivePret($dbh, $pretID){
    $query="SELECT `quantite` FROM `items` WHERE `id` IN (SELECT `item` FROM `demandes` WHERE `id` IN (SELECT `demande` FROM `pretoperation` WHERE `id`=?));";
    $sth=$dbh->prepare($query);
    $sth->execute(array($pretID));
    $item=$sth->fetch();
    $quantite=htmlspecialchars($item['quantite']);
    
    $query="SELECT `quantite_pret` FROM `pretoperation` WHERE `id`=?;";
    $sth=$dbh->prepare($query);
    $sth->execute(array($pretID));
    $pret=$sth->fetch();
    $quantite_pret=htmlspecialchars($pret['quantite_pret']);
    
    $query="UPDATE `items` SET `quantite`=? WHERE `id` IN (SELECT `item` FROM `demandes` WHERE `id` IN (SELECT `demande` FROM `pretoperation` WHERE `id`=?));";
    $sth=$dbh->prepare($query);
    $sth->execute(array($quantite+$quantite_pret,htmlspecialchars($pretID)));
    
    $today=date("Y-m-d");
    $query="UPDATE `pretoperation` SET `date_rendu` = ? WHERE `pretoperation`.`id` = ?;";
    $sth=$dbh->prepare($query);
    if($sth->execute(array($today, $pretID))){
        echo "<div class='container'><span class='enregistrement-valide'>Vous avez archivé le prêt.</span></div><br/>";
    } else{
        echo "<div class='container'><span class='enregistrement-invalide'>Erreur : impossible d'archiver.</span></div><br/>";
    }
}

function encaisseCaution($dbh, $pretID){
    $today=date("Y-m-d");
    $query="UPDATE `cautions` SET `encaisse`=1, `date_encaissement`=? WHERE `id` IN (SELECT `caution` FROM `pretoperation` WHERE `id`=?);";
    $sth=$dbh->prepare($query);
    if ($sth->execute(array($today, $pretID))){
        echo "<div class='container'><span class='enregistrement-valide'>Vous avez encaissé la caution.</span></div><br/>";
    } else{
        echo "<div class='container'><span class='enregistrement-invalide'>Erreur : impossible d'encaisser la caution.</span></div><br/>";
    }   
}

        
if (isset($_GET["pageBinet"]) && Binet::doesBinetExist($dbh, $_GET["pageBinet"]) && $_GET["pageBinet"]!="Administrateurs"){
    $binet= htmlspecialchars($_GET["pageBinet"]);
    printHeaderPage($binet, Binet::getImageBinet($dbh, $binet));
    //Si la page s'affiche, on sait par la condition dans index.php que $_SESSION[loggedIn] est actif et que l'utilisateur a un login.
    $role; //rôle du visiteur pour ce binet.
    $isManager=false;
    if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] && (Utilisateur::isAdminBinet($dbh, $_SESSION["login"], $binet) || Utilisateur::isAdmin($dbh, $_SESSION["login"]))){
        $role="admin";
        $isManager=true;
        
        if(isset($_POST["loginRoleBinetAdd"]) && $_POST["loginRoleBinetAdd"] != "" &&
        isset($_POST["role"]) && $_POST["role"]!= ""){
            if (AddRole($dbh, $_POST["loginRoleBinetAdd"], $binet, $_POST["role"])){
                echo "<div class='container'><span class='enregistrement-valide'>Enregistrement du rôle réussi !</span></div><br/>";
            } else{
                echo "<div class='container'><span class='enregistrement-invalide'>Erreur de login.</span></div><br/>";
            }
            }
        if (isset($_POST["loginRoleBinetDelete"]) && $_POST["loginRoleBinetDelete"]!="" &&
                isset($_POST["roleDelete"]) && $_POST["roleDelete"]!= ""){
            if (DeleteRole($dbh, $_POST["loginRoleBinetDelete"], $binet, $_POST["roleDelete"])){
                echo "<div class='container'><span class='enregistrement-valide'>Déletion du rôle réussie !</span></div><br/>";
            } else{
                echo "<div class='container'><span class='enregistrement-invalide'>Erreur.</span></div><br/>";
            }
        }
    }
    genereLeaderBoard($dbh, $binet);
    if ($isManager){
        printAdministration($dbh, $binet);
    } elseif (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] && Utilisateur::isMatosManager($dbh, $_SESSION["login"], $binet)) {
        $role="matosManager";
        $isManager=true;
    } else{
        $role="visiteur";
        $isManager=false;
    }
    
    if ($isManager){
        
        printgestionItemsForm($dbh, $binet);
        if (isset($_POST["nomItem"]) && $_POST["nomItem"]!="" &&
                isset($_POST["marqueItem"]) &&
                isset($_POST["typeItem"]) &&
                isset($_POST["quantiteItem"]) &&
                isset($_POST["descriptionItem"]) &&
                isset($_POST["isStockPublicItem"]) &&
                isset($_POST["isOffrePublicItem"]) &&
                isset($_POST["cautionItem"])){
            $nomItem= htmlspecialchars ($_POST["nomItem"]);
            $marqueItem= htmlspecialchars ($_POST["marqueItem"]);
            $typeItem= htmlspecialchars($_POST["typeItem"]);
            $quantiteItem= htmlspecialchars ($_POST["quantiteItem"]);
            $descriptionItem= htmlspecialchars ($_POST["descriptionItem"]);
            $isStockPublicItem= $_POST["isStockPublicItem"]=="oui" ? true : false;
            $isOffrePublicItem=$_POST["isOffrePublicItem"] == "oui" ? true : false;
            $cautionItem= htmlspecialchars ($_POST["cautionItem"]);
            //$idItem=addItem($dbh, $nomItem, $marqueItem, $typeItem);
            
            if (isset($_FILES['imageItem']) && $_FILES['imageItem']['name']!=""){
            if ($_FILES['imageItem']['error'] > 0){
            switch($_FILES['imageItem']['error']){
                case 4 : //"UPLOAD_ERR_NO_FILE"
                    $error_file="L'image n'a pas été téléversée.";
                    break;
                case 1 : //"UPLOAD_ERR_INI_SIZE"
                    $error_file="L'image est trop grosse !";
                    break;
                case 2 : //"UPLOAD_ERR_FORM_SIZE"
                    $error_file="L'image est trop grosse !";
                    break;
                case 3 : //"UPLOAD_ERR_PARTIAL"
                     $error_file="L'image n'a pas été complètement téléversée.";
                    break;
                default :
                    $error_file="ERREUR";
                    break;
            }
        } else{
            if ($_FILES["imageItem"]['size']>1048576){ $error_file="L'image est trop grosse !";}
            $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $extension_upload = strtolower(  substr(  strrchr($_FILES['imageItem']['name'], '.')  ,1)  );
            if (!in_array($extension_upload,$extensions_valides) ){ $error_file="Extension incorrecte ($extension_upload).";}
        }
        
        if (isset($error_file)){
            $imageItem="default-itemlogo.png";
            echo "<div><span class='enregistrement-invalide'>Upload impossible : $error_file</span></div><br/>"; //erreur rencontrée : il faut avoir tous les droits sur le dossier /image
        } else{
            $dateToInsert=date('YmdHis');
            $adresse_image="images/items/image-".htmlspecialchars($nomItem).$dateToInsert.".png";
            $resultat = move_uploaded_file($_FILES['imageItem']['tmp_name'], $adresse_image);
            if ($resultat){
               echo "<p class='enregistrement-valide'>L'image a bien été téléversée !</p>";
                $imageItem="image-".htmlspecialchars($nomItem).$dateToInsert.".png";
            } else{
                 $imageItem="default-itemlogo.png";
                  echo "<p class='enregistrement-invalide'>BUG : l'image n'a pas pu être téléversée !</p>";
            }
        }
        } else{
            $imageItem="default-itemlogo.png";
        }  
          if (addItem($dbh, $nomItem, $marqueItem, $typeItem, $binet, $quantiteItem, $descriptionItem, $imageItem, $isOffrePublicItem, $isStockPublicItem, $cautionItem)){
              echo "<p class='enregistrement-valide'>Enregistrement dans le stock effectué !</p>";
          } else{
               echo "<p class='enregistrement-invalide'>BUG : pas d'enregistrement dans le stock !</p>";
          }
        }
        
        if (isset($_POST['stockQuantity']) && $_POST['stockQuantity']!="" &&
            isset($_POST['description']) && $_POST['description']!="" &&
            isset($_POST['isOfferPublic']) && $_POST['isOfferPublic']!="" &&
            isset($_POST['isStockPublic']) && $_POST['isStockPublic']!="" &&
            isset($_POST['caution']) && $_POST['caution']!="" &&
            isset($_POST['nom']) && $_POST['nom']!="" &&
            isset($_POST['marque']) && $_POST['marque']!="" &&
            isset($_POST['type']) && $_POST['type']!="" &&
            isset($_POST['itemUpdateID']) && $_POST['itemUpdateID']!=""){
            $stockQuantity= htmlspecialchars($_POST['stockQuantity']);
            $description= htmlspecialchars($_POST['description']);
            $isOfferPublic= $_POST['isOfferPublic']=="oui" ? true : false;
            $isStockPublic= $_POST['isStockPublic']=="oui" ? true : false;
            $caution= htmlspecialchars($_POST['caution']);
            $itemUpdateID= htmlspecialchars($_POST['itemUpdateID']);
            $itemCheck = Item::getItemById($dbh, $_POST['itemUpdateID']);
            $userCheck = $_SESSION['login'];
            if(Utilisateur::isAdminBinet($dbh, $userCheck, $itemCheck->binet) || Utilisateur::isMatosManager($dbh, $userCheck, $itemCheck->binet))
                updateStock($dbh, $_POST['nom'], $_POST['marque'], $_POST['type'], $stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID);
        }
        
        if (isset($_POST['itemDeleteID']) && $_POST['itemDeleteID']!=""){
            $itemCheck = Item::getItemById($dbh, $_POST['itemDeleteID']);
            $userCheck = $_SESSION['login'];
            if(Utilisateur::isAdminBinet($dbh, $userCheck, $itemCheck->binet) || Utilisateur::isMatosManager($dbh, $userCheck, $itemCheck->binet))
                deleteStock($dbh, $_POST["itemDeleteID"]);
        }
        
        if (isset($_POST['toRefuseDemande']) && $_POST['toRefuseDemande'] && isset($_POST['demandeID']) && $_POST['demandeID']!=""){
            $demandeID= htmlspecialchars($_POST['demandeID']);
            deleteDemandeEnCours($dbh, $demandeID);
        }
        
        if (isset($_POST['toAcceptDemande']) && $_POST['toAcceptDemande'] && isset($_POST['demandeID']) && $_POST['demandeID']!=""){
            $demandeID= htmlspecialchars($_POST['demandeID']);
            acceptDemandeEnCours($dbh, $demandeID);
        }
        
        if (isset($_POST['toArchivePret']) && $_POST['toArchivePret'] && isset($_POST['pretID']) && $_POST['pretID']!=""){
            $pretID= htmlspecialchars($_POST['pretID']);
            archivePret($dbh, $pretID);
        }
        
        if (isset($_POST['encaisserCaution']) && $_POST['encaisserCaution'] && isset($_POST['pretID']) && $_POST['pretID']!=""){
            $pretID= htmlspecialchars($_POST['pretID']);
            encaisseCaution($dbh, $pretID);
        }
        
        printGestionDemandes($dbh, $binet);
    }
    
    if($isManager)
        printItemsManager($dbh, $binet);
    else
        printItemsUser($dbh, $binet);
    
} else{
    require("erreur.php");
}



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php

//TODO : rajouter une requête BDD pour connaître le rôle de la personne dans le binet.
//Division en 3 : pas membre du binet, inventory manager ou Admin. Si Inventory manager, ajustage des quantités possible. Si Admin, panneau d'administration pour rajouter des personnes dans le binet comme Inventory Manager.

function printHeaderPage($binet){ //TODO : rajouter l'image du binet ?
    echo <<< CHAINE_DE_FIN
    <div class="container">
    <div class="jumbotron">
            <img src='images/binets/$binet-logo.png' alt='$binet-logo.png' class='pageLogo'>
            <h1>$binet</h1>
        <p>Consultez ici ce que ce binet souhaite vous proposer.</p>
    </div>
</div>
CHAINE_DE_FIN;
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
                    <form action=index.php?page=binet method=post>
                    <input type="hidden" name="pageBinet" value="$binet">
                    <input type="hidden" name="loginRoleBinetDelete" value="$nomUtilisateur">
                    <input type="hidden" name="roleDelete" value="$nomRole">
                    <input type=submit class="btn btn-danger toBeWarnedDelete" value="X" style="text-align:center" onclick="return confirm('Confirmer la suppression.');">
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
    <div class="container">
<div class="panel panel-warning">
            <div class="panel-heading toBeClicked0 isClickable" style="text-align:center">Administration des rôles</div>
            <div class="panel-body toBeToggled0">
    <div class='row '>
        <div class='col-md-6 gris'>
            <div class="panel panel-success">
            <div class="panel-heading center">Ajouter un rôle</div>
            <div class="panel-body">
                <form action=index.php?page=binet method=post>
 <p>
  <label for="loginRole">login : </label>
  <input id="loginRole" type=text name=loginRoleBinetAdd value=$loginRole required>
    <input type="hidden" name="pageBinet" value="$binet">
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
            <div class="panel-heading center">Retirer un rôle</div>
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

echo"</div></div></div></div></div></div></div>";
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

function printTableItems($dbh, $isManager, $binet){
     echo <<< CHAINE_DE_FIN
    <div class="container-fluid">
    <table class="table table-striped table-bordered sortable">
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
            echo "<img src=images/items/";
            echo htmlspecialchars($resultat->image);
            echo " alt='";
            echo htmlspecialchars($resultat->image);
            if ($isManager){
                echo "' class='image-item-Manager'/>";
            }else{
                echo "' class='image-item-search'/>";
            }
        echo "</td><td class='description-search'>";
        if ($isManager){
            $description= htmlspecialchars($resultat->description);
            echo "<label for='description'>Description modifiable : </label><textarea name='description' rows='5'/>$description</textarea>";
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
                <input type=number value=$stockNumber step='any' name=stockQuantity id="stockQuantity">
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
            <input type=number value=$caution step='0.01' name=caution id="caution" min='0'>
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

function updateStock($dbh, $stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID){
    $query="UPDATE `items` SET `quantite`=? , `description`=?, `offre`=?, `isstockpublic`=? , `caution`=? WHERE `id`=? ;";
    $sth=$dbh->prepare($query);
    $sth->execute(array($stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID));
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
    <form action=index.php?page=binet method=post enctype='multipart/form-data'>
    <div class='col-md-6 gris'>    
        <input type="hidden" name="pageBinet" value="$binet" />
 <p>
  <label for="nomItem">Nom :</label>
  <input id="nomItem" type=text name=nomItem value='$nomItem' required>
 </p>
 <p>
  <label for="marqueItem">Marque :</label>
  <input id="marqueItem" type=text name=marqueItem value='$marqueItem'>
 </p>
 <p>
  <label for="typeItem">Type :</label>
  <select id="typeItem" name=typeItem required>
CHAINE_DE_FIN;
    
    genereTypes($dbh);
    
    echo <<< CHAINE_DE_FIN
    </select>
 </p>
 <p>
  <label for="quantiteItem">Quantité :</label>
  <input id="quantiteItem" type=number step='any' name=quantiteItem value='$quantiteItem'>
 </p>
 <p>
  <span style="font-weight:bold">Stock public ?</span>
    <input type="radio" value='oui' name=isStockPublicItem id="StockPublicItem" checked><label for="StockPublicItem">oui</label>
    <input type="radio" value='non' name=isStockPublicItem id="StockPriveItem"><label for="StockPriveItem">non</label>
 </p>
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />         
 <p>
   <label for="imageItem"> Image : (1 Mo max | format jpeg, jpg, gif ou png)</label>
   <input id="imageItem" type=file name=imageItem>
 </p>
</div>
<div class='col-md-6 gris'>
 <p>
  <label for="descriptionItem">Description :</label><br/>
  <textarea id="descriptionItem" rows=5 name=descriptionItem>$descriptionItem</textarea>
 </p>
 <p>
  <span style="font-weight:bold">Offre publique ?</span>
    <input type="radio" value='oui' name=isOffrePublicItem id="OffrePublicItem" checked><label for="OffrePublicItem">oui</label>
    <input type="radio" value='non' name=isOffrePublicItem id="OffrePriveItem"><label for="OffrePriveItem">non</label>
 </p>
 <p>
  <label for="cautionItem">Caution :</label>
  <input id="cautionItem" type=number step='0.01' name=cautionItem value='$cautionItem'>
 </p>
 <input type=submit class="btn btn-warning" value="Ajouter l'objet">
 </form>
    </div>
CHAINE_DE_FIN;
}

function printGestionItemsForm($dbh, $binet){
    echo <<< CHAINE_DE_FIN
    <div class="container">
<div class="panel panel-warning">
            <div class="panel-heading toBeClicked1 isClickable" data-toggle="collapse" data-target="#demande-form" style="text-align:center">Gestion de l'inventaire</div>
            <div class="panel-body toBeToggled1">
    <div class='row'>
CHAINE_DE_FIN;
    
    printAddItemForms($dbh, $binet);
    
    echo <<< CHAINE_DE_FIN
    </div></div></div></div>
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
    <div class="container">
    <div class="panel panel-warning">
            <div class="panel-heading toBeClicked2 isClickable center"  >Gestion des demandes et des prêts.</div>
            <div class="panel-body panel-collapse collapse toBeToggled2">
    <div class='row'>
CHAINE_DE_FIN;
    
    printDemandeEnCours($dbh, $binet);
    printPretsEnCours($dbh, $binet);
    
    echo <<< CHAINE_DE_FIN
    </div></div></div></div>
CHAINE_DE_FIN;
}

function printDemandeEnCours($dbh, $binet){
    echo <<< CHAINE_DE_FIN
    <div class='col-md-6'>
        <div class="panel panel-success">
            <div class="panel-heading center"> Demandes en cours </div>
            <div class="panel-body panel-collapse">
            <table class="table table-striped table-bordered sortable" style="table-layout:fixed">
            <thead class="thead-dark">
            <th scope="col" >Contenu</th>
            <th scope="col" >Commentaire</th>
            <th scope="col" >Accepter ?</th>
                </thead>
                <tbody>    
CHAINE_DE_FIN;
    genereDemandeEnCours($dbh, $binet);
    echo <<< CHAINE_DE_FIN
    </tbody>
    </table>
    </div></div></div>
CHAINE_DE_FIN;
}

function genereDemandeEnCours($dbh, $binet){
    $query="SELECT `id`, `item`, `commentaire`, `utilisateur`, `quantite`, `debut`, `fin`, `binet_emprunteur` FROM  `demandes` WHERE `binet`=?";
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
        echo "<tr><td>";
        echo htmlspecialchars($nomItem['nom']);
        echo"<br/> Quantité : ";
        echo htmlspecialchars($demande['quantite']);
        echo "<br/> Pour ";
        echo htmlspecialchars($demande['utilisateur']);
        echo " au nom de ";
        if ($demande['binet_emprunteur']!=NULL){
        echo htmlspecialchars($demande['binet_emprunteur']);
        } else{
            echo 'Personnel';
        }
        echo "<br/>";
        echo 'Debut : ';
        if ($demande['debut']!=NULL){
            echo htmlspecialchars($demande['debut']);
        }
        echo '<br/>';
        echo "Fin : ";
        if ($demande['fin']!=NULL){
            echo htmlspecialchars($demande['fin']);
        }
        echo "</td><td>";
        echo htmlspecialchars($demande['commentaire']);
        echo <<< CHAINE_DE_FIN
        </td><td>
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
            <input type='hidden' name='demandeID' value='$demandeID'>
            <input type='hidden' name='toAcceptDemande' value='true'>
            <input type=submit class="btn btn-success toBeWarnedDelete" value="Accepter" style="text-align:center" onclick="return confirm('Accepter la demande.');">
        </form>
        <br/>        
        <form action='index.php?page=binet&pageBinet=$binet' method=post>
            <input type='hidden' name='demandeID' value='$demandeID'>
            <input type='hidden' name='toRefuseDemande' value='true'>
            <input type=submit class="btn btn-danger toBeWarnedDelete" value="Refuser" style="text-align:center" onclick="return confirm('Confirmer le refus.');">
        </form>
        </td></tr>
CHAINE_DE_FIN;
        
    }
}





function printPretsEnCours($dbh, $binet){
    echo <<< CHAINE_DE_FIN
    <div class='col-md-6'>
        <div class="panel panel-danger">
            <div class="panel-heading center"> Prêts en cours </div>
            <div class="panel-body panel-collapse collapse">
    
CHAINE_DE_FIN;
    
    echo <<< CHAINE_DE_FIN
    </div></div></div>
CHAINE_DE_FIN;
}





        
if (isset($_GET["pageBinet"]) && Binet::doesBinetExist($dbh, $_GET["pageBinet"]) && $_GET["pageBinet"]!="Administrateurs"){
    $binet= htmlspecialchars($_GET["pageBinet"]);
    printHeaderPage($binet);
    //Si la page s'affiche, on sait par la condition dans index.php que $_SESSION[loggedIn] est actif et que l'utilisateur a un login.
    $role; //rôle du visiteur pour ce binet.
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
           echo "<div><span class='enregistrement-invalide'>Upload impossible : $error_file</span></div><br/>"; //erreur rencontrée : il faut avoir tous les droits sur le dossier /image
        } else{
            $adresse_image="images/items/image-item".date('YmdHis').".png";
            $resultat = move_uploaded_file($_FILES['imageItem']['tmp_name'], $adresse_image);
            if ($resultat){
               echo "<p class='enregistrement-valide'>L'image a bien été téléversée !</p>";
                $imageItem="image-item".date('YmdHis').".png";
            } else{
                 $imageItem=NULL;
                  echo "<p class='enregistrement-invalide'>BUG : l'image n'a pas pu être téléversée !</p>";
            }
        }
        } else{
            $imageItem=NULL;
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
            isset($_POST['itemUpdateID']) && $_POST['itemUpdateID']!=""){
            $stockQuantity= htmlspecialchars($_POST['stockQuantity']);
            $description= htmlspecialchars($_POST['description']);
            $isOfferPublic= $_POST['isOfferPublic']=="oui" ? true : false;
            $isStockPublic= $_POST['isStockPublic']=="oui" ? true : false;
            $caution= htmlspecialchars($_POST['caution']);
            $itemUpdateID= htmlspecialchars($_POST['itemUpdateID']);
            updateStock($dbh, $stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID);
        }
        
        if (isset($_POST["toDelete"]) && $_POST["toDelete"]==true &&
            isset($_POST['itemUpdateID']) && $_POST['itemUpdateID']!=""){
        deleteStock($dbh, $_POST["itemUpdateID"]);
        }
        
        printGestionDemandes($dbh, $binet);
    }
    
    printTableItems($dbh, $isManager, $binet);
    
} else{
    require("erreur.php");
}



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


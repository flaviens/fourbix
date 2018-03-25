<?php

//TODO : rajouter une requête BDD pour connaître le rôle de la personne dans le binet.
//Division en 3 : pas membre du binet, inventory manager ou Admin. Si Inventory manager, ajustage des quantités possible. Si Admin, panneau d'administration pour rajouter des personnes dans le binet comme Inventory Manager.

function printHeaderPage($binet){ //TODO : rajouter l'image du binet ?
    echo <<< CHAINE_DE_FIN
    <div class="container">
    <div class="jumbotron">
        <h1>$binet</h1>
        <p>Consultez ici la page de ce binet !</p>
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
                    <input type=submit class="btn btn-danger" value="X" style="text-align:center">
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
            <div class="panel-heading toBeClicked0" style="text-align:center">Administration</div>
            <div class="panel-body toBeToggled0">
    <div class='row '>
        <div class='col-md-6 gris'>
            <div class="panel panel-success">
            <div class="panel-heading">Ajouter un rôle</div>
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
            <div class="panel-heading">Retirer un rôle</div>
            <div class="panel-body">
<table class="table table-striped table-bordered" style="table-layout:fixed">
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
    <div class="container">
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
    $query="SELECT * FROM `stock` INNER JOIN `item` ON `item`.`id`=`stock`.`item` WHERE `binet`=? ";
    $sth = $dbh->prepare($query);
    $sth->execute(array($binet));
    while ($resultat=$sth->fetch()){
        //var_dump($resultat);
        //var_dump($imageBinet);
        $itemUpdateID=$resultat[0];
        echo"<tr><th scope='row'>";
        echo htmlspecialchars($resultat["nom"]);
        if ($isManager){
            echo <<< CHAINE_DE_FIN
        <form action=index.php?page=binet method=post>
                <br/><p style="text-align:center"><input type='hidden' name='pageBinet' value='$binet'>
                <input type='hidden' name='itemUpdateID' value='$itemUpdateID'>
                <input type='hidden' name='toDelete' value='true'>   
                <input type=submit class="btn btn-danger" value="Supprimer"></p>
            </form>
CHAINE_DE_FIN;
        }
        if ($resultat["offre"] || $isManager){
        echo "</th>";
        if($isManager){
            echo "<form action=index.php?page=binet method=post><input type='hidden' name='pageBinet' value='$binet'><input type='hidden' name='itemUpdateID' value='$itemUpdateID'>";
        }
        echo "<td>";
        echo htmlspecialchars($resultat["marque"]);
        echo "</td><td>";
        echo htmlspecialchars($resultat["type"]);
        echo "</td><td>";
            echo "<img src=images/items/";
            echo $resultat["image"];
            echo " alt='";
            echo $resultat["image"];
            echo "' class='image-item-search'/>";
        echo "</td><td class='description-search'>";
        if ($isManager){
            $description= htmlspecialchars($resultat['description']);
            echo "<label for='description'>Description modifiable : </label><textarea name='description' rows='5'/>$description</textarea>";
        } else{
        echo htmlspecialchars($resultat["description"]);
        }
        if (!$resultat["offre"]){
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
        if ($resultat["isstockpublic"]){
            echo htmlspecialchars($resultat["quantite"]);
            
        } else {
            echo "Non renseigné";
        }
        } else{
            $stockNumber=$resultat["quantite"];
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
            if (!$resultat["isstockpublic"]){
                echo"<br/> <p class='notPublicBinetItem'>Le stock n'est pas public.</p>";
            }
        }
        echo "</td><td>";
        if (!$isManager){
        if (strlen($resultat["caution"])>0){
            echo htmlspecialchars($resultat["caution"]);
            echo "€";
        }else {
            echo "Non renseigné ou sans caution.</td>";
        }
        } else{
            $caution=htmlspecialchars($resultat["caution"]);
            echo <<< CHAINE_DE_FIN
            <label for="caution">Montant :</label>
            <input type=number value=$caution step='0.01' name=caution id="caution">
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
    $query="UPDATE `stock` SET `quantite`=? , `description`=?, `offre`=?, `isstockpublic`=? , `caution`=? WHERE `id`=? ;";
    $sth=$dbh->prepare($query);
    $sth->execute(array($stockQuantity, $description, $isOfferPublic, $isStockPublic, $caution, $itemUpdateID));
    $sth->closeCursor();
}

function deleteStock($dbh, $itemUpdateID){
    $query="DELETE FROM `stock` WHERE `stock`.`id`=?";
    $sth=$dbh->prepare($query);
    $resultat=$sth->execute(array($itemUpdateID));
    if ($resultat){
        echo "<div class='container'><span class='enregistrement-valide'>Objet retiré !</span></div><br/>";
    } else{
                echo "<div class='container'><span class='enregistrement-invalide'>Deletion impossible.</span></div><br/>";
    }
    $sth->closeCursor();
}

function printAddItemsForm(){
    echo <<< CHAINE_DE_FIN
    <div class="container">
<div class="panel panel-warning">
            <div class="panel-heading toBeClicked1" style="text-align:center">Gestion de l'inventaire</div>
            <div class="panel-body toBeToggled1">
    <div class='row '>
        <div class='col-md-12 gris'>
CHAINE_DE_FIN;
    
    
    echo <<< CHAINE_DE_FIN
    </div></div></div></div></div>
CHAINE_DE_FIN;
    
}

        
if (isset($_POST["pageBinet"]) && Binet::doesBinetExist($dbh, $_POST["pageBinet"]) && $_POST["pageBinet"]!="Administrateurs"){
    $binet= htmlspecialchars($_POST["pageBinet"]);
    printHeaderPage($binet);
    //Si la page s'affiche, on sait par la condition dans index.php que $_SESSION[loggedIn] est actif et que l'utilisateur a un login.
    $role; //rôle du visiteur pour ce binet.
    if (Utilisateur::isAdminBinet($dbh, $_SESSION["login"], $binet) || Utilisateur::isAdmin($dbh, $_SESSION["login"])){
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
    } elseif (Utilisateur::isMatosManager($dbh, $_SESSION["login"], $binet)) {
        $role="matosManager";
        $isManager=true;
    } else{
        $role="visiteur";
        $isManager=false;
    }
    
    if ($isManager){
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
        
        printAddItemsForm();
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


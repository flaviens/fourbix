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
            <div class="panel-heading toBeClicked" style="text-align:center">Administration</div>
            <div class="panel-body toBeToggled">
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

function printTableItems($role, $binet){
    
}



        
if (isset($_POST["pageBinet"]) && Binet::doesBinetExist($dbh, $_POST["pageBinet"]) && $_POST["pageBinet"]!="Administrateurs"){
    $binet= htmlspecialchars($_POST["pageBinet"]);
    printHeaderPage($binet);
    //Si la page s'affiche, on sait par la condition dans index.php que $_SESSION[loggedIn] est actif et que l'utilisateur a un login.
    $role; //rôle du visiteur pour ce binet.
    if (Utilisateur::isAdminBinet($dbh, $_SESSION["login"], $binet) || Utilisateur::isAdmin($dbh, $_SESSION["login"])){
        $role="admin";
        
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
    } else{
        $role="visiteur";
    }
    
    
    
} else{
    require("erreur.php");
}



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


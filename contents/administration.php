<?php

echo <<< CHAINE_DE_FIN

<div class="container">
    <div class="jumbotron">
<img src='images/logo/administration2-logo.png' alt='administration2-logo.png' class='pageLogo'>
        <h1>Administration</h1>
        <p>Panneau d'administration : ajoute un utilisateur, un binet, un rôle.</p>
    </div>
</div>  
CHAINE_DE_FIN;

// Creation d'utilisateur.

$isAdministrateur=Utilisateur::isAdmin($dbh, $_SESSION["login"]);

if ($isAdministrateur){

$form_values_valid_user=false;
 
if(isset($_POST["login"]) && $_POST["login"] != "" &&
   isset($_POST["email"]) && $_POST["email"] != "" &&
   isset($_POST["formation"]) && $_POST["formation"]!= "" &&
   isset($_POST["naissance"]) && $_POST["naissance"]!= "" && 
   isset($_POST["up"]) && $_POST["up"]!= "" &&
   isset($_POST["up2"]) && $_POST["up2"]!= "" &&
   isset($_POST["nom"]) && $_POST["nom"] != ""  &&
   isset($_POST["prenom"]) && $_POST["prenom"] != "" ){
    $sth=$dbh->prepare("SELECT `login` FROM `utilisateurs` WHERE `login`=?;");
    $sth->execute(array($_POST["login"]));
   if ($sth->rowCount()==0 && $_POST["up"]==$_POST["up2"]){
    Utilisateur::insererUtilisateur($dbh, $_POST["login"], $_POST["up"], $_POST["nom"], $_POST["prenom"], $_POST["formation"], $_POST["naissance"], $_POST["email"]);
    $form_values_valid_user=true;
   } else{
       echo "<div class='container'><span class='enregistrement-invalide'>Format invalide : vous devez recommencer.</span></div><br/>";
   }
   
}

echo <<< CHAINE_DE_FIN
<div class='container'>
    <div class='row'>
        <div class='col-md-4 gris'>

CHAINE_DE_FIN;

if ($form_values_valid_user) {echo "<div><p class='enregistrement-valide'>Enregistrement d'utilisateur réussi !</p></div>";}


if (isset($_POST["login"])) $login=$_POST["login"];
else $login="''";
if (isset($_POST["prenom"])) $prenom=$_POST["prenom"];
else $prenom="''";
if (isset($_POST["nom"])) $nom=$_POST["nom"];
else $nom="''";
if (isset($_POST["email"])) $email=$_POST["email"];
else $email="''";
if (isset($_POST["formation"])) $formation=$_POST["formation"];
else $formation="''";
if (isset($_POST["naissance"])) $naissance=$_POST["naissance"];
else $naissance="''";

echo <<< CHAINE_DE_FIN
            <div class="panel panel-primary">
            <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Ajouter un utilisateur</div>
            <div class="panel-body">
                <form action=index.php?page=administration method=post oninput="up2.setCustomValidity(up2.value != up.value ? 'Les mots de passe diffèrent.' : '')">
<p>
  <label for="login">Login : </label>
  <input id="login" type=text value=$login name=login required>
 </p>
<p>
  <label for="nom">Nom : </label>
  <input id="nom" type=text value=$nom required name=nom>
 </p>
<p>
  <label for="prenom">Prénom : </label>
  <input id="prenom" type=text value=$prenom required name=prenom>
 </p>
<p>
  <label for="mail">Adresse mail : </label>
  <input id="mail" type=email required value=$email name=email>
</p>
<p>
  <label for="formation">Formation : </label>
  <input id="formation" type=text required value=$formation name=formation>
</p>
<p>
  <label for="naissance">Date de naissance : </label>
  <input id="naissance" type=date value=$naissance name=naissance>
</p>
 <p>
  <label for="password1">Password : </label>
  <input id="password1" type=password required name=up>
 </p>
 <p>
  <label for="password2">Confirm password : </label>
  <input id="password2" type=password name=up2>
 </p>
  <button type=submit class="btn btn-primary" ><span class="glyphicon glyphicon-plus-sign"></span> Créer l'utilisateur</button>
</form>
</div>
</div>
</div>
CHAINE_DE_FIN;



//Creation de Binets

$form_values_valid_binet=false;

echo "<div class='col-md-4 gris'>";

if (isset($_POST["binet"]) && $_POST["binet"]!=""){
    $sth=$dbh->prepare("SELECT `nom` FROM `binets` WHERE `nom`=?;");
    $sth->execute(array($_POST["binet"]));
   if ($sth->rowCount()==0){
       
    if (isset($_FILES['image']) && $_FILES['image']['name']!=""){
        if ($_FILES['image']['error'] > 0){
            switch($_FILES['image']['error']){
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
            if ($_FILES["image"]['size']>1048576){ $error_file="L'image est trop grosse !";}
            $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $extension_upload = strtolower(  substr(  strrchr($_FILES['image']['name'], '.')  ,1)  );
            if (!in_array($extension_upload,$extensions_valides) ){ $error_file="Extension incorrecte ($extension_upload).";}
        }
        
        if (isset($error_file)){
           echo "<div><span class='enregistrement-invalide'>Upload impossible : $error_file</span></div><br/>"; //erreur rencontrée : il faut avoir tous les droits sur le dossier /image
        } else{
            $adresse_image="images/binets/".$_POST['binet']."-logo.png";
            $resultat = move_uploaded_file($_FILES['image']['tmp_name'],$adresse_image);
            if ($resultat){
               echo "<p class='enregistrement-valide'>L'image a bien été téléversée !</p>";
               Binet::insererBinet($dbh, $_POST["binet"]);
               $form_values_valid_binet=true;
            } else{
                 echo "<p class='enregistrement-invalide'>BUG : l'image n'a pas pu être téléversée !</p>";
            }
        }
    } else{
    Binet::insererBinet($dbh, $_POST["binet"]);
    $form_values_valid_binet=true;
    }
    
   } else{
       echo "<div><span class='enregistrement-invalide'>Format invalide : le binet existe déjà.</span></div><br/>";
   }
}

if ($form_values_valid_binet){ echo "<div><p class='enregistrement-valide'>Enregistrement de binet réussi !</p></div>";}

echo <<< CHAINE_DE_FIN
            <div class="panel panel-warning">
            <div class="panel-heading"><span class="glyphicon glyphicon-bitcoin"></span> Ajouter un Binet</div>
            <div class="panel-body">
                <form action=index.php?page=administration method=post enctype='multipart/form-data'>
 <p>
  <label for="binet">Binet :</label>
  <input id="binet" type=text name=binet required>
 </p>
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
 <p>
   <label for="image"> Image : (1 Mo max | format jpeg, jpg, gif ou png)</label>
   <input id="image" type=file name=image>
 </p>
 <button type=submit class="btn btn-warning"> <span class="glyphicon glyphicon-plus-sign"></span> Ajouter le Binet</button>
 </form>
</div>
</div>
CHAINE_DE_FIN;


if (isset($_POST['suppressionBinet']) && $_POST['suppressionBinet']!=""){
    if (Binet::deleteBinet($dbh, $_POST['suppressionBinet'])){
        echo "<div><p class='enregistrement-valide'>Délétion de binet réussie !</p></div>";
    } else{
        echo "<div class='container'><span class='enregistrement-invalide'>Deletion impossible.</span></div><br/>";;
    }
    
}



echo <<< CHAINE_DE_FIN
    <div class="panel panel-danger">
            <div class="panel-heading"><span class="glyphicon glyphicon-bitcoin"></span> Supprimer un Binet</div>
            <div class="panel-body">
                <form action=index.php?page=administration method=post>
 <p>
  <label for="suppressionBinet">Binet : </label>
  <select id="suppressionBinet" name="suppressionBinet" class="form-control" required>
CHAINE_DE_FIN;
  //<input id="binetRole" type=text name=binetRole value=$binetRole required>

Binet::generateBinetOptionsNoAdmin($dbh);

echo <<< CHAINE_DE_FIN
  </select>
 </p>
 <button type=submit class="btn btn-danger" onclick="return confirm('Confirmer la suppression.')"><span class="glyphicon glyphicon-minus-sign"></span> Supprimer le Binet</button>
 </form>
</div>
</div>

CHAINE_DE_FIN;

echo "</div>";

//Ajouter un rôle

function genereRolesChoices($dbh){
    $sth=$dbh->prepare("SELECT `nom` FROM `role`");
    $sth->execute();
    while($role=$sth->fetch()){
        $toPrint=$role['nom'];
        echo "<option>$toPrint</option>";
    }
       
    $sth->closeCursor();
}

$form_values_valid_role=false;

echo "<div class='col-md-4 gris'>";

if(isset($_POST["loginRole"]) && $_POST["loginRole"] != "" &&
   isset($_POST["binetRole"]) && $_POST["binetRole"] != "" &&
   isset($_POST["role"]) && $_POST["role"]!= ""){
    $sth=$dbh->prepare("SELECT `login` FROM `utilisateurs` WHERE `login`=?;");
    $sth->execute(array($_POST["loginRole"]));
   if ($sth->rowCount()==1){
     $sth->closeCursor();
     $sth=$dbh->prepare("SELECT `nom` FROM `binets` WHERE `nom`=?;");
     $sth->execute(array($_POST["binetRole"]));
     if ($sth->rowCount()==1){
         $sth=$dbh->prepare("INSERT INTO `membres` (`id`, `utilisateur`, `binet`, `role`) VALUES (NULL, ?, ?, ?)");
         $sth->execute(array($_POST["loginRole"], $_POST["binetRole"], $_POST["role"]));
         $form_values_valid_role=true;
         $sth->closeCursor();
     } else{
         echo "<div><span class='enregistrement-invalide'>Erreur de binet.</span></div><br/>";
     }
   } else{
       echo "<div><span class='enregistrement-invalide'>Erreur de login.</span></div><br/>";
   }
   
}



if ($form_values_valid_role){ echo "<div><p class='enregistrement-valide'>Enregistrement du rôle réussi !</p></div>";}
    
if (isset($_POST["loginRole"])) $loginRole=$_POST["loginRole"];
else $loginRole="''";
if (isset($_POST["binetRole"])) $binetRole=$_POST["binetRole"];
else $binetRole="''";

echo <<< CHAINE_DE_FIN
            <div class="panel panel-success">
            <div class="panel-heading"><span class="glyphicon glyphicon-registration-mark"></span> Ajouter un rôle</div>
            <div class="panel-body">
                <form action=index.php?page=administration method=post>
 <p>
  <label for="loginRole">login : </label>
  <input id="loginRole" type=text name=loginRole value=$loginRole required>
 </p>
 <p>
  <label for="binetRole">Binet : </label>
  <select id="binetRole" name="binetRole" class="form-control" required>
CHAINE_DE_FIN;
  //<input id="binetRole" type=text name=binetRole value=$binetRole required>

Binet::generateBinetOptions($dbh);

echo <<< CHAINE_DE_FIN
  </select>
 </p>
 <p>
     <label for="role">Role : </label>
      <select id="role" name=role class="form-control">
    
CHAINE_DE_FIN;

genereRolesChoices($dbh);

echo <<< CHAINE_DE_FIN
    </select>
 </p>
   
 <button type=submit class="btn btn-success" ><span class="glyphicon glyphicon-plus-sign"></span> Ajouter le rôle</button>
</form>
</div>
CHAINE_DE_FIN;


echo "</div></div></div>";

function genereBugRapports($dbh){
    $query ="SELECT `id`, `description`, `utilisateur` FROM `bugreports`";
    $sth=$dbh->prepare($query);
    $sth->execute();
    while ($bugRapport=$sth->fetch()){
        $bugToPrint= htmlspecialchars($bugRapport['description']);
        $user= htmlspecialchars($bugRapport['utilisateur']);
        $idBug=htmlspecialchars($bugRapport['id']);
        echo <<< CHAINE_DE_FIN
        <th class="center">$user
            <form action='index.php?page=administration' method=post>
                <br/><p style="text-align:center">
                <input type='hidden' name='bugDeleteID' value='$idBug'>
                <input type='hidden' name='toDeleteBug' value='true'>   
                <button type=submit class="btn btn-success" onclick="return confirm('Le Bug a été résolu ?');"><span class="glyphicon glyphicon-wrench"></span> Bug Résolu</button></p>
            </form>
        </th>
        <td>
         $bugToPrint 
        </td>
CHAINE_DE_FIN;
    }
}

if (isset($_POST['toDeleteBug']) && $_POST['toDeleteBug'] && isset($_POST['bugDeleteID']) && $_POST['bugDeleteID']!=""){
    $sth=$dbh->prepare("DELETE FROM `bugreports` WHERE `id`=?;");
    if ($sth->execute(array(htmlspecialchars($_POST['bugDeleteID'])))){
        echo "<div><span class='enregistrement-valide'>Bug effacé.</span></div><br/>";
   } else{
       echo "<div><span class='enregistrement-invalide'>Impossible d'effacer le bug.</span></div><br/>";
   }
}

echo <<< CHAINE_DE_FIN
<div class='row'>
    <div class='col-md-12'>
        <div class="panel panel-info">
            <div class="panel-heading center isClickable"  data-toggle="collapse" data-target="#bugRapports"><span class="glyphicon glyphicon-alert"></span> Rapports de bug</div>
            <div class="panel-body" id='bugRapports'>
                <table class="table table-striped table-bordered sortable" style="table-layout:fixed">
                <thead class="thead-dark">
                <th scope="col" style="width:20%">Utilisateur</th>
                <th scope="col" >Rapport de bug</th>
                </thead>
                <tbody>
CHAINE_DE_FIN;
     
genereBugRapports($dbh);

echo <<< CHAINE_DE_FIN
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
CHAINE_DE_FIN;

} else{
    
    echo <<< CHAINE_DE_FIN
    <div class=container>
    <div class="alert alert-danger" role="alert" id="pasAdmin">
  /!\ Vous n'êtes pas administrateur ! /!\
</div>
    </div>
CHAINE_DE_FIN;
}


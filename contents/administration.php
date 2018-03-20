<?php

echo <<< CHAINE_DE_FIN

<div class="container">
    <div class="jumbotron">
        <h1>Administration</h1>
        <p>Panneau d'administration : ajoute ou supprime définitivement un utilisateur.</p>
    </div>
</div>  
CHAINE_DE_FIN;

// Creation d'utilisateur.

$form_values_valid_user=false;
 
if(isset($_POST["login"]) && $_POST["login"] != "" &&
   isset($_POST["email"]) && $_POST["email"] != "" &&
   isset($_POST["formation"]) && $_POST["formation"]!= "" &&
   isset($_POST["naissance"]) && $_POST["naissance"]!= "" && 
   isset($_POST["up"]) && $_POST["up"]!= "" &&
   isset($_POST["up2"]) && $_POST["up2"]!= "" &&
   isset($_POST["nom"]) && $_POST["nom"] != ""  &&
   isset($_POST["prenom"]) && $_POST["prenom"] != "" ){
    //$dbh=Database::connect();
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

if (!$form_values_valid_user) {



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
            <div class="panel-heading">Ajouter un utilisateur</div>
            <div class="panel-body">
                <form action=index.php?page=administration method=post oninput="up2.setCustomValidity(up2.value != up.value ? 'Les mots de passe diffèrent.' : '')">
<p>
  <label for="login">login:</label>
  <input id="login" type=text value=$login name=login required>
 </p>
<p>
  <label for="nom">Nom:</label>
  <input id="nom" type=text value=$nom required name=nom>
 </p>
<p>
  <label for="prenom">Prénom:</label>
  <input id="prenom" type=text value=$prenom required name=prenom>
 </p>
<p>
  <label for="mail">Adresse mail</label>
  <input id="mail" type=email required value=$email name=email>
</p>
<p>
  <label for="formation">Formation</label>
  <input id="formation" type=text value=$formation name=formation>
</p>
<p>
  <label for="naissance">Date de naissance</label>
  <input id="naissance" type=date value=$naissance name=naissance>
</p>
 <p>
  <label for="password1">Password:</label>
  <input id="password1" type=password required name=up>
 </p>
 <p>
  <label for="password2">Confirm password:</label>
  <input id="password2" type=password name=up2>
 </p>
  <input type=submit class="btn btn-primary" value="Créer l'utilisateur">
</form>
</div>
</div>
</div>
CHAINE_DE_FIN;
} else{
    echo "<p class='enregistrement-valide'>Enregistrement d'utilisateur réussi !</p>";
}


//Creation de Binets

$form_values_valid_binet=false;
//var_dump($_FILES);

echo "<div class='col-md-4 gris'>";

if (isset($_POST["binet"]) && $_POST["binet"]!=""){
    $sth=$dbh->prepare("SELECT `nom` FROM `binets` WHERE `nom`=?;");
    $sth->execute(array($_POST["binet"]));
   if ($sth->rowCount()==0){
       
    if (isset($_FILES['image']) && $_FILES['image']['name']!=""){
        if ($_FILES['image']['error'] > 0){
            switch($_FILES['image']['error']){
                case "UPLOAD_ERR_NO_FILE" :
                    $error_file="L'image n'a pas été téléversée.";
                    break;
                case "UPLOAD_ERR_INI_SIZE" :
                    $error_file="L'image est trop grosse !";
                    break;
                case "UPLOAD_ERR_FORM_SIZE" :
                    $error_file="L'image est trop grosse !";
                    break;
                case "UPLOAD_ERR_PARTIAL" :
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
           echo "<div class='container'><span class='enregistrement-invalide'>Upload impossible : $error_file</span></div><br/>"; //erreur rencontrée : il faut avoir tous les droits sur le dossier /image
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
       echo "<div class='container'><span class='enregistrement-invalide'>Format invalide : le binet existe déjà.</span></div><br/>";
   }
}

if (!$form_values_valid_binet){

echo <<< CHAINE_DE_FIN
            <div class="panel panel-warning">
            <div class="panel-heading">Ajouter un Binet</div>
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
 <input type=submit class="btn btn-warning" value="Ajouter le Binet">   
CHAINE_DE_FIN;
} else{
    echo "<p class='enregistrement-valide'>Enregistrement de binet réussi !</p>";
}


echo "</div></div></div>";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php

echo <<< CHAINE_DE_FIN

<div class="container">
    <div class="jumbotron">
        <h1>Administration</h1>
        <p>Panneau d'administration : ajoute ou supprime définitivement un utilisateur.</p>
    </div>
</div>  
CHAINE_DE_FIN;

$form_values_valid=false;
 
if(isset($_POST["login"]) && $_POST["login"] != "" &&
   isset($_POST["email"]) && $_POST["email"] != "" &&
   isset($_POST["formation"]) && $_POST["formation"]!= "" &&
   isset($_POST["naissance"]) && $_POST["naissance"]!= "" && 
   isset($_POST["up"]) && $_POST["up"]!= "" &&
   isset($_POST["up2"]) && $_POST["up2"]!= "" &&
   isset($_POST["nom"]) && $_POST["nom"] != ""  &&
   isset($_POST["prenom"]) && $_POST["prenom"] != "" ){
    $dbh=Database::connect();
    $sth=$dbh->prepare("SELECT `login` FROM `utilisateurs` WHERE `login`=?;");
    $sth->execute(array($_POST["login"]));
   if ($sth->rowCount()==0 && $_POST["up"]==$_POST["up2"]){
    Utilisateur::insererUtilisateur($dbh, $_POST["login"], $_POST["up"], $_POST["nom"], $_POST["prenom"], $_POST["formation"], $_POST["naissance"], $_POST["email"]);
    $form_values_valid=true;
   } else{
       echo "<div class='container'><span id='enregistrement-invalide'>Format invalide : vous devez recommencer.<span></div><br/>";
   }
   
}
 
if (!$form_values_valid) {

echo "<div class='container'><form action=index.php?todo=register&page=administration method=post";

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

   echo <<<CHAINE_DE_FIN
      oninput="up2.setCustomValidity(up2.value != up.value ? 'Les mots de passe diffèrent.' : '')">
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
  <input type=submit value="Créer l'utilisateur.">
</form>
</div>
CHAINE_DE_FIN;
} else{
    echo "<p>Enregistrement réussi !</p>";
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php

function printChangePassword() {

    echo <<<CHAINE_DE_FIN
    <form action="index.php?page=accueil&todo=changePassword" method="post">
        <p>Mot de passe actuel : <input type="password" name="currentPassword" required></p>
        <p>Nouveau mot de passe : <input type="password" name="newPassword" required></p>
        <p>Confirmation : <input type="password" name="confirmation" required></p>
        <p> <input type="submit" value="Changer le mot de passe"></p>
    </form>
CHAINE_DE_FIN;
}

function printChangePasswordButton() { //Sert à aller sur la page de changement de Mot de Passe
    echo <<< CHAINE_DE_FIN
    <form action="index.php?page=changePassword" method="post">
        <p><input type="submit" value="Changer le mot de passe"></p>
    </form>
CHAINE_DE_FIN;
}

function changePassword($dbh) {
    $bool1=(isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmation"]) && $_POST["newPassword"]==$_POST["confirmation"]);
    $bool2= Utilisateur::getUtilisateur($dbh, $_SESSION["login"])!=NULL;
    $bool3= Utilisateur::testerMdp($dbh, $_SESSION["login"], $_POST["currentPassword"]);
    
    if ($bool1 && $bool2 && $bool3){
        $loginSession=$_SESSION["login"];
        $newPasswd=$_POST["newPassword"];
        $query="UPDATE `utilisateurs` SET `mdp`=SHA1(?) WHERE `login`='$loginSession'";
        $sth=$dbh->prepare($query);
        $sth->execute(array($newPasswd));
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


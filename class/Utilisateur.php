<?php

class Utilisateur {

    public $prenom;
    public $nom;
    public $formation;
    public $email;
    public $naissance;
    public $login;
    public $password;
    
    public function __toString() {
        $result = "[" . $this->login . "] " . $this->prenom . " <b>" . $this->nom . "</b>, né le " . substr($this->naissance, 8) . "/" . substr($this->naissance, 5, 2) . "/" . substr($this->naissance, 0, 4) . ", "; #possibilité d'utiliser explode pour découper une chaîne de caractère
        if ($this->formation != NULL) {
            $result = $result . $this->formation . ", ";
        }
        $result = $result . "<b>" . $this->email . "</b>";
        return $result;
    }

    public static function getUtilisateur($dbh, $login) {
        $query = "SELECT * FROM `utilisateurs` WHERE `login` = ?;";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array($login));
        $user = $sth->fetch();
        $sth->closeCursor();
        return $user;
    }

    public static function insererUtilisateur($dbh, $login, $password, $nom, $prenom, $formation, $naissance, $email) {
        if (Utilisateur::getUtilisateur($dbh, $login) == NULL) {
            $sth = $dbh->prepare("INSERT INTO `utilisateurs` (`prenom`, `nom`, `formation`, `email`, `naissance`, `login`, `password`) VALUES(?,?,?,?,?,?,SHA1(?))");
            $sth->execute(array($prenom, $nom, $formation, $email, $naissance, $login, $password));
        } 
    }

    public static function testerMdp($dbh, $login, $mdp) {
        $sth = $dbh->prepare("SELECT `password` FROM `utilisateurs` WHERE `login` = ?;");
        $resultat = $sth->execute(array($login));
        $trueMDP = $sth->fetch(PDO::FETCH_ASSOC);
        if (SHA1($mdp) == $trueMDP["password"]) {
            return true;
        } else {
            return false;
        }
    }

    public function secure($tab) {
        foreach ($tab as $cle => $valeur) {
            $tab[$cle] = htmlspecialchars($valeur);
        }
        return $tab;
    }
    
    public static function isAdmin($dbh, $login){ //return true if login is admin
        $query="SELECT `login` FROM `utilisateurs` WHERE `login` IN (SELECT `utilisateur` FROM `membres` WHERE `membres`.`role`='admin' AND `membres`.`binet`='Administrateurs') AND `login`=?;";
        $sth=$dbh->prepare($query);
        $sth->execute(array($login));
        return $sth->rowCount()==1;
    }
    
    
    

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php

class Utilisateur {

    public $prenom;
    public $nom;
    public $formation;
    public $email;
    public $login;
    public $password;
    public static $salt='gcGEZPjSBOZzx+lIC6AYzebtXzaVbsQCGMoKmvEv6a3+A7QjJiHhtN9hJRfL'.
            'VoxRkc1bavwpMwFS20b6t6PAQpJG7jhZBRn2MU1gaiWeVyRatLsvAAAAFQC0Wn+2DFB9Y3'.
            '2PChf9gWnInBeTJwAAAIBnQKZyRqtnk0IlZnU26MhHxrh+A7OMq9YxbqMaVGpBgWJ6SRrX'.
            'Mcd7VuEPuULKYR/ll6O7H60Zt5a9eznT079wdN6UsCjwbFGatjJo+YwyL5XOeGyXccwuvW'.
            'cdy1h4qZNP8sLt+yZ5IT0spcvh0ULfGDmjJtlL9CqFtsfTE5FFLQAAAIB8y4g11dxmckfb'.
            'T7Vs/jZAzYsnW/rQ2zreZs+6ja7EkQ/UN4Q/dh+CbwNnYQJwnvJtLTH6bSH7D7';
    public $naissance;
    
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

    public static function deleteUser($dbh, $login){
        $query = "DELETE FROM utilisateurs WHERE login = ?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($login));
    }

    public static function insererUtilisateur($dbh, $login, $password, $nom, $prenom, $formation, $naissance, $email) {
        if (Utilisateur::getUtilisateur($dbh, $login) == NULL) {
            $sth = $dbh->prepare("INSERT INTO `utilisateurs` (`prenom`, `nom`, `formation`, `email`, `naissance`, `login`, `password`) VALUES(?,?,?,?,?,?,SHA1(?))");
            $sth->execute(array($prenom, $nom, $formation, $email, $naissance, $login, $password.Utilisateur::$salt));
        } 
    }

    public static function testerMdp($dbh, $login, $mdp) {
        $sth = $dbh->prepare("SELECT `password` FROM `utilisateurs` WHERE `login` COLLATE utf8_bin = ?");
        $resultat = $sth->execute(array($login));
        $trueMDP = $sth->fetch(PDO::FETCH_ASSOC);
        if (SHA1($mdp. Utilisateur::$salt) == $trueMDP["password"]) {
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
    
    public static function isAdminBinet($dbh, $login, $binet){ //return true if login is admin
        $query="SELECT `login` FROM `utilisateurs` WHERE `login` IN (SELECT `utilisateur` FROM `membres` WHERE `membres`.`role`='admin' AND `membres`.`binet`=?) AND `login`=?;";
        $sth=$dbh->prepare($query);
        $sth->execute(array($binet, $login));
        return $sth->rowCount()==1;
    }
    
    
    
    public static function isMatosManager($dbh, $login, $binet){ //return true if login is admin
        $query="SELECT `login` FROM `utilisateurs` WHERE `login` IN (SELECT `utilisateur` FROM `membres` WHERE `membres`.`role`='matosManager' AND `membres`.`binet`=?) AND `login`=?;";
        $sth=$dbh->prepare($query);
        $sth->execute(array($binet, $login));
        return $sth->rowCount()==1;
    }
    
    public static function updateProfile($dbh, $login, $new_login, $nom, $prenom, $formation, $naissance, $email){
        $query = "UPDATE utilisateurs SET login = ?, nom = ?, prenom = ?, formation = ?, naissance = ?, email = ? WHERE login = ?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($new_login, $nom, $prenom, $formation, $naissance, $email, $login));
    }
    
    public static function updatePassword($dbh, $login, $mdp){
        $query = "UPDATE utilisateurs SET password = SHA1(?) WHERE login = ?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($mdp.Utilisateur::$salt, $login));
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


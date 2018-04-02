<?php

class Binet{
    public $nom;
 
    public static function getBinet($dbh, $nom){
        $query = "SELECT * FROM `binets` WHERE `nom`=?;";
        $sth=$dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Binet');
        $sth->execute(array($nom));
        $binet=$sth->fetch();
        $sth->closeCursor();
        return $binet;
    }
    
    public static function insererBinet($dbh, $nom){
        if (Binet::getBinet($dbh, $nom)==NULL){
           $sth=$dbh->prepare("INSERT INTO `binets` (`nom`, `image`) VALUES(?, ?)");
           $sth->execute(array($nom, $nom ."-logo.png"));
        }   
    }
    
    public static function getBinetResearchFunction($dbh, $nom){
        $query="SELECT * FROM `binets` WHERE LOCATE(?, `nom`)>0";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Binet');
        $sth->execute(array($nom));
        $binets=array();
        $i=0;
        while ($binet=$sth->fetch()){
            $binets[$i]=clone $binet;
            $i=$i+1;
        }
        $sth->closeCursor();
        return $binets;
    }

    public static function getAllBinets($dbh){
        $query="SELECT * from `binets` ORDER BY `nom` ASC";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Binet');
        $sth->execute();
        $binets = array();
        while($binet = $sth->fetch()){
            array_push($binets, $binet);
        }
        $sth->closeCursor();
        return $binets;
    }
    
    public static function doesBinetExist($dbh, $nom){
        $query="SELECT * FROM `binets` WHERE `nom`=?";
        $sth=$dbh->prepare($query);
        $sth->execute(array($nom));
        return $sth->rowCount()==1;
    }

    public static function generateBinetOptions($dbh){
        $binets = Binet::getAllBinets($dbh);
        foreach ($binets as $binet){
            echo '<option>' . htmlspecialchars($binet->nom) . '</option>';
        }
    }
    
    public static function generateBinetsByMember($dbh, $login){
        $query="SELECT binet FROM membres WHERE utilisateur = ? AND binet!='Administrateurs' GROUP BY binet ORDER BY binet ASC";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Binet');
        $sth->execute(array($login));
        $binets = array();
        while($membre = $sth->fetch()){
            array_push($binets, $membre);
        }
        $sth->closeCursor();
        return $binets;
    }

    public static function generateBinetsByMemberOptions($dbh, $login){
        $query="SELECT binet FROM membres WHERE utilisateur = ? ORDER BY binet ASC";
        $sth = $dbh->prepare($query);
        $sth->execute(array($login));
        $binets = array();
        while($membre = $sth->fetch()){
            if (!in_array($membre['binet'], $binets))
                array_push($binets, $membre['binet']);
        }
        $sth->closeCursor();
        foreach ($binets as $binet){
            echo '<option>' . htmlspecialchars($binet) . '</option>';
        }
    }

    public static function getBinetsByUser($dbh, $login){
        $query = "SELECT id, binet, role, image FROM membres, binets WHERE utilisateur = ? AND membres.binet = binets.nom ORDER BY binet ASC";
        $sth = $dbh->prepare($query);
        $sth->execute(array($login));
        $mesBinets = array();
        while($binet=$sth->fetch()){
            array_push($mesBinets, $binet);
        }
        $sth->closeCursor();
        return $mesBinets;
    }

    public static function deleteBinetMember($dbh, $id){
        $query = "DELETE FROM membres WHERE id = ?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($id));
    }

    public static function getMemberById($dbh, $id){
        $query = "SELECT * FROM membres WHERE id = ?";
        $sth= $dbh->prepare($query);
        $sth->execute(array($id));
        $member = $sth->fetch();
        $sth->closeCursor();
        return $member;
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


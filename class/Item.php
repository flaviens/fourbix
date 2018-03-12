<?php

class Item{
    
    public $id;
    public $nom;
    public $marque;
    public $type;
    
    public function __toString(){
        
    }
    
    public function getItemResearchFunction($dbh, $nom){
        $query="SELECT * FROM `item` WHERE LOCATE(?, `nom`)>0";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
        $sth->execute(array($nom));
        $item = $sth->fetch();
        $sth->closeCursor();
        return $item;
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


<?php

class Item{
    
    public $id;
    public $nom;
    public $marque;
    public $type;
    public $binet;
    public $quantite;
    public $description;
    public $image;
    public $offre;
    public $isstockpublic;
    public $caution;
    
    public static function getItemResearchFunction($dbh, $nom){
        $query="SELECT * FROM items WHERE LOCATE(?, nom)>0";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
        $sth->execute(array($nom));
        $items=array();
        $i=0;
        while ($item=$sth->fetch()){
            $items[$i]=clone $item;
            $i=$i+1;
        }
        $sth->closeCursor();
        return $items;
    }

    public static function getItemMultipleResearch($dbh, $search){
        $query="SELECT * FROM items WHERE nom LIKE CONCAT('%', :search, '%') OR marque LIKE CONCAT('%', :search, '%')";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
        $sth->execute(array('search' => $search));
        $items=array();
        $i=0;
        while ($item=$sth->fetch()){
            $items[$i] = clone $item;
            $i=$i+1;
        }
        $sth->closeCursor();
        return $items;
    }

    public static function getItemById($dbh, $id){
        $query = "SELECT * FROM items WHERE id = ?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
        $sth->execute(array($id));
        $item = $sth->fetch();
        $sth->closeCursor();
        return $item;
    }

    public static function getItemsFromBinetsWithImage($dbh, $nomBinet){ 
        $query="SELECT * FROM items WHERE binet=? AND offre=1 AND quantite>0 AND image IS NOT NULL";
        $sth=$dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
        $sth->execute(array($nomBinet));
        $items=array();
        $i=0;
        while ($item=$sth->fetch()){
            $items[$i] = clone $item;
            $i++;
        }
        return $items;
    }

    public static function getItemsFromBinets($dbh, $nomBinet){ 
        $query="SELECT * FROM items WHERE binet=?";
        $sth=$dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Item');
        $sth->execute(array($nomBinet));
        $items=array();
        $i=0;
        while ($item=$sth->fetch()){
            $items[$i] = clone $item;
            $i++;
        }
        $sth->closeCursor();
        return $items;
    }

}

?>
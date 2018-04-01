<?php

class Stock {

	public $id;
	public $binet;
	public $item;
	public $quantite;
	public $description;
	public $image;
	public $offre;
	public $isstockpublic;
	public $caution;

	public static function getStockById($dbh, $id){
		$query = "SELECT * FROM stock WHERE id = ?";
		$sth = $dbh->prepare($query);
		$sth->setFetchMode(PDO::FETCH_CLASS, 'Stock');
		$sth->execute(array($id));
		$stock = $sth->fetch();
		$sth->closeCursor();
		return $stock;
	}
}

?>
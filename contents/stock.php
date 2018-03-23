<div class="container">
    <div class="jumbotron">
        <h1>Stock</h1>
        <p>Informations sur les items des binets.</p>
    </div>

<?php

if (isset($_GET['id']) and ctype_digit($_GET['id'])){
	$stock = Stock::getStockById($dbh, $_GET['id']);
	if ($stock != null && $stock->isstockpublic){
		$item = Item::getItemById($dbh, $stock->item);
		echo $item->nom;
	}
	else
		echo "<p>Cet item n'existe pas ou n'est pas disponible.</p>";
}
else
	echo "<p>Cet item n'existe pas ou n'est pas disponible.</p>";

?>


</div>
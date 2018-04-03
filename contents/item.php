<div class="container">
    <div class="jumbotron">
        <img src='images/logo/item-logo.png' alt='item-logo.png' class='pageLogo'>
        <h1>Inventaire</h1>
        <p>Informations sur les items des binets.</p>
    </div>

<?php

if (isset($_GET['id']) and ctype_digit($_GET['id'])){
	$item = Item::getItemById($dbh, $_GET['id']);
?>

<div class="item">
	<div class="row">
		<div class="col-md-4 col-md-offset-2">
			<h2><?php echo htmlspecialchars($item->nom) ?></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-md-offset-2">
			<p><b>Binet : </b><a href="<?php echo 'index.php?page=binet&pageBinet=' . htmlspecialchars($item->binet); ?>"><?php echo htmlspecialchars($item->binet) ?></a></p>
			<p><b>Marque : </b><?php echo htmlspecialchars($item->marque) ?></p>
			<p><b>Type : </b><?php echo htmlspecialchars($item->type) ?></p>
			<p><b>Déscription : </b><br/><?php echo htmlspecialchars($item->description) ?></p>
			<p><b>Quantité disponible : </b><?php echo htmlspecialchars($item->quantite) ?></p>
			<p><b>Caution : </b><?php echo htmlspecialchars($item->caution) ?> &euro;</p>
		</div>
		<div class="col-md-4">
			<img src=<?php echo "'images/items/{$item->image}'"; ?> class="image-item-item">
		</div>
	</div>
</div>

<div class="demande">
<?php
		$valid_demande = false;
		if($item->quantite > 0){
			if(isset($_POST['quantite']) and $_POST['quantite'] >= 1 and $_POST['quantite'] <= htmlspecialchars($item->quantite)){
				$quantite = $_POST['quantite'];
				
				if(isset($_POST['commentaire']) and $_POST['commentaire'] != "")
					$commentaire = htmlspecialchars ($_POST['commentaire']);
				else
					$commentaire = NULL;

				if(isset($_POST['date-debut']) and $_POST['date-debut'] != "")
					$debut = $_POST['date-debut'];
				else
					$debut = NULL;

				if(isset($_POST['date-fin']) and $_POST['date-fin'] != "")
					$fin = $_POST['date-fin'];
				else
					$fin = NULL;

				if(isset($_POST['binet']) and $_POST['binet'] != ""){
					$binet_emprunteur = Binet::getBinet($dbh, $_POST['binet']);
					if ($binet_emprunteur != NULL)
						$binet_emprunteur = $binet_emprunteur->nom;
				}
				else
					$binet_emprunteur = NULL;

				$valid_demande = (isset($_POST['checkBinet']) and ($_POST['checkBinet'] == "no" or $binet_emprunteur != NULL)) and $_SESSION["loggedIn"];
				if (!($fin == NULL or $debut == NULL or $debut < $fin))
					$valid_demande = false;

				if ($valid_demande){
					$sth = $dbh->prepare("INSERT INTO demandes (utilisateur, item, binet, commentaire, debut, fin, binet_emprunteur, quantite) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
					$sth->execute(array($_SESSION["login"], $item->id, $item->binet, $commentaire, $debut, $fin, $binet_emprunteur, $quantite));
				}
				else
					echo "<div><span class='enregistrement-invalide'>Votre demande a échoué. Veuillez essayer à nouveau.</span></div><br/>";
			}

			if ($valid_demande)
				echo "<div><span class='enregistrement-valide'>Demande de prêt réussi ! Veuillez attendre la confirmation du binet. </span></div>"
?>
	<div class="row"><div class="col-md-4 col-md-offset-2">
		<div class="panel panel-primary">
		<div class="panel-heading" data-toggle="collapse" data-target="#demande-form">
			<h3 class="panel-title"><span class="glyphicon glyphicon-shopping-cart"></span> Faire une demande de prêt</h3>
		</div>
		<div class="panel-body panel-collapse collapse" id="demande-form">
			<form action=<?php echo "'index.php?page=item&id=$item->id'";?> method="post" oninput="binet.setCustomValidity(binet.value == '' && checkBinet.value == 'yes' ? 'Veuillez sélectionner binet.' : '')">
				<p>
					<label for="quantite">Quantité : </label>
					<input type="number" name="quantite" id="quantite" min="1" max="<?php echo htmlspecialchars($item->quantite) ?>" required>
				</p>
				<p><label for="commentaire">Commentaire : </label></p>
				<p><textarea name="commentaire" id="commentaire" placeholder="Écrivez votre commentaire." style="margin-bottom: 3px"></textarea></p>
				<p>
					<label for="date-debut">Date de début : </label>
					<input type="date" name="date-debut" id="date-debut">
				</p>
				<p>
					<label for="date-fin">Date de fin estimée : </label>
					<input type="date" name="date-fin" id="date-fin">
				</p>
				<p>
					<label for="checkBinet">Demande faite par un binet ? </label>
					<input type="radio" name="checkBinet" id="yesBinet" value="yes" onclick="showBinet()" style="margin-left: 5px">Oui
					<input type="radio" name="checkBinet" id="noBinet" value="no" onclick="showBinet()" style="margin-left: 5px" checked="on">Non
				</p>
				<p id="binet-emprunteur" style="display: none">
					<label for="binet">Binet : </label>
					<select name="binet" id="binet">
					<option value="" disabled selected>Sélectionnez votre binet</option>
					<?php Binet::generateBinetsByMemberOptions($dbh, $_SESSION["login"]); ?>
					</select>
				</p>
				<input type=submit class="btn btn-primary" value="Valider">
			</form>
		</div></div>
	</div>
</div></div>

<?php
	}
	else
		echo "<h4 style='text-align:center'>Cet item n'existe pas ou n'est pas disponible.</h4>";
}
else
	echo "<h4 style='text-align:center'>Cet item n'existe pas ou n'est pas disponible.</h4>";

?>


</div>

<script type="text/javascript">
	function showBinet(){
		if(document.getElementById("yesBinet").checked)
			document.getElementById("binet-emprunteur").style.display = 'block';
		else
			document.getElementById("binet-emprunteur").style.display = 'none';
	}
</script>
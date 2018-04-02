<?php 

$user = Utilisateur::getUtilisateur($dbh, $_SESSION['login']);

if (isset($_POST['abandonnerBinet']) and ctype_digit($_POST['abandonnerBinet'])){
	$member = Binet::getMemberById($dbh, $_POST['abandonnerBinet']);
	if ($member['utilisateur'] == $_SESSION['login'])
		Binet::deleteBinetMember($dbh, $member['id']);
}

?>

<div class="container">
    <div class="jumbotron">
        <h1><?php echo $user->prenom . " " . $user->nom ?></h1>
        <p>Votre page personnelle.</p>
    </div>

	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Profil</div>
				<div class="panel-body">
					<p><b>Login :</b> <?php echo htmlspecialchars($user->login); ?></p>
					<p><b>Nom :</b> <?php echo htmlspecialchars($user->nom); ?></p>
					<p><b>Prénom :</b> <?php echo htmlspecialchars($user->prenom); ?></p>
					<p><b>E-mail :</b> <?php echo htmlspecialchars($user->email); ?></p>
					<p><b>Formation :</b> <?php echo htmlspecialchars($user->formation); ?></p>
					<p><b>Naissance :</b> <?php echo date_format(date_create($user->naissance), 'd F Y'); ?></p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading"><span class="glyphicon glyphicon-briefcase"></span> Mes Binets</div>
				<ul class="list-group">
				<?php
					$mesBinets = Binet::getBinetsByUser($dbh, $user->login);
					foreach ($mesBinets as $binet) {
						echo "<li class='list-group-item'><div class='media'>";
						echo "<div class='media-left media-top'><img src='images/binets/" . htmlspecialchars($binet['image']) . "' alt='" . htmlspecialchars($binet['image']) . "' class='image-binet-catalogue' /></div>";
						echo "<div class='media-body'><h4 class='media-heading'>" . htmlspecialchars($binet['binet']) . "</h4><p style='font-style: italic'>" . htmlspecialchars($binet['role']) . "</p>";
						echo "<form method='post' action='index.php?page=utilisateur'><button type='submit' class='btn btn-danger' name='abandonnerBinet' value='" . htmlspecialchars($binet['id']) . "' onclick='return confirm(\"Voulez-vous quitter le binet ce binet ?\")';><span class='glyphicon glyphicon-trash'></span> Abandonner</button></form>";
						echo "</div>";
						echo "</div></li>";
					}
				?>
				</ul>
			</div>
		</div>
	</div>

</div>
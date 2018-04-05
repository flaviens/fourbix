<?php 

$user = Utilisateur::getUtilisateur($dbh, $_SESSION['login']);

if (isset($_POST['abandonnerBinet']) and ctype_digit($_POST['abandonnerBinet'])){
	$member = Binet::getMemberById($dbh, $_POST['abandonnerBinet']);
	if ($member['utilisateur'] == $_SESSION['login'])
		Binet::deleteBinetMember($dbh, $member['id']);
}

if (isset($_POST['suprimmerCompte']) and $_POST['suprimmerCompte']){
	Utilisateur::deleteUser($dbh, $_SESSION['login']);
	logOut();
	header('Location: index.php?page=accueil');
}

if (isset($_POST['bugDescription']) and $_POST['bugDescription'] != ''){
	$query = "INSERT INTO bugreports (description, utilisateur) VALUES (?, ?)";
	$sth = $dbh->prepare($query);
	$sth->execute(array($_POST['bugDescription'], $_SESSION['login']));
}

$mdp_valid = true;
$updateMdp_valid = false;
if (isset($_POST['updateMdp']) && $_POST['updateMdp'] &&
		isset($_POST["password"]) && $_POST["password"] != "" &&
		isset($_POST["password1"]) && $_POST["password1"] != "" &&
   		isset($_POST["password2"]) && $_POST["password2"] != "" &&
   		$_POST["password2"] == $_POST["password1"]){
	$mdp_valid = Utilisateur::testerMdp($dbh, $_SESSION['login'], $_POST["password"]);
	if($mdp_valid){
		Utilisateur::updatePassword($dbh, $_SESSION['login'], $_POST["password1"]);
		$updateMdp_valid = true;
	}
}

$update_valid = false;
$new_login_valid = true;
if (isset($_POST['updateUser']) && $_POST['updateUser'] &&
		isset($_POST["login"]) && $_POST["login"] != "" &&
   		isset($_POST["email"]) && $_POST["email"] != "" &&
   		isset($_POST["formation"]) && $_POST["formation"] != "" &&
   		isset($_POST["naissance"]) && $_POST["naissance"] != "" && 
   		isset($_POST["nom"]) && $_POST["nom"] != ""  &&
   		isset($_POST["prenom"]) && $_POST["prenom"] != ""){
	$new_login_valid = $_POST["login"] == $_SESSION['login'] || (Utilisateur::getUtilisateur($dbh, $_POST["login"]) == NULL);
	if($new_login_valid){
		Utilisateur::updateProfile($dbh, $_SESSION['login'], $_POST["login"], $_POST["nom"], $_POST["prenom"], $_POST["formation"], $_POST["naissance"], $_POST["email"]);
		$update_valid = true;
		$_SESSION['login'] = $_POST["login"];
		$user = Utilisateur::getUtilisateur($dbh, $_SESSION['login']);
	}
}

?>

<div class="container">
    <div class="jumbotron">
    	<img src='images/logo/utilisateur-logo.png' alt='utilisateur-logo.png' class='pageLogo'>
        <h1><?php echo $user->prenom . " " . $user->nom ?></h1>
        <p>Votre page personnelle.</p>
    </div>

	<div class="row">
		<div class="col-md-5 col-md-offset-1">
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
		<div class="col-md-5">
			<div class="panel panel-info">
				<div class="panel-heading"><span class="glyphicon glyphicon-briefcase"></span> Mes Binets</div>
				<ul class="list-group">
				<?php
					$mesBinets = Binet::getBinetsByUser($dbh, $user->login);
					$mesBinetsGrouped = array();
					foreach ($mesBinets as $binet) {
						if(isset($mesBinetsGrouped[$binet['binet']]))
							array_push($mesBinetsGrouped[$binet['binet']]['roles'], array('role' => $binet['role'], 'id' => $binet['role']));
						else{
							$mesBinetsGrouped[$binet['binet']] = array('binet' => $binet['binet'],
																		'image' => $binet['image'],
																		'roles' =>  array(array('role' => $binet['role'], 'id' => $binet['id'])));
						}

					}

					foreach ($mesBinetsGrouped as $binet) {
						if($binet['binet'] != 'Administrateurs')
							$page = 'index.php?page=binet&pageBinet=' . htmlspecialchars($binet['binet']);
						else
							$page = 'index.php?page=administration';
						echo "<li class='list-group-item'><div class='media'>";
						echo "<div class='media-left media-top'><a href='" . $page . "'><img src='images/binets/" . htmlspecialchars($binet['image']) . "' alt='" . htmlspecialchars($binet['image']) . "' class='image-binet-catalogue' /></a></div>";
						echo "<div class='media-body'><h4 class='media-heading'><a href='" . $page . "'>" . htmlspecialchars($binet['binet']) . "</a></h4>";
						echo "<ul style='list-style-type: none; padding: 0;'>";
						foreach ($binet['roles'] as $role) {
							echo "<li style='margin-bottom: 5px;'><span style='font-style: italic; margin-right: 5px; display: inline-block;'>" . htmlspecialchars($role['role']) . "</span>";
							echo "<form method='post' action='index.php?page=utilisateur' style='display: inline-block'><button type='submit' class='btn btn-danger' name='abandonnerBinet' value='" . htmlspecialchars($role['id']) . "' onclick='return confirm(\"Voulez-vous quitter le binet ce binet ?\")'><span class='glyphicon glyphicon-trash'></span> Abandonner</button></form></li>";
						}
						echo "</ul></div></div></li>";
					}

					if (empty($mesBinets))
						echo "<li class='list-group-item' style='font-style: italic;'>Vous n'avez pas de binets à afficher.";
				?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-success">
				<div class="panel-heading isClickable" data-toggle="collapse" data-target="#update-form">
					<span class="glyphicon glyphicon-wrench"></span> Modifier votre profil
				</div>
				<div class="panel-body panel-collapse collapse" id="update-form">
					<form action="index.php?page=utilisateur" method="POST">
						<p>
							<label for="login">Login : </label><br/>
							<input class="form-control" id="login" type="text" name="login" value="<?php echo htmlspecialchars($user->login); ?>" required/>
						</p>
						<p>
							<label for="email">E-mail : </label><br/>
							<input class="form-control" type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required/>
						</p>
						<p>
							<label for="nom">Nom : </label><br/>
							<input class="form-control" type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user->nom); ?>" required/>
						</p>
						<p>
							<label for="prenom">Prénom : </label><br/>
							<input class="form-control" type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user->prenom); ?>" required/>
						</p>
						<p>
							<label for="formation">Formation : </label><br/>
							<input class="form-control" type="text" id="formation" name="formation" value="<?php echo htmlspecialchars($user->formation); ?>" />
						</p>
						<p>
							<label for="naissance">Date de naissance : </label><br/>
							<input class="form-control" type="date" id="naissance" name="naissance" value="<?php echo htmlspecialchars($user->naissance); ?>" required/>
						</p>
						<button type='submit' class='btn btn-success' name='updateUser' value='true'><span class='glyphicon glyphicon-floppy-disk'></span> Enregistrer</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-success">
				<div class="panel-heading isClickable" data-toggle="collapse" data-target="#mdp-form">
					<span class="glyphicon glyphicon-lock"></span> Modifier votre mot de passe
				</div>
				<div class="panel-body panel-collapse collapse" id="mdp-form">
					<form action="index.php?page=utilisateur" method="POST" oninput="password2.setCustomValidity(password2.value != password1.value ? 'Les mots de passe différent.' : '')">
						<p>
							<label for="password">Mot de passe actuel : </label><br/>
							<input class="form-control" type="password" id="password" name="password" required>
						</p>
						<p>
							<label for="password1">Nouveau mot de passe : </label><br/>
							<input class="form-control" type="password" id="password1" name="password1" required>
						</p>
						<p>
							<label for="password2">Confirmez nouveau mot de passe : </label><br/>
							<input class="form-control" type="password" id="password2" name="password2" required>
						</p>
						<button type='submit' class='btn btn-success' name='updateMdp' value='true'><span class='glyphicon glyphicon-floppy-disk'></span> Enregistrer</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-warning">
				<div class="panel-heading isClickable" data-toggle="collapse" data-target="#bug-form">
					<span class="glyphicon glyphicon-alert"></span> Signaler un bug
				</div>
				<div class="panel-body panel-collapse collapse" id="bug-form">
					<form action="index.php?page=utilisateur" method="post">
						<label for="bugDescription">Avez-vous trouvé un bug dans le site ? Dites-le-nous !</label>
						<p><textarea class="form-control" name="bugDescription" id="bugDescription" placeholder="Description" required=""></textarea></p>
						<button type='submit' class='btn btn-warning' name='bugReport' value='true'><span class='glyphicon glyphicon-envelope'></span> Envoyer</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
		if (isset($_POST['updateUser']) and $_POST['updateUser']){
			if (!$new_login_valid)
				echo "<div class='row'><div class='col-md-6 col-md-offset-3 enregistrement-invalide' style='text-align:center'>Cet utilisateur existe déjà. Veuillez choisir un login différent.</div></div><br/>";
			else if(!$update_valid)
				echo "<div class='row'><div class='col-md-6 col-md-offset-3 enregistrement-invalide' style='text-align:center'>Modification invalide. Veuillez essayer à nouveau.</div></div><br/>";
			else
				echo "<div class='row'><div class='col-md-6 col-md-offset-3 enregistrement-valide' style='text-align:center'>Votre profile a été bien modifié !</div></div><br/>";
		}

		if (isset($_POST['updateMdp']) and $_POST['updateMdp']){
			if (!$mdp_valid)
				echo "<div class='row'><div class='col-md-6 col-md-offset-3 enregistrement-invalide' style='text-align:center'>Mot de passe incorrect. Veuillez essayer à nouveau.</div></div><br/>";
			else if(!$updateMdp_valid)
				echo "<div class='row'><div class='col-md-6 col-md-offset-3 enregistrement-invalide' style='text-align:center'>Modification invalide. Veuillez essayer à nouveau.</div></div><br/>";
			else
				echo "<div class='row'><div class='col-md-6 col-md-offset-3 enregistrement-valide' style='text-align:center'>Votre mot de passe a été bien modifié !</div></div><br/>";
		}
	?>

	<div class="row">
		<div class="col-md-2 col-md-offset-5" style="text-align:center">
			<form method='post' action='index.php?page=utilisateur'>
				<button type='submit' class='btn btn-danger' name='suprimmerCompte' value='true' onclick='return confirm("Voulez-vous supprimer votre compte ?");'><span class='glyphicon glyphicon-remove-sign'></span> Supprimer compte</button>
			</form>
		</div>
		<br/><br/><br/>
	</div>

</div>
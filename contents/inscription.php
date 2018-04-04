<?php

$form_values_valid_user=false;
 
if(isset($_POST["login"]) && $_POST["login"] != "" &&
   isset($_POST["email"]) && $_POST["email"] != "" &&
   isset($_POST["formation"]) && $_POST["formation"]!= "" &&
   isset($_POST["naissance"]) && $_POST["naissance"]!= "" && 
   isset($_POST["password"]) && $_POST["password"]!= "" &&
   isset($_POST["password2"]) && $_POST["password2"]!= "" &&
   isset($_POST["nom"]) && $_POST["nom"] != ""  &&
   isset($_POST["prenom"]) && $_POST["prenom"] != "" ){
    $sth=$dbh->prepare("SELECT `login` FROM `utilisateurs` WHERE `login`=?;");
    $sth->execute(array($_POST["login"]));
    if ($sth->rowCount() != 0)
    	echo "<br/><div class='container'><span class='enregistrement-invalide'>Cet utilisateur existe déjà. Veuillez choisir un login différent.</span></div><br/>";
   	else if ($sth->rowCount() == 0 && $_POST["password"]==$_POST["password2"]){
    	Utilisateur::insererUtilisateur($dbh, $_POST["login"], $_POST["password"], $_POST["nom"], $_POST["prenom"], $_POST["formation"], $_POST["naissance"], $_POST["email"]);
    	$form_values_valid_user=true;
   	}
   	else
       echo "<br/><div class='container'><span class='enregistrement-invalide'>Inscription invalide. Veuillez recommencer.</span></div><br/>";
   
}

if ($form_values_valid_user){
	logIn($dbh);
	header('Location: index.php?page=accueil');
}
else if (!isset($_SESSION['loggedIn']) or !$_SESSION['loggedIn']){
	if (isset($_POST["login"]))
		$login=$_POST["login"];
	else 
		$login="''";

	if (isset($_POST["prenom"]))
		$prenom=$_POST["prenom"];
	else
		$prenom="''";

	if (isset($_POST["nom"]))
		$nom=$_POST["nom"];
	else
		$nom="''";

	if (isset($_POST["email"]))
		$email=$_POST["email"];
	else
		$email="''";

	if (isset($_POST["formation"]))
		$formation=$_POST["formation"];
	else
		$formation="''";

	if (isset($_POST["naissance"]))
		$naissance=$_POST["naissance"];
	else
		$naissance="''";
?>

<div class="container">
    <div class="jumbotron">
        <img src='images/logo/inscription-logo.png' alt='inscription-logo.png' class='pageLogo'>
        <h1>Inscription</h1>
        <p>Créez votre compte.</p>
    </div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-6">
                    <div class="panel panel-info">
                    <div class="panel-heading"><span class="glyphicon glyphicon-pencil"></span> Inscription</div>
                    <div class="panel-body">
			<form action="index.php?page=inscription" method="POST" oninput="password2.setCustomValidity(password2.value != password.value ? 'Les mots de passe différent.' : '')">
				<p>
					<label for="login">Login : </label><br/>
					<input class="form-control" id="login" type="text" name="login" value=<?php echo "'$login'"; ?> required/>
				</p>
				<p>
					<label for="email">E-mail : </label><br/>
					<input class="form-control" type="email" id="email" name="email" value=<?php echo "'$email'"; ?> required/>
				</p>
				<p>
					<label for="password">Mot de passe : </label><br/>
					<input class="form-control" type="password" id="password" name="password" required>
				</p>
				<p>
					<label for="password2">Confirmez mot de passe : </label><br/>
					<input class="form-control" type="password" id="password2" name="password2" required>
				</p>
				<p>
					<label for="nom">Nom : </label><br/>
					<input class="form-control" type="text" id="nom" name="nom" value=<?php echo "'$nom'"; ?> required/>
				</p>
				<p>
					<label for="prenom">Prénom : </label><br/> 
					<input class="form-control" type="text" id="prenom" name="prenom" value=<?php echo "'$prenom'"; ?> required/>
				</p>
				<p>
					<label for="formation">Formation : </label><br/> 
					<input class="form-control" type="text" id="formation" name="formation" value=<?php echo "'$formation'"; ?>/>
				</p>
				<p>
					<label for="naissance">Date de naissance : </label><br/>
					<input class="form-control" type="date" id="naissance" name="naissance" value=<?php echo "'$naissance'"; ?> require/>
				</p>
				<p><input type="submit" value="Créer compte" class="btn btn-primary"></p>
			</form>
                    </div>
                    </div>
		</div>
	</div>
</div>

<?php
}
else{
	header('Location: index.php?page=accueil');
}
?>
<div class="container">
<div class="row">
<div class="col-md-4">
<h3>Inscription</h3>
<form action="?todo=register&page=accueil" method="POST" oninput="mdp2.setCustomValidity(mdp2.value != mdp.value ? 'Les mots de passe différent.' : '')">
	<p>
		<label for="login">Login : </label>
		<input id="login" type="text" name="login" required/>
	</p>
	<p>
		<label for="email">E-mail : </label> 
		<input type="email" id="email" name="email" required/>
	</p>
	<p>
		<label for="password1">Mot de passe : </label>
		<input type="password" id="password1" name="mdp" required>
	</p>
	<p>
		<label for="password22">Confirmez mot de passe : </label>
		<input type="password" id="password2" name="mdp2" required>
	</p>
	<p>
		<label for="nom">Nom : </label> 
		<input type="text" id="nom" name="nom" required/>
	</p>
	<p>
		<label for="prenom">Prénom : </label> 
		<input type="text" id="prenom" name="prenom" required/>
	</p>
	<p>
		<label for="formation">Formation : </label> 
		<input type="text" id="formation" name="formation"/>
	</p>
	<p>
		<label for="naissance">Naissance : </label> 
		<input type="date" id="naissance" name="naissance" required/>
	</p>
	<p><input type="submit" value="Créer compte" class="btn btn-primary"></p>
</form>
</div></div></div>
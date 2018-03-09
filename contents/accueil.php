<?php if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) { ?>

<div class="container">
    <div class="jumbotron">
        <h1>Accueil</h1>
        <p>Bienvenue sur FourbiX !</p>
    </div>
</div>

<?php
}

else {

?>

<div class="container">
    <div class="jumbotron">
        <h1>Accueil</h1>
        <p>Veuillez vous connecter pour accéder à l'ensemble du site.</p>
    </div>
</div>

<?php 

	printLoginForm($askedPage);
}

?>
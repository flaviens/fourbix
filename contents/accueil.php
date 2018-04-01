<?php if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) { ?>

<div class="container">
    <div class="jumbotron">
        <h1>Accueil</h1>
        <p>Bienvenue sur FourbiX !</p>
    </div>
    <h2>A quoi sert ce site ?</h2>
    <p>Ce site vous permet d'emprunter du matériel à des binets. Besoin d'un clavier parce que le vôtre est tombé en panne ? D'une clef USB pour votre soutenance ? Besoin d'une caméra pour un clip ? Ou encore de matériel de cuisine spécialisé pour votre repas de binet ? Venez trouver ici tout ce dont vous avez besoin !</p>
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
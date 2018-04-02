

<div class="container">
    <div class="jumbotron">
        <img src='images/logo/accueil-logo.png' alt='accueil-logo.png' class='pageLogo'>
        <h1>Accueil</h1>
        <p>Bienvenue sur <span class="nomSite"> FourbiX </span>!</p>
    </div>
    <h2>A quoi sert ce site ?</h2>
    <p class="accueil-content">Ce site vous permet d'emprunter du matériel à des binets. Besoin d'un clavier parce que le vôtre est tombé en panne ? D'une clef USB pour votre soutenance ? Besoin d'une caméra pour un clip ? Ou encore de matériel de cuisine spécialisé pour votre repas de binet ? Venez trouver ici tout ce dont vous avez besoin !</p>
    <h2>Comment utiliser ce site ?</h2>
    <p class="accueil-content">Vous pourrez effectuer vos recherches selon différents critères. Retrouvez-vous sur le <a href="index.php?page=catalogue">catalogue</a> pour retrouver ce que les binets ont à vous proposer en prêt. Vous pouvez également rechercher un item directement via la barre de recherche intégrée. En cliquant sur le nom de l'item, vous accéderez à sa page et vous pourrez en faire la demande au nom de votre binet ou à votre propre nom. Vous pouvez également directement vous rendre sur la page de vos binets pour gérer le matériel mis à disposition et les demandes qui vous ont été adressées. Enfin, vous pouvez directement voir l'état d'avancement de toutes vos demandes.</p>
    <h2>Un problème ?</h2>
    <p class='accueil-content'>N'hésitez pas à en référer un administrateur du Binet Réseau.</p>


<?php

if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
    echo <<< CHAINE_DE_FIN
    <h2>Connexion</h2>
    <p class='accueil-content'>Connectez-vous pour l'accès complet au site ou <a href='index.php?page=inscription'>inscrivez-vous.</a></p>

CHAINE_DE_FIN;
   printLoginForm($askedPage);
}

?>
    
</div>
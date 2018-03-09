<?php

function printLoginForm($askedpage){
    echo <<<CHAINE_DE_FIN
    <form action="index.php?todo=login&page=$askedpage" method="post">
        <p>Login : <input type="text" name="login" placeholder="login" required></p>
        <p>Password : <input type="password" name="password"></p>
        <p><input type="submit" value="Valider"</p>
    </form>
    
CHAINE_DE_FIN;
}

//inutile : c'est le bouton connexion qui permet de se déconnecter.
function printLogoutForm(){
    echo <<<CHAINE_DE_FIN
    <form action="index.php?todo=logout&page=accueil" method="post">
        <p><input type="submit" value="Se deconnecter" </p>
    </form>
CHAINE_DE_FIN;
}
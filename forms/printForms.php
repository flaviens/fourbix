<?php

function printLoginForm($askedpage){
    echo <<<CHAINE_DE_FIN
    <form action="index.php?todo=login&page=$askedpage" method="post">
        <p>
            <label for="login">Login : </label>
            <input type="text" name="login" id="login" placeholder="login" required>
        </p>
        <p>
            <label for="password">Password : </label>
            <input type="password" name="password" id="password" placeholder="password" required>
        </p>
        <p><input type="submit" value="Valider"</p>
    </form>
    
CHAINE_DE_FIN;
}

function printLogoutForm(){
    echo <<<CHAINE_DE_FIN
    <form action="index.php?todo=logout&page=accueil" method="post">
        <p><input type="submit" value="Se deconnecter" </p>
    </form>
CHAINE_DE_FIN;
}
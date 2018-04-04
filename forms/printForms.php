<?php

function printLoginForm($askedPage){
    global $validLogin;
    if (isset($_POST["login"])){
        $login=$_POST["login"];
        if (!$validLogin)
            echo "<div class='container'><span class='enregistrement-invalide'>Login invalide : login ou mot de passe incorrects</span></div><br/>";
    }
    else
        $login="''";
    echo <<<CHAINE_DE_FIN
    <div class="row">
    <div class="col-md-4">
    <div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-log-in"></span> Connexion</div>
    <div class="panel-body">
    <form action="index.php?todo=login&page=$askedPage" method="post">
        <p>
            <label for="login">Login : </label>
            <input class="form-control" type="text" name="login" id="login" placeholder="Login" value=$login required>
        </p>
        <p>
            <label for="password">Password : </label>
            <input class="form-control" type="password" name="password" id="password" placeholder="Mot de Passe" required>
        </p>
        <p><input type="submit" class="btn btn-primary" value="Valider"</p>
    </form>
    </div
    </div>
    </div>
    </div>
    
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

?>
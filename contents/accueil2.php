<?php

function printAccueil($isLogged, $askedpage){

    if(!$isLogged){
        echo <<<CHAINE_DE_FIN
    <div class="container">
       <div class="jumbotron">
           <h1>Accueil</h1>
           <p>Veuillez vous connecter pour accéder à l'ensemble du site.</p>
        </div>
    </div>

CHAINE_DE_FIN;
        
    printLoginForm($askedpage);
    } else{
         echo <<<CHAINE_DE_FIN
    <div class="container">
       <div class="jumbotron">
           <h1>Accueil</h1>
           <p>Bienvenue sur FourbiX !</p>
        </div>
    </div>

CHAINE_DE_FIN;
    }
}
    
    
?>
<div class="container">
    <div class="jumbotron">
        <img src='images/logo/demandes-logo.png' alt='demandes-logo.png' class='pageLogo'>
        <h1>Demandes</h1>
        <p>Consultez vos demandes en cours !</p>
    </div>
</div>

<?php

function printDemandesEnCours($dbh, $login){
    
    echo <<< CHAINE_DE_FIN
    <div class='col-md-6'>
        <div class="panel panel-warning">
            <div class="panel-heading center">Demandes en cours</div>
            <div class="panel-body">
                <table class="table table-striped table-bordered sortable">
            <thead class="thead-dark">
            <th scope="col" >Objet</th>
            <th scope="col" >Quantité</th>
            <th scope="col" >Binet de prêt</th>
            <th scope="col" >Au nom de</th>
            <th scope="col" >Dates</th>
            <th scope="col" >Supprimer</th>
                </thead>
                <tbody>
CHAINE_DE_FIN;
     
    genereDemandesEnCours($dbh, $login);
     
     echo <<< CHAINE_DE_FIN
        
        </tbody>
    </table>
    
CHAINE_DE_FIN;
    

    echo '</div></div></div>';
}

function genereDemandesEnCours($dbh, $login){
    $query="SELECT `id`, `item`, `binet`, `quantite`, `debut`, `fin`, `binet_emprunteur` FROM  `demandes` WHERE `utilisateur`=? AND `isAccepted`=0";
    $sth=$dbh->prepare($query);
    $sth->execute(array($login));
    $demandes=array();
    while ($demande=$sth->fetch()){
        array_push($demandes, $demande);
    }
    
    foreach ($demandes as $demande) {
        $query="SELECT `nom` FROM `items` WHERE `id`=?";   
        $sth=$dbh->prepare($query);
        $sth->execute(array($demande['item']));
        $nomItem=$sth->fetch();
        $nomBinet=$demande['binet'];
        echo "<tr><th>";
        echo htmlspecialchars($nomItem['nom']);
        echo"</th><td>";
        echo htmlspecialchars($demande['quantite']);
        echo "</td><td><a href='index.php?page=binet&pageBinet=$nomBinet'>";
        echo htmlspecialchars($nomBinet);
        echo "</a></td><td>";
        if ($demande['binet_emprunteur']!=NULL){
        echo htmlspecialchars($demande['binet_emprunteur']);
        } else{
            echo 'Personnel';
        }
        
        echo "</td><td>";
        if ($demande['debut']!=NULL){
            echo 'Debut : ';
            echo htmlspecialchars($demande['debut']);
            echo '<br/>';
        }
        if ($demande['fin']!=NULL){
            echo "Fin : ";
            echo htmlspecialchars($demande['fin']);
        }
        $demandeID=$demande['id'];
        echo <<< CHAINE_DE_FIN
        </td><td>
        <form action=index.php?page=demandes method=post>
            <input type='hidden' name='demandeID' value='$demandeID'>
            <input type='hidden' name='toDelete' value='true'>
            <input type=submit class="btn btn-danger toBeWarnedDelete" value="X" style="text-align:center" onclick="return confirm('Confirmer la suppression.');">
        </form>
        </td></tr>
        
CHAINE_DE_FIN;
       
    }
    
}

function deleteDemande($dbh, $demandeID){
    $sth=$dbh->prepare("DELETE FROM `demandes` WHERE `id`=?");
    return $sth->execute(array($demandeID));
    
}



function printPretsEnCours($dbh, $login){
    
    echo <<< CHAINE_DE_FIN
    <div class='col-md-6'>
        <div class="panel panel-warning">
            <div class="panel-heading center">Prêts en cours</div>
            <div class="panel-body center">
                <table class="table table-striped table-bordered sortable">
                <thead class="thead-dark">
                    <th scope="col" >Objet</th>
                    <th scope="col" >Quantité</th>
                    <th scope="col" >Binet de prêt</th>
                    <th scope="col" >Au nom de</th>
                    <th scope="col" >Deadline</th>
                </thead>
                <tbody>
CHAINE_DE_FIN;
     
    generePretsEnCours($dbh, $login);
     
     echo <<< CHAINE_DE_FIN
        
        </tbody>
    </table>
    
CHAINE_DE_FIN;
    

    echo '</div></div></div>';
}

function generePretsEnCours($dbh, $login){
    $query="SELECT `demande`, `quantite_pret`, `deadline` FROM  `pretoperation` WHERE  (`date_rendu` IS NULL OR `caution` IN (SELECT `id` FROM `cautions` WHERE `encaisse`=0)) AND `demande` IN (SELECT `id` FROM `demandes` WHERE `utilisateur`=?);";
    $sth=$dbh->prepare($query);
    $sth->execute(array($login));
    $prets=array();
    while ($pret=$sth->fetch()){
        array_push($prets, $pret);
    }
    
    foreach ($prets as $pret) {
        $query="SELECT `id`, `item`, `utilisateur`, `binet_emprunteur`, `binet` FROM  `demandes` WHERE `id`=?"; 
        $sth=$dbh->prepare($query);
        $sth->execute(array($pret['demande']));
        $demande=$sth->fetch();
        $query="SELECT `nom` FROM `items` WHERE `id`=?";   
        $sth=$dbh->prepare($query);
        $sth->execute(array($demande['item']));
        $nomItem=$sth->fetch();
        $nomBinet=$demande['binet'];
        echo "<tr><th>";
        echo htmlspecialchars($nomItem['nom']);
        echo"</th><td>";
        echo htmlspecialchars($pret['quantite_pret']);
        echo "</td><td><a href='index.php?page=binet&pageBinet=$nomBinet'>";
        echo htmlspecialchars($nomBinet);
        echo "</a></td><td>";
        if ($demande['binet_emprunteur']!=NULL){
        echo htmlspecialchars($demande['binet_emprunteur']);
        } else{
            echo 'Personnel';
        }
        
        echo "</td><td>";
        if ($pret['deadline']!=NULL){
            echo htmlspecialchars($pret['deadline']);
        } else{
            echo 'Sans contrainte';
        }
        echo '</td></tr>';
    }
}


function printPretsTermines($dbh, $login){
    
    echo <<< CHAINE_DE_FIN
    <div class='col-md-12'>
        <div class="panel panel-danger" data-toggle="collapse" data-target="#demande-form">
            <div class="panel-heading center isClickable"> Prêts terminés</div>
            <div class="panel-body panel-collapse collapse" id="demande-form">
    
CHAINE_DE_FIN;
    
    

    echo '</div></div></div>';
}

if (isset($_SESSION['login'])){
    $login=$_SESSION['login'];

?>
    
    <div class='container'>
        <div class='row'>
    
<?php

    if (isset($_POST['toDelete']) && isset($_POST['demandeID'])){
        if (DeleteDemande($dbh, $_POST["demandeID"])){
                echo "<div class='container'><span class='enregistrement-valide'>Déletion de la demande réussie !</span></div><br/>";
            } else{
                echo "<div class='container'><span class='enregistrement-invalide'>Erreur.</span></div><br/>";
            }
    }

    printDemandesEnCours($dbh, $login);
    
    printPretsEnCours($dbh, $login);
?>
        </div>
    </div>
 <?php
   
// echo <<< CHAINE_DE_FIN
// <br/>
//      <div class='container'>
//        <div class='row'>      
//CHAINE_DE_FIN;
//    printPretsTermines($dbh, $login);
//    echo '</div></div>';    
//

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
}
?>



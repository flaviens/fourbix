<?php

class Database {
    public static function connect() {
        require('password.php');
        $dsn = 'mysql:dbname=matos;host=127.0.0.1';
        $user = 'matos';
        $dbh = null;
        try {
            $dbh = new PDO($dsn, $user, $mysql_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
            exit(0);
        }
        return $dbh;
    }

    #Deja fait dans la classe utilisateur :
//    public static function insererUtilisateur($dbh,$login,$mdp,$nom,$prenom,$promotion,$naissance,$email,$feuille){ //A REFAIRE
//        $sth=$dbh->prepare("INSERT INTO `utilisateurs` (`login`, `mdp`, `nom`, `prenom`, `promotion`, `naissance`, `email`, `feuille`) VALUES(?,SHA1(?),?,?,?,?,?,?)");
//        $sth=$dbh->prepare("INSERT INTO `utilisateurs` (`login`, `mdp`, `nom`, `prenom`, `promotion`, `naissance`, `email`, `feuille`) VALUES(?,SHA1(?),?,?,?,?,?,?)");
//        $sth->execute(array($login,SHA1($mdp),$nom,$prenoms,$promotion,$naissance,$email,$feuille));
//    }

    public static function requete($dbh, $query, $tableau){ //effectuer une requête simple mais sécurisée)
        $sth=$dbh->prepare($query);
        $sth->execute($tableau);
        $result=$sth->fetch();
        return $result;
    }
}


// opérations sur la base
#$dbh = Database::connect();
#$dbh->query("INSERT INTO `utilisateurs` (`login`, `mdp`, `nom`, `prenom`, `promotion`, `naissance`, `email`, `feuille`) VALUES('moi',SHA1('nombril'),'bebe','louis','2005','1980-03-27','Marcel.Dupont@polytechnique.edu','modal.css')");
#$sth = $dbh->prepare("INSERT INTO `utilisateurs` (`login`, `mdp`, `nom`, `prenom`, `promotion`, `naissance`, `email`, `feuille`) VALUES(?,SHA1(?),?,?,?,?,?,?)");
#$sth->execute(array('SuperMarcel','Mystere','Marcel','Dupont','2005','1980-03-27','Marcel.Dupont@polytechnique.edu','modal.css'));
#$query="SELECT * FROM `utilisateurs` WHERE `naissance`>='1979-12-31' AND `naissance`<'1990-01-01'; ";
#$sth=$dbh->prepare($query);
#$result=$sth->execute();
#while ($courant=$sth->fetch(PDO::FETCH_ASSOC))
#        echo $courant['nom'].'<br>';
#$dbh = null; // Déconnexion de MySQL
?>

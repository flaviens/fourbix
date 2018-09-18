
<?php

require("utilities/CAS.php");

phpCAS::client(CAS_VERSION_2_0,'cas.binets.fr', 443, '/');
phpCAS::setNoCasServerValidation();

if (!phpCAS::isAuthenticated())
{
  phpCAS::forceAuthentication();
}else{
  phpCAS::forceAuthentication();

  //eader(‘page: accueil’); // TODO Changer celle-là
}


?>

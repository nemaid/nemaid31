<?php
include('includes/haut.php');
/*
 * Created on 13 mars 2013 by Thomas CROS
 * */


//si l'id est �gal � admin alors on permet l'acc�s au managment de la bdd
if ($_SESSION ['admin'] == 1 or $_SESSION ['user_id'] == 20) {
	
echo 'ok';
	
	
} 

else { echo "vous n'avez pas les droits suufisants pour vous connecter au Management de la base de donn�es";}


?>

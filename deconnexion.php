<?php
include('includes/haut.php');
connexion_bdd();
delete_user_files();
vider_cookie();
session_destroy();

$informations = Array(/*D�connexion*/
				false,
				'Deconnexion...',
				ROOTPATH.'/index.php',
				1
				);
require_once('informations.php');
exit();
?>
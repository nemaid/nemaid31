<?php
include('includes/functions.php');

if(isset($_POST)) {
	extract($_POST);
	
	/* file_type = champs caché dans les formulaires (au niveau des boutons submit)
	 * Permet de savoir quel type de fichier doit être sauvegardé
	 * parameters => fichier contenant les parametres de d'utilisateur
	 * sample => contient les données du sample a analyser
	 * genus => affiche juste un message d'information pour indiquer que le genre a bien été défini
	 * Les fichiers sont unique pour un user et supprimés à la déconnexion
	 */
	if ($file_type == 'parameters') {
		$dom=generate_xml_file($genus);
		 dl_file($dom);
		echo 'Your parameters have been saved on server.'; // <br /><a href="'.ROOTPATH.'/download.php?s=0">Click here to download</a> it on your own computer.';
		
	} elseif ($file_type == 'sample') {
		$genus_name = define_genus();
		if(isset($_SESSION['nb_sample_saved'])) $_SESSION['nb_sample_saved']++;
		else $_SESSION['nb_sample_saved'] = 1;	
		
		
		echo 'Your sample have been saved on server.'; // <br /><a href="'.ROOTPATH.'/download.php?s='.$_SESSION['nb_sample_saved'].'">Click here to download</a> it on your own computer.';
	} elseif ($file_type == 'genus') {
		if(isset($genus) && $genus != '') {
			$_SESSION['genus_n'] = $genus;
		}
	
		$informations = Array(false,'Genus set !',ROOTPATH.'/main.php',3);
		require_once('informations.php');
		exit();
	}
}
?>

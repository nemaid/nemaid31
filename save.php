<?php
include('includes/haut.php');

if(isset($_POST)) {
	extract($_POST);
	
	/* file_type = champs cach� dans les formulaires (au niveau des boutons submit)
	 * Permet de savoir quel type de fichier doit �tre sauvegard�
	 * parameters => fichier contenant les parametres de d'utilisateur
	 * sample => contient les donn�es du sample a analyser
	 * genus => affiche juste un message d'information pour indiquer que le genre a bien �t� d�fini
	 * Les fichiers sont unique pour un user et supprim�s � la d�connexion
	 */
	if ($file_type == 'parameters') {
		generate_xml_file($genus);
		echo 'Your parameters have been saved on server.'; // <br /><a href="'.ROOTPATH.'/download.php?s=0">Click here to download</a> it on your own computer.';
		
	} elseif ($file_type == 'sample') {
		$genus_name = define_genus();
		if(isset($_SESSION['nb_sample_saved'])) $_SESSION['nb_sample_saved']++;
		else $_SESSION['nb_sample_saved'] = 1;		
		save_user_sample($genus_name, $sample_id, $sample_date, $sample_loc, $sample_host, $remarks);
		
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

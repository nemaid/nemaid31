<?php
include('includes/haut.php');

if(isset($_GET['s'])) {
	if($_GET['s']) {
		$files_list = get_user_samples();
		echo $_GET['s']; exit();
		foreach($files_list as $id => $f) {
			if(substr_count($f,$_GET['s']."-user".$_SESSION['user_id']."_sample")) {
				$file = '/users_files/'.$f;
				$file_name = $f;
				break;
			}
		}
	} else {
		$file = '/users_files/user'.$_SESSION['user_id'].'_params.xml';
		$file_name = 'user'.$_SESSION['user_id'].'_params.xml';
	}
	
	if( ini_get('allow_url_fopen') ) {
		header("Content-disposition: attachment; filename=$file_name");  
		header("Content-type: application/octet-stream");  
		readfile(ROOTPATH.$file);
	} else {
		$informations = Array(true,'Server error... Impossible to download file.',ROOTPATH.'/main.php',2);
		require_once('informations.php');
		exit();
	}

} else {
	$informations = Array(true,'Error...',ROOTPATH.'/main.php',2);
	require_once('informations.php');
	exit();
}

?>
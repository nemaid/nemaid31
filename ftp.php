<?php include('includes/haut.php');

if(!(empty($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])) && isset($_POST)){


  $file = $_FILES['file']['tmp_name'];   // Le fichier t�l�vers�
  if($_POST['file_type'] == 'params') { 
	$dest = '/nemaid3/users_files/user'.$_SESSION['user_id'].'_params.xml'; // Sa destination
	
  } elseif($_POST['file_type'] == 'sample') {
	// Incr�mentation du nombre de fichier sample du user pr�sent sur le serveur
	if(!isset($_SESSION['nb_sample_saved'])) $_SESSION['nb_sample_saved'] = 1;
	else $_SESSION['nb_sample_saved']++;
	
	// Cr�ation du nom du nouveau fichier sample
	if(!substr_count($_FILES['file']['name'],$_SESSION['user_id']."_sample"))
		$dest = '/nemaid3/users_files/'.$_SESSION['nb_sample_saved'].'-user'.$_SESSION['user_id'].'_sample#'.$_FILES['file']['name']; // Sa destination
	else {
		if(substr_count(substr($_FILES['file']['name'],2),'-')) $dest = '/nemaid3/users_files/'.$_SESSION['nb_sample_saved'].'-'.substr($_FILES['file']['name'],2);
		else $dest = '/nemaid3/users_files/'.$_SESSION['nb_sample_saved'].'-'.$_FILES['file']['name'];
	}
  
  } else {
	$informations = Array(true,'Download not permitted.',ROOTPATH.'/main.php',5);
	require_once('informations.php');
	exit();
  }

  $conn_id = ftp_connect(FTP_SERVER);   // Cr�ation de la connexion au serveur FTP

  if(empty($conn_id)) {
    $informations = Array(true,'Download failed... Problems with ftp server (error f001). <br /> Please contact the administrator if this problem continues.',ROOTPATH.'/main.php',5);
	require_once('informations.php');
	exit();
  } else {
    // D�finition du d�lai de connexion
    ftp_set_option($conn_id, FTP_TIMEOUT_SEC, CONFIG_TIMEOUT);

    //echo 'Connect� au serveur FTP.<br/>';
        
    // Identification avec le nom d'utilisateur et le mot de passe
    $login_result = ftp_login($conn_id, FTP_USERNAME, FTP_PASSWORD);

    if(!$login_result) {
    //  echo '�chec d\'identification � ' . FTP_SERVER;
		$informations = Array(true,'Download failed... Problems with ftp server (error f002). <br /> Please contact the administrator if this problem continues.',ROOTPATH.'/main.php',5);
		require_once('informations.php');
		exit();
    } else {
      // Tentative de chargement sur le serveur FTP
      if(ftp_put($conn_id, $dest, $file, FTP_BINARY)) {		
		$informations = Array(false,'File downloaded with success.',ROOTPATH.'/main.php',3);
		require_once('informations.php');
		exit();
	  } else {
        $informations = Array(true,'Download failed... Problems with ftp server (error f003). <br /> Please contact the administrator if this problem continues.',ROOTPATH.'/main.php',5);
		require_once('informations.php');
		exit();
      }
	}
    // Fermeture de la connexion
    ftp_close($conn_id);
  }
} else {
	$informations = Array(true,'Download failed... Problems with ftp server (error f000). <br /> Please contact the administrator if this problem continues.',ROOTPATH.'/main.php',5);
	require_once('informations.php');
	exit();
}
?>

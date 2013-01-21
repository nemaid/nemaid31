<?php
/*
Neoterranos & LkY

Gère les informations (page incluse).

Liste des informations/erreurs :
--------------------------
Erreur interne
--------------------------
*/

if(!isset($informations)) {
	$informations = Array(
					true, // true => erreur		false => information
					'Une erreur interne est survenue...', // Message erreur/information
					ROOTPATH.'/index.php', // lien de redirection
					3 // temps avant redirection
					);
}

if($informations[0] === true) $type = 'erreur';
else $type = 'information';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>Nemaid 3.0</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="language" content="fr" />
		<meta http-equiv="Refresh" content="<?php echo $informations[3]; ?>;url=<?php echo $informations[2]; ?>">
	</head>
	
	<body>
		<div id="content">
			<div id="<?php echo $type; ?>">
				<?php echo $informations[1]; ?> <br/>
				Redirection in progress... <a href="<?php echo $informations[2]; ?>">Click here to be redirect faster</a>
			</div>
		</div>
	</body>
</html>
<?php
unset($informations);
?>
<?php
/*
 * Created on 19 mars 2013 by DRI
 *
 
 */
include("connectionSQL.php");
include("includes/haut.php");

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
	
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}



$Email = $_POST['email'];
$success = false;
$formError = false;
$password = generatePassword();

if(empty($_POST['email'])) {
	$formError = "true";
	$error = "Please enter your e-mail address.";
}else{
	$to = $Email;
	$subject = "Password Help";
	$message = "Hello<br/>Your new password is : " . $password . "<br> Renaud Fortuner" ;
	$from = "Renaud Fortuner <li.redpanda@gmail.com>";
	$headers  = 'MIME-Version: 1.0' . "\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
	$headers .= "From: $from";
	if(mail($to, $subject, $message, $headers));{
		$success = "true";
	}
}

$updatePassword = ("UPDATE users set password = '" . md5($password) ."' where email='" . $Email . "'");
if (mysql_query($updatePassword) && $sucess="true"){
		$informations = Array(true,'YPassword updated successfully.',ROOTPATH.'/index.php',2);
					require_once('informations.php');
					exit();
	}
	
	else {
		echo "Error updating record: " . mysql_error();
	}


?>

<?php

/*
 * Created on 19 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include('includes/haut.php');
include('connectionSQL.php');

$idref = $_POST['id_ref'];
$status = $_POST['status'];
$code_spe = $_POST['code_spe'];
$validity = $_POST['validity'];
$pop_type = $_POST['pop_type'];
// enregistrement des data
$LON = $_POST['LON'];
$STY = $_POST['STY'];
$DGO = $_POST['DGO'];
$EXPO = $_POST['EXPO'];
$BAW = $_POST['BAW'];
$TAIL = $_POST['TAIL'];
$TAN = $_POST['TAN'];
$PHAS = $_POST['PHAS'];
$a = $_POST['a'];
$c = $_POST['c'];
$c_bis = $_POST['c_bis'];
$m = $_POST['m'];
$v = $_POST['v'];
$SPIC = $_POST['SPIC'];
$MALES = $_POST['MALES'];
$DISC = $_POST['DISC'];
$CAN = $_POST['CAN'];
$HAB1 = $_POST['HAB1'];
$HAB2 = $_POST['HAB2'];
$LIP1 = $_POST['LIP1'];
$LIP2 = $_POST['LIP2'];
$INC1 = $_POST['INC1'];
$INC2 = $_POST['INC2'];
$LANN1 = $_POST['LANN1'];
$LANN2 = $_POST['LANN2'];
$LANN3 = $_POST['LANN3'];
$KBS1 = $_POST['KBS1'];
$KBS2 = $_POST['KBS2'];
$KBS3 = $_POST['KBS3'];
$TSH1 = $_POST['TSH1'];
$TSH2  = $_POST['TSH2'];
$TSH3  = $_POST['TSH3'];
$TSH4  = $_POST['TSH4'];
$GENB1  = $_POST['GENB1'];
$GENB2 = $_POST['GENB2'];
$GENB3 = $_POST['GENB3'];

//requete d'insertion de la ligne de données "data" dans la BDD
mysql_query("INSERT INTO`data` (
	id_data,	
	LON,	
	STY,	
	DGO,	
	EXPO,	
	BAW,
	TAIL,	
	TAN,	
	PHAS,	
	a,	
	c,	
	c_bis,	
	m,	
	v,	
	SPIC,	
	MALES,	
	DISC,	
	CAN,	
	HAB1,	
	HAB2,	
	LIP1,	
	LIP2,	
	INC1,	
	INC2,	
	LANN1,	
	LANN2,	
	LANN3,	
	KBS1,	
	KBS2,	
	KBS3,	
	TSH1,	
	TSH2,	
	TSH3,	
	TSH4,	
	GENB1,	
	GENB2,	
	GENB3)
	VALUES ('','$LON','$STY','$DGO','$EXPO','$BAW','$TAIL','$TAN','$PHAS','$a','$c','$c_bis','$m','$v','$SPIC','$MALES',
	'$DISC','$CAN','$HAB1','$HAB2','$LIP1','$LIP2','$INC1','$INC2','$LANN1',
'$LANN2','$LANN3','$KBS1','$KBS2','$KBS3','$TSH1','$TSH2','$TSH3','$TSH4','$GENB1','$GENB2','$GENB3')");

//révupération l'id data		
$id_data = mysql_insert_id();
//insertion dans la table define
$sQuery=("INSERT INTO `define` (id_def, validity, pop_type, code_spe, id_data, id_ref, status)
VALUES ('','$validity', '$pop_type', '$code_spe', '$id_data', '$idref', '$status');");

//test de la validité de la fonction SQL		
if (mysql_query($sQuery))
{
	echo ("<h2>Data saved successfully</h2>");
}
else
{
	echo "Error inserting record: " . mysql_error();
}	


?>

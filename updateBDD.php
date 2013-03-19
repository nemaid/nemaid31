<?php
/*
 * Created on 18 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include("connectionSQL.php");
include("includes/haut.php");


$iddata = $_POST['iddata'];
$result = mysql_query ("SELECT code_char from characters");

while($row = mysql_fetch_array($result)){
	$champ = $row['code_char']; 
	${$row['code_char']} = $_POST[$champ];
}


$update = ("UPDATE data set LON = '$LON',
STY = '$STY',
DGO = '$DGO', 
EXPO = '$EXPO', 
BAW= '$BAW', 
TAIL= '$TAIL', 
TAN= '$TAN', 
PHAS= '$PHAS', 
a= '$a', 
c= '$c', 
c_bis= '$c_bis', 
m= '$m', 
v= '$v', 
SPIC= '$SPIC', 
MALES= '$MALES', 
DISC= '$DISC', 
CAN= '$CAN', 
HAB1= '$HAB1', 
HAB2= '$HAB2', 
LIP1= '$LIP1', 
LIP2= '$LIP2', 
INC1= '$INC1', 
INC2= '$INC2', 
LANN1= '$LANN1', 
LANN2= '$LANN2', 
LANN3= '$LANN3', 
KBS1= '$KBS1', 
KBS2= '$KBS2', 
KBS3= '$KBS3', 
TSH1= '$TSH1', 
TSH2= '$TSH2', 
TSH3= '$TSH3', 
TSH4= '$TSH4', 
GENB1= '$GENB1', 
GENB2= '$GENB2', 
GENB3= '$GENB3'
WHERE id_data=$iddata");

if (mysql_query($update)){
		echo "<h3>Database updated successfully</h3>";
	}
	
	else {
		echo "Error updating record: " . mysql_error();
	}


?>

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


for ($i=1; $i<37; $i++) { 
$champ = "nbmenu".$i; 
${'value'.$i} = $_POST[$champ]; 
}


$result = mysql_query("UPDATE data set LON = $value1,
STY = $value2,
DGO = $value3, 
EXPO = $value4, 
BAW= $value5, 
TAIL= $value6, 
TAN= $value7, 
PHAS= $value8, 
a= $value9, 
c= $value10, 
c_bis= $value11, 
m= $value12, 
v= $value13, 
SPIC= $value14, 
MALES= $value15, 
DISC= $value16, 
CAN= $value17, 
HAB1= $value18, 
HAB2= $value19, 
LIP1= $value20, 
LIP2= $value21, 
INC1= $value22, 
INC2= $value23, 
LANN1= $value24, 
LANN2= $value25, 
LANN3= $value26, 
KBS1= $value27, 
KBS2= $value28, 
KBS3= $value29, 
TSH1= $value30, 
TSH2= $value31, 
TSH3= $value32, 
TSH4= $value33, 
GENB1= $value34, 
GENB2= $value35, 
GENB3= $value36
WHERE id_data=$iddata");

?>

<h3>Database updated successfully</h3>

<<<<<<< HEAD
<?php
/*
 * Created on 12 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 // On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");


$name = $_GET["specie"];


//requete
$result = mysql_query("select LON,
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
	GENB3
from species, define, data where specie='$name' AND species.code_spe=define.code_spe and data.id_data=define.id_data;");

echo "<table border='1'>
<tr>
<th>Species</th>
<th>Authors</th>
<th>Validity</th>
</tr>";


while($row = mysql_fetch_array($result))
{
	echo $row['LON'];
}





?>
=======
<?php
/*
 * Created on 12 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 

echo "JE TE MANGE LE MULET";
 
?>
>>>>>>> 4ef23e173e72d7e4733702efbba74208a28588b4

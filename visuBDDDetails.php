<?php
/*
 * Created on 12 mars 2013
 *
 * To change tde template for tdis generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 // On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");


$name = $_GET["specie"];


//requete
$result = mysql_query("SELECT LON,
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
FROM species, define, data WHERE specie='$name' AND species.code_spe=define.code_spe AND data.id_data=define.id_data;");

echo "<table border='1'>
<tr>
<th>Body lenght</th>";
while($row = mysql_fetch_array($result))
	{
		echo "<td>" . $row['LON'] . "</td>";
	}
echo "</tr>";
echo "</table>";
?>
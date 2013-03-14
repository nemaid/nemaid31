<?php
/*
 * Created on 12 mars 2013
 *
 * To change tde template for tdis generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 // On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("includes/functions.php");


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

$data = mysql_fetch_array($result);


$value = get_value_by_key($data, "LON");
print_r($data);


echo "<table border='1'>
<tr>
<th>Body lenght</th>";
foreach ($data as $key => $value)
{	
	if ($key == "LON"){
		echo "<td>" . $value . "</td>";
	}
}
echo "</tr>";

echo "<tr>
<th>Stylet lenght</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['STY'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Position of dorsal gland opening</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['DGO'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Position of excretory pore</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['EXPO'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Width of body annules</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['BAW'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Tail length</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TAIL'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Number of tail annules (ventral side)</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TAN'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Position of phasmids</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['PHAS'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Ratio a</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['a'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Ratio b</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['b'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Ratio c</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['c'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Ratio c bis</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['c_bis'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Ratio m</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['m'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Ratio V</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['v'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Spicule lenght</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['SPIC'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Presence/absence of males</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['MALES'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Labial disc	Intestinal canals (fasciculi)</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['DISC'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Labial disc	Intestinal canals (fasciculi)</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['CAN'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Habitus 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['HAB1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Habitus 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['HAB2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Lip shape 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LIP1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Lip shape 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LIP2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Fusion of inner lateral field lines (incisures) on tail 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['INC1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Fusion of inner lateral field lines (incisures) on tail 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['INC2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Number of lip annules 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LANN1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Number of lip annules 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LANN2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Number of lip annules 3</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LANN3'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of anterior face of stylet knobs 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['KBS1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of anterior face of stylet knobs 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['KBS2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of anterior face of stylet knobs 3</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['KBS3'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of tail 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of tail 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of tail 3</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH3'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Shape of tail 4</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH4'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Genital branches 1</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['GENB1'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Genital branches 2</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['GENB2'] . "</td>";
}
echo "</tr>";

echo "<tr>
<th>Genital branches 3</th>";
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['GENB3'] . "</td>";
}
echo "</tr>";

echo "</table>";


?>
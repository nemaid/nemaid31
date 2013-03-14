<?php
/*
 * Created on 12 mars 2013
 *
 * To change tde template for tdis generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 // On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");



//requete
function request($char, $name){
	$result = mysql_query("SELECT $char FROM species, define, data WHERE specie='$name' AND species.code_spe=define.code_spe AND data.id_data=define.id_data;");
	return $result;
}
echo "<table border='1'>
<tr>
<th>Body lenght</th>";

$name = $_GET["specie"];
$result = request("LON", $name);
while ($row = mysql_fetch_array($result))
{	
	echo "<td>" . $row['LON'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);

echo "<tr>
<th>Stylet lenght</th>";
$result = request("STY", $name);
while($row = mysql_fetch_array($result))
		{
	echo "<td>" . $row['STY'] . "</td>";
		}
echo "</tr>";
mysql_free_result($result);

echo "<tr>
<th>Position of dorsal gland opening</th>";
$result = request("DGO", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['DGO'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);

echo "<tr>
<th>Position of excretory pore</th>";
$result = request("EXPO", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['EXPO'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);

echo "<tr>
<th>Width of body annules</th>";
$result = request("BAW", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['BAW'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Tail length</th>";
$result = request("TAIL", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TAIL'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Number of tail annules (ventral side)</th>";
$result = request("TAN", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TAN'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Position of phasmids</th>";
$result = request("PHAS", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['PHAS'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Ratio a</th>";
$result = request("a", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['a'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Ratio c</th>";
$result = request("c", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['c'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Ratio c bis</th>";
$result = request("c_bis", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['c_bis'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Ratio m</th>";
$result = request("m", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['m'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Ratio V</th>";
$result = request("v", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['v'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Spicule lenght</th>";
$result = request("SPIC", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['SPIC'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Presence/absence of males</th>";
$result = request("MALES", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['MALES'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Labial disc	Intestinal canals (fasciculi)</th>";
$result = request("DISC", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['DISC'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Labial disc	Intestinal canals (fasciculi)</th>";
$result = request("CAN", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['CAN'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Habitus 1</th>";
$result = request("DGO", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['HAB1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Habitus 2</th>";
$result = request("HAB2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['HAB2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Lip shape 1</th>";
$result = request("LIP1", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LIP1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Lip shape 2</th>";
$result = request("LIP2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LIP2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Fusion of inner lateral field lines (incisures) on tail 1</th>";
$result = request("INC1", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['INC1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Fusion of inner lateral field lines (incisures) on tail 2</th>";
$result = request("INC2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['INC2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Number of lip annules 1</th>";
$result = request("LANN1", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LANN1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Number of lip annules 2</th>";
$result = request("LANN2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LANN2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Number of lip annules 3</th>";
$result = request("LANN3", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['LANN3'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of anterior face of stylet knobs 1</th>";
$result = request("KBS1", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['KBS1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of anterior face of stylet knobs 2</th>";
$result = request("KBS2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['KBS2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of anterior face of stylet knobs 3</th>";
$result = request("KBS3", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['KBS3'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of tail 1</th>";
$result = request("TSH1", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of tail 2</th>";
$result = request("TSH2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of tail 3</th>";
$result = request("TSH3", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH3'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Shape of tail 4</th>";
$result = request("TSH4", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['TSH4'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Genital branches 1</th>";
$result = request("GENB1", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['GENB1'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Genital branches 2</th>";
$result = request("GENB2", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['GENB2'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "<tr>
<th>Genital branches 3</th>";
$result = request("GENB3", $name);
while($row = mysql_fetch_array($result))
{
	echo "<td>" . $row['GENB3'] . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "</table>";


?>
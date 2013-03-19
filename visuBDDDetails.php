<?php
/*
 * Created on 12 mars 2013
 *
 * To change tde template for tdis generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 // On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("includes/haut.php");


//requete
function request($char, $idData){
	$result = mysql_query("SELECT $char FROM data where id_data=$idData");
	return $result;
}

$idRef = $_GET["idref"];
$result2 = mysql_query("SELECT title FROM `references` where id_ref=$idRef");

$title = mysql_fetch_assoc($result2);
$titre = $title['title'];


	echo "<h2>Description based on the article : </h2>  </br> <h3>$titre </h3>";
?>

<form action="updateBDD.php" method="post">


<?php
echo "<table border='1'>
<tr>
<th>Body lenght</th>";

$idData = $_GET["iddata"];
echo "<input type='hidden' name='iddata' value=$idData> ";
$result = request("LON", $idData);
while ($row = mysql_fetch_array($result))
{	
	$value = $row['LON'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu1' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";
mysql_free_result($result);

echo "<tr>
<th>Stylet lenght</th>";
$result = request("STY", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['STY'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu2' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Position of dorsal gland opening</th>";
$result = request("DGO", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['DGO'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu3' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Position of excretory pore</th>";
$result = request("EXPO", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['EXPO'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu4' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);

echo "<tr>
<th>Width of body annules</th>";
$result = request("BAW", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['BAW'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu5' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Tail length</th>";
$result = request("TAIL", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['TAIL'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu6' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Number of tail annules (ventral side)</th>";
$result = request("TAN", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['TAN'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu7' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Position of phasmids</th>";
$result = request("PHAS", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['PHAS'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu8' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Ratio a</th>";
$result = request("a", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['a'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu9' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Ratio c</th>";
$result = request("c", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['c'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu10' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Ratio c bis</th>";
$result = request("c_bis", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['c_bis'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu11' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Ratio m</th>";
$result = request("m", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['m'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu12' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Ratio V</th>";
$result = request("v", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['v'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu13' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Spicule lenght</th>";
$result = request("SPIC", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['SPIC'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu14' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Presence/absence of males</th>";
$result = request("MALES", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['MALES'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu15' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Labial disc	Intestinal canals (fasciculi)</th>";
$result = request("DISC", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['DISC'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu16' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Labial disc	Intestinal canals (fasciculi)</th>";
$result = request("CAN", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['CAN'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu17' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Habitus 1</th>";
$result = request("HAB1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['HAB1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu18' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Habitus 2</th>";
$result = request("HAB2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['HAB2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu19' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Lip shape 1</th>";
$result = request("LIP1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['LIP1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu20' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Lip shape 2</th>";

$result = request("LIP2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['LIP2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu21' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Fusion of inner lateral field lines (incisures) on tail 1</th>";
$result = request("INC1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['INC1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu22' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Fusion of inner lateral field lines (incisures) on tail 2</th>";
$result = request("INC2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['INC2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu23' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Number of lip annules 1</th>";
$result = request("LANN1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['LANN1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu24' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Number of lip annules 2</th>";
$result = request("LANN2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['LANN2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu25' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Number of lip annules 3</th>";
$result = request("LANN3", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['LANN3'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu26' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of anterior face of stylet knobs 1</th>";
$result = request("KBS1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['KBS1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu27' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of anterior face of stylet knobs 2</th>";
$result = request("KBS2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['KBS2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu28' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of anterior face of stylet knobs 3</th>";
$result = request("KBS3", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['KBS3'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu29' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of tail 1</th>";
$result = request("TSH1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['TSH1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu30' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of tail 2</th>";
$result = request("TSH2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['TSH2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu31' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of tail 3</th>";
$result = request("TSH3", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['TSH3'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu32' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Shape of tail 4</th>";
$result = request("TSH4", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['TSH4'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu33' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Genital branches 1</th>";
$result = request("GENB1", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['GENB1'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu34' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Genital branches 2</th>";
$result = request("GENB2", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['GENB2'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu35' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";

mysql_free_result($result);
echo "<tr>
<th>Genital branches 3</th>";
$result = request("GENB3", $idData);
while($row = mysql_fetch_array($result))
{
	$value = $row['GENB3'];
	echo "<td>" . "<INPUT TYPE='text' name='nbmenu36' size='2' value='$value'>"  . "</td>";
}
echo "</tr>";
mysql_free_result($result);
echo "</table>";


if($_SESSION['admin'] == 1){
?>

<button type = 'submit' name ='update' action="updateBDD.php" target="_blank">Update</button>
</form>

<?php
}
?>
<?php
/*
 * Created on 18 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
include("connectionSQL.php");
include("includes/haut.php");


$name = $_GET["specie"];

$result = mysql_query("SELECT pop_type, define.id_ref, id_data, author, year, define.code_spe, specie FROM define, `references`, species WHERE specie='$name' AND species.code_spe = define.code_spe AND define.id_ref = `references`.id_ref");

echo "<h3> $name <h3>";

echo "<table border='1'>
<tr>
<th>Population type</th>
<th>Author(s)</th>
<th>Year</th>
<th>Link to data</th>
</tr>";


while($row = mysql_fetch_array($result))
{
	echo "<tr>";
	echo "<td>" .$row['pop_type'] . "</td>";
	echo "<td>" .$row['author'] . "</td>";
	echo "<td>" .$row['year'] . "</td>";
	
	$data = $row['id_data'];
	$nameURL = "view data";
	$url = "visuBDDDetails.php?iddata=";
	$url .= $data;
	echo "<td>" ."<a href=$url target=$target>$nameURL</a>" . "</td>";
	echo "</tr>";
}
echo "</table>";



?>

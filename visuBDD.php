<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");


$result = mysql_query("SELECT specie from species");

echo "<table border='1'>
<tr>
<th>Species</th>
</tr>";


while($row = mysql_fetch_array($result))
  {
  	
  	$nameSpecies = $row['specie'];
  	$url = "visuBDDBySpecies.php?specie=";
  	$url .= $nameSpecies;
  echo "<tr>";
  echo "<td>" ."<a href=$url target='_blank'>$nameSpecies</a>" . "</td>";
  echo "</tr>";
  }
echo "</table>";
?>

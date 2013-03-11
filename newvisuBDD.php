<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

$url = "http://www.google.fr";
$target = "_blank";
$site_title="google";

$result = mysql_query("SELECT define.code_spe, specie, authors, years, validity
				FROM `species`
				LEFT OUTER JOIN `define` ON define.code_spe = species.code_spe
				GROUP BY specie");

echo "<table border='1'>
<tr>
<th>Test</th>
<th>Species</th>
<th>Authors</th>
<th>Validity</th>
</tr>";


while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" ."<a href=$url target=$target>$site_title</a>" . "</td>";
  echo "<td>" . $row['specie'] . "</td>";
  echo "<td>" . $row['authors'] . "</td>";
  if ($row['validity'] == 1){ 
  	echo "<td>" . "true"  . "</td>";
  }
   else {
  	echo "<td>" . "false" . "</td>";
  	}	
  echo "</tr>";
  }
echo "</table>";
?>


		
		
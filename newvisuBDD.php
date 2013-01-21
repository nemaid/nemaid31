<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");


$result = mysql_query("SELECT define.id_ref, define.code_spe, specie, species.name_genus, description, author, publi_in, title, validity, notes
				FROM `species`
				LEFT OUTER JOIN `define` ON define.code_spe = species.code_spe
				LEFT OUTER JOIN `references` ON define.id_ref = references.id_ref
				GROUP BY specie");

echo "<table border='1'>
<tr>
<th>Species</th>
<th>Authors</th>
<th>Validity</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['specie'] . "</td>";
  echo "<td>" . $row['author'] . "</td>";
  echo "<td>" . $row['validity'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
?>


		
		
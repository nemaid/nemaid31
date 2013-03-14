<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

//on récupère l'id de connexion 
//$id = session_id();
//si l'id est égal à admin alors on permet l'accès au managment de la bdd
//if (($id) == ("ciret.m@gmail.com")) {
// création du bouton de redirection vers le mangement de la BDD
?>
<input type="button" name="DB Management" value="Return" onClick="javascript:document.location.href='BDDManagement.php'" />
<?php
//}

$result = mysql_query("SELECT define.code_spe, specie, authors, years, validity
				FROM `species`
				LEFT OUTER JOIN `define` ON define.code_spe = species.code_spe
				GROUP BY specie");

echo "<table border='1'>
<tr>
<th>Species</th>
<th>Authors</th>
<th>Validity</th>
</tr>";


while($row = mysql_fetch_array($result))
  {
  	
  	$nameSpecies = $row['specie'];
  	$url = "visuBDDDetails.php?specie=";
  	$url .= $nameSpecies;
  echo "<tr>";
  echo "<td>" ."<a href=$url target=$target>$nameSpecies</a>" . "</td>";
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

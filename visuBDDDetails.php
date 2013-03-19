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

$character = mysql_query("SELECT code_char, name_char, explanations from characters");

$title = mysql_fetch_assoc($result2);
$titre = $title['title'];


	echo "<h2>Description based on the article : </h2>  </br> <h3>$titre </h3>";
?>

<form action="updateBDD.php" method="post">


<?php
echo "<table border='1'>";
while ($rowChar = mysql_fetch_array($character)){
	echo "<tr>";
	echo "<th>" . $rowChar['name_char'] . "</th>";
	$idData = $_GET["iddata"];
	echo "<input type='hidden' name='iddata' value=$idData> ";
	$result = request($rowChar['code_char'], $idData);
	while ($row = mysql_fetch_array($result))
		{	
			$value = $row[$rowChar['code_char']];
			echo "<td>" . "<INPUT TYPE='text' name='" . $rowChar['code_char']. "' size='2' value='$value'>"  . "</td>";
		}
echo "</tr>";
}
echo "</table>";


if($_SESSION['admin'] == 1){
?>

<button type = 'submit' name ='update' action="updateBDD.php" target="_blank">Update</button>
</form>

<?php
}
?>
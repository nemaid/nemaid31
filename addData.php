<?php
include('includes/haut.php');
include('connectionSQL.php');
/*
 * Created on 19 mars 2013 by Thomas CROS
 *
 * 
 * 
 */
 //on r�cup�re le code de l'esp�ce
 $code_spe = $_POST['code_spe'];


if(isset ($_POST['id_ref'])){
	$id_ref = $_POST['id_ref'];
	

}

else {
//on r�cup�re toutes les donn�es de la r�f�rences cr�ee pr�lalblement	
$author = $_POST['author'];
$year = $_POST['year'];
$publi_in = $_POST['publi_in'];
$title = $_POST['title'];
$journal = $_POST['journal'];

//requete d'insertion dans la base de la ligne de r�f�rence
mysql_query("INSERT INTO `references` (id_ref, author, year, publi_in, title, journal) VALUES ('', '$author', '$year', '$publi_in', '$title', '$journal')");

$id_ref = mysql_insert_id();
}
 

 $code_spe = $_POST['code_spe'];
 
 $result = mysql_query ("SELECT code_char, name_char, explanations from characters");
 
?>

<form action="addDefine.php" method="post">
<?php
echo "<input type = 'hidden' name = 'code_spe' value = $code_spe>";
echo "<input type = 'hidden' name = 'id_ref' value = $id_ref>";

echo "<table border = '1'>";
echo "<tr>";
echo"<th> Status </th>";
echo "<td> Enter the status of the definition </td>";
echo"<td>" . "<input type = 'text' name ='status'> </td>";
echo "</tr>"; 
while ($row = mysql_fetch_array($result))
{
	echo "<tr>";
	echo "<th>" . $row['name_char'] . "</th>";
	echo "<td>" . $row['explanations'] . "</td>";
	echo "<td>" . "<input type = 'text' name = '" . $row['code_char'] . "'> </td>";
	echo "</tr>";
}



echo "</table>";
?>	
<br>
Data are valid : <input type="checkbox" defaultChecked name="validity" value="1" />
<br>
Description on a population type : <input type="checkbox" defaultChecked name="pop_type" value="T" />
<br>
<input type="submit"  name="submitAddData" action="addDefine.php" value="Submit"  />
</form>




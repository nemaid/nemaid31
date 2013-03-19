<?php
/*
 * Created on 19 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
include('includes/haut.php');
include('connectionSQL.php');

if(isset ($_POST['id_spe'])){
	$code_spe = $_POST['id_spe'];
}

else {
	
}




?>

<h2> Add a new reference or choose a existing one in the list</h2>

</br>

<h3> Add a new reference </h3> <br>

<form action="addData.php" method="post">

<table  border="no">
	<tr>
		<td>Author(s) : </td>
		<td><input required type="text" name="author" size = "100" ></td>
	</tr>
	<tr>
		<td>Year :</td> 
		<td><input type="text" name="year" size = "100" ></td>
	</tr>
	<tr>
		<td>Publi in :</td> 
		<td><input type="text" name="publi_in" size = "100" ></td>
	</tr>
	<tr>
		<td>Title : </td>
		<td><input required type="text" name="title" size = "100"></td>
	</tr>
	<tr>
		<td>Journal : </td>
		<td><input type="text" name="journal" size = "100" ></td>
	</tr>
	</table>

<input type="submit" name="submitAddReference" value="Submit"  />
</form>

<br>
<br>

<h3> Select a reference </h3> <br>
<form action="addData.php" method="post">
<select id="id_ref"  onchange="document.getElementById('id_ref_content').value=this.options[this.selectedIndex].value">
<option name = "default" value = ""> --Choose a reference--</option>

<?php

$reference =mysql_query("SELECT id_ref, title from `references`");
while ($result = mysql_fetch_array($reference))
{	
	$title = $result['title'];
	
	echo '<option name = "id_ref" value='. $result['id_ref']. '>' . $title . '</option>' ;
}        

?>
</select>
<input type="hidden" name="id_ref" id="id_ref_content" value="" />
<br>
<input type="submit"  name="submitGetReference" action="addData.php" value="Submit"  />
</form>



<?php
include('includes/haut.php');
include('connectionSQL.php');
/*
 * Created on 13 mars 2013 by Thomas CROS
 * */

//si l'id est égal à admin alors on permet l'accès au managment de la bdd dans le header

echo "<h2> Create a new species or select an existing species in the list </h2>";

?>
 <form action = "addReference.php" method = "post">
 
<?php
// création nouvelle espèce d'un genre'
echo "<h3> Create a new species </h3>";
echo "Name of the new species ";
echo '<tr>';
echo '	<td><SUP title="NameSpecies"></SUP></td>';
echo '	<td><input type="text" name="specie"></td>';
echo '</tr>';
$namespe = $_POST['specie'];

//Selection du genre de l'espèce			



$recuperation_species = mysql_query("SELECT name_genus from genera");

?>
        
        <br>
        <br>
        Genus of the new Species :
        <select name="name_genus" >
        <!--<option selected="selected"></option> -->
        
<?php
while ($tableau_client = mysql_fetch_array($recuperation_species))
{
	echo '<option value='. $tableau_client['name_genus']. '>' . $tableau_client['name_genus'] . '</option>' ;
}        



?>
        </select>
        <input type="submit" name="submitAddSpecies" action="addReference.php" value="Submit"  />
        </form>
 <form action = "addReference.php" method = "post">        
         

<?php 
echo "<h3> Select an existing species </h3>";      
$recuperation_species = mysql_query("SELECT code_spe, specie from species ORDER BY specie COLLATE latin1_german2_ci");

?>
        
        name of the existing species :
       <select id="id_spe" onchange="document.getElementById('id_spe_content').value=this.options[this.selectedIndex].value">
		  <option name="default" value="">--Choose a species--</option> -->
  
<?php
while ($tableau_species = mysql_fetch_array($recuperation_species))
{
	echo '<option value='. $tableau_species['code_spe']. '>' . $tableau_species['specie'] . '</option>' ;
}        


?>
</select>
</select>
<input type="hidden" name="id_spe" id="id_spe_content" value="" />
<input type="submit" name="submitAddSpecies" value="Submit"  />
</form>

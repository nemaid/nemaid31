<?php
include('includes/haut.php');
include('connectionSQL.php');
/*
 * Created on 13 mars 2013 by Thomas CROS
 * */

//si l'id est égal à admin alors on permet l'accès au managment de la bdd
echo "Code of the new species";
echo '<tr>';
					
		  			    echo '<input type="text" name="CodeSpecies"/><br/>';
     
echo '</tr>';
  $codespe = $_POST['CodeSpecies']; 

// création nouvelle espèce d'un genre'
echo "Name of the new species";
echo '<tr>';
						echo '	<td><SUP title="NameSpecies"></SUP></td>';
						echo '	<td><input type="text" name="NameSpe"></td>';
echo '</tr>';
  $namespe = $_POST['NameSpe'];

//Selection du genre de l'espèce'			


       
 $recuperation_species = mysql_query("SELECT name_genus from genera");
        
        ?>
        
        <br>
        <br>
        <form method="post" action="BDDManagement.php">
        Genus of the new Species :
        <select name="choix" >
        <!--<option selected="selected"></option> -->
        
        <?php
                        while ($tableau_client = mysql_fetch_array($recuperation_species))
                        {
                                echo '<option value='. $tableau_client['name_genus']. '>' . $tableau_client['name_genus'] . '</option>' ;
                        }        
                        
                        $recuperation_species->closeCursor();
        
        //$insert_codeSpe = mysql_query("INSERT INTO species (code_spe, specie, name_genus)
		//VALUES ('$codespe, $namespe, $NameGenus')");
        
        
        ?>
        </select>
        

	

	

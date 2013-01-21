<?php 
include('includes/haut.php');
if(!file_exists("default_params.xml")) generate_xml_file('default');
?>
<div>
	<h2>Choose one of the available genera :</h2>
	
	<form action="save.php" method="post">

		<input type="radio" name="genus" value="heli1" onclick="show('listHeli')" checked>Helicotylenchus (Steiner, 1945)</br>
			<select id="listHeli">
				<option value="heli1" <?php if($_SESSION['genus_n'] == 'heli1') echo 'checked="true"'; ?> >Helicotylenchus s.l. [sensu Fortuner, 1984]</option>
				<option value="heli2" <?php if($_SESSION['genus_n'] == 'heli2') echo 'checked="true"'; ?> >Helicotylenchus s.str. [2 genital branches equally developed]</option>
				<option value="heli3" <?php if($_SESSION['genus_n'] == 'heli3') echo 'checked="true"'; ?> >Rotylenchoides sensu Siddiqi & Husain, 1964 [1 anterior branch and a PUS]</option>
				<option value="heli4" <?php if($_SESSION['genus_n'] == 'heli4') echo 'checked="true"'; ?> >Rotylenchoides sensu Sher, 1965 [1 ant. branch and post. branch either reduced in size or PUS]</option>
			</select>
			</br>
			</br>
		<input type="radio" name="genus" value="apha" onclick="hide('listHeli')"<?php if($_SESSION['genus_n'] == 'apha') echo 'checked="true"'; ?> >Aphasmatylenchus Sher, 1965
		</br>
		</br>
		<input type="hidden" name="file_type" value="genus">
		<input type="submit" value="Set the genus">
	</form>
</div>

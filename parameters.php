<?php
include('includes/haut.php');
connexion_bdd();

$genus_name = define_genus();
?>

<script type="text/javascript">
	function allto1($type) {
		var inputs = document.getElementsByTagName('input');
		for(var i=0; i<inputs.length; i++) {
			if(inputs[i].className == $type) { inputs[i].value = 1; }
		}
	}
	
	function getXMLHttpRequest() {
		var xhr = null;
		if (window.XMLHttpRequest || window.ActiveXObject) {
			if (window.ActiveXObject) {
				try  {
					xhr = new ActiveXObject("Msxm12.XMLHTTP");
				} catch(e) {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
			} else {
				xhr = new XMLHttpRequest();
			}
		} else {
			alert("Votre navigateur ne supporte pas l'objet XMLHttpRequest...");
			return null;
		}
		return xhr;
	}
	
	var xhr = getXMLHttpRequest();
	xhr.open("GET", "default_params.xml", false);
	xhr.send(null);
	var xmldoc = xhr.responseXML;
	
	function default_values($type) {
		if ('<?php echo $genus_name ?>' == 'Helicotylenchus' ) {
			var values = xmldoc.getElementsByTagName('genus')[1].getElementsByTagName('char');
		} else {
			var values = xmldoc.getElementsByTagName('genus')[0].getElementsByTagName('char');
		}

		var res = new Array();

		for (var i=0; i<values.length; i++) {
			var length = values[i].childNodes.length;
			var input_name = values[i].getAttribute('name');
			switch ($type) {
				case 'qt_weight': 
					if (length > 3) {
						res[i] = values[i].childNodes[1].firstChild.nodeValue;
						input_name = values[i].getAttribute("name").concat('_w');
					} break;
				case 'qt_correction': 
					if (length > 3) {
						res[i] = values[i].childNodes[3].firstChild.nodeValue;
						input_name = values[i].getAttribute("name").concat('_c');
					} break;
				case 'qt_rangeValue': 
					if (length > 3) {
						res[i] = values[i].childNodes[5].firstChild.nodeValue;
						input_name = values[i].getAttribute("name").concat('_r');
					} break;
				case 'ql_weight': 
					if (length == 3) {
						res[i] = values[i].childNodes[1].firstChild.nodeValue;
						input_name = values[i].getAttribute("name").concat('_w');
					} break;
			}
			
			var inputs = document.getElementsByTagName('input');
			for(var j=0; j<inputs.length; j++) {
				if (inputs[j].name == input_name) { inputs[j].value = res[i]; }
			}
		}
	}
	
	function verif_champs() {
		var inputs = document.getElementsByTagName('input');

		for(var i=0; i<inputs.length; i++) {
			if(inputs[i].type == 'text' && inputs[i].value == '') {
				alert("Error: All fields must be filled."); 
				return false;
			}
		}
		
		return true;
	}
</script>

<div id="parameters">
	<h2><?php echo $genus_name; ?> settings</h2>
	<ul>
	<li><h3>Upload a previously saved file: </h3></li>
		<form action="ftp.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file" />
			<input type="hidden" name="file_type" value="params" />
			<input type="submit" value="Upload" />
		</form>
		This file must be a .xml file generated by Nemaid 3.1.
	<br /><br />
	<li><h3>Or set your own parameters now:</h3></li>
	<form onsubmit='return verif_champs();' action="save.php" method="post">
		<table>
			<tr><th><input type="hidden" name="genus" value="<?php echo $genus_name; ?>"></th></tr>
			<tr><th>Quantitative characters parameters</th></tr>
			<tr>
				<th>Characters</th>
				<th>Weights</th>
				<th>Correction factors</th>
				<th>Range</th>
			</tr>
			
			<?php
				extract($_POST);
				if (file_exists("users_files/user".$_SESSION['user_id']."_params.xml") && get_xml_data('genus') == $genus_name) {
					$use_user_params = true; $user_params = get_xml_data('user_params');
					//echo "test";
				} else {
					$use_user_params = false;
					//echo "testfalse";
				}
				
				$query = mysql_query('SELECT code_char, name_char, weight, correction, explanations, max-min as rangeValue
									  FROM characters 
									  WHERE name_genus = "'.$genus_name.'" AND correction IS NOT NULL');

				while($row = mysql_fetch_assoc($query)){
					if($use_user_params) {
						$weight = $user_params[(string)$row["code_char"]]['weight'];
						$correction = $user_params[(string)$row["code_char"]]['correction'];
						$rangeValue = $�[(string)$row["code_char"]]['rangeValue'];
					} else {
						$weight = sprintf('%.2f',round($row["weight"],2));
						$correction = sprintf('%.2f',round($row["correction"],2));
						$rangeValue = sprintf('%.2f',round($row["rangeValue"],2));
					}
					echo '<tr>';
					echo '	<td>'.$row['name_char']; if ($row["explanations"] != NULL) echo '<SUP style="cursor:default;" title="'.$row['explanations'].'">?</SUP></td>';
					echo '	<td><input class="qt_weight" type="text" name="'.$row["code_char"].'_w" value="'.$weight.'"></td>';
					echo '	<td><input class="qt_correction" type="text" name="'.$row["code_char"].'_c" value="'.$correction.'"></td>';
					echo '	<td><input class="qt_rangeValue" type="text" readonly="readonly" name="'.$row["code_char"].'_r" value="'.$rangeValue.'"></td>';
					echo '</tr>';
				}
			?>
			<tr>
				<th></th>
				<td>
					<input type="button" value="Set all to 1" onclick="allto1('qt_weight')">
					<br/>
					<input type="button" value="Defaults values" onclick="default_values('qt_weight')">
				</td>
				<td>
					<input type="button" value="Defaults values" onclick="default_values('qt_correction')">
				</td>
				<td>
					<input type="button" value="Calculate range" onclick="default_values('qt_rangeValue')">
				</td>
			</tr>
			
			<th><br />Qualitative characters parameters</th>
			<tr>
				<th>Characters</th>
				<th>Weights</th>
			</tr>
			<?php
				$query = mysql_query('SELECT code_char, name_char, weight, explanations, nb_states 
									  FROM characters 
									  WHERE name_genus = "'.$genus_name.'" AND correction IS NULL');
				
				while($row = mysql_fetch_assoc($query)){
					if($row["nb_states"] == 1 || substr($row["code_char"], -1) == 1) {
						if($use_user_params) {
							$weight = $user_params[(string)$row["code_char"]]["weight"];
						} else {
							$weight = $row["weight"];
						}
						echo '<tr>';
							echo '<td>'.$row["name_char"].'</td>';
							/* Ici pas d'explanations car celles contenues dans la base 
							 * correspondent a chaque �tat et pas a la caract�ristique dans son ensemble */
							echo '	<td><input class="ql_weight" type="text" name="'.$row["code_char"].'_w" value="'.$weight.'">';
						echo '</tr>';
					}
				}
			?>
			<tr>
				<th></th>
				<td>
					<input type="button" value="Set all to 1" onclick="allto1('ql_weight')">
					<br/><input type="button" value="Defaults values" onclick="default_values('ql_weight')">
				</td>
			</tr>
		</table>
		<input type="hidden" name="file_type" value="parameters">
		<input type="submit" value="Save parameters">
	</form>
	</ul>
</div>

<?php mysql_close(); ?>

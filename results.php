<?php
include('includes/haut.php');
?>

<script type="text/javascript">
	$(document).ready(function() {		
		$("#waiting").replaceWith($("#results"));
		$("#results").css('display','block');
	});
</script>

<?php
if(isset($_SESSION['results'])) {
	$results = $_SESSION['results'];
}
?>

<div id="waiting">
	<img src="<?php echo ROOTPATH.'/includes/img/ajax-loader.gif'; ?>">
	<br />
	Loading ...
</div>

<?php
include('includes/bas.php'); 
?>

<div id="results"><table>
	<tr><th><h2>Results</h2></th></tr>			
		<tr>
			<th>Species names</th>
			<?php if($_SESSION['res_type'] == "all" || $_SESSION['res_type'] == "mixed") echo '<th>Description</th>'; ?>
			<th>Coefficients of similarity</th>
			<th>Number of characters used</th>
			<th>Number of agreements</th>
		</tr>
	<?php
	$count = 0;
	foreach($results as $spe => $res) { if ($res['nb_char_used'] != 0) {
	connexion_bdd();
		$species = '';
		$genus_name = '';
		$desc = '';

		// Recuperation du numero de la description si necessaire
		if($_SESSION['res_type'] == "all" || $_SESSION['res_type'] == "mixed") {
			$desc = substr($spe,0,1);
			
			switch($desc) {
				case '0': $descText = 'Original'; break;
				case 'C': $descText = 'Composite'; break;
				default : $descText = $desc; break;
			}
		}
		// Recuperation du code de l'espece
		$spe = substr($spe,-2);	
		
		$q = mysql_query("SELECT specie, name_genus  
						  FROM species 
						  WHERE code_spe = '".$spe."'");

		while($row = mysql_fetch_assoc($q)){
			$species = substr($row['name_genus'],0,1).". ".$row['specie'];
			$genus_name = $row['name_genus'];
		}
			echo '<tr>';
				echo '<td><button onClick = "showHideDetails('.$count++.')">+</button> '.$species.'</td>';
				if($_SESSION['res_type'] == "all" || $_SESSION['res_type'] == "mixed") echo '<td class="center">'.$descText.'</td>';
				echo '<td class="center">'.$res['coef'].'</td>';
				echo '<td class="center">'.$res['nb_char_used'].'</td>';
				echo '<td class="center">'.$res['nb_char_agree'].'</td>';
			echo '</tr>';
			echo '<tr class="details">';
				echo '<td colspan = "5"><table>';
					
		if(isset($res['details']['qt'])) {
			echo '<tr>
				<th colspan="2">Quantitative characters</th>
				<th>Sample</th>
				<th>Species</th>
				<th>Score</th>
				<th>Weight</th>
				<th>SW</th>
			</tr>';
			foreach($res['details']['qt'] as $key => $details) {
				$char = '';
				$q2 = mysql_query("SELECT name_char, code_char 
								   FROM characters 
								   WHERE code_char = '".$key."' AND characters.name_genus = '".$genus_name."' ");

				while($row2 = mysql_fetch_assoc($q2)){
					$code = $row2['code_char'];
					$character = $row2['name_char'];
					//$char = $code."    -    ".$character;
				}
					echo '<tr>
							<td class="center">'.$code.'</td>
							<td>'.$character.'</td>
							<td class="center">'.$details['sample'].'</td>
							<td class="center">'.$details['species'].'</td>
							<td class="center">'.$details['score'].'</td>
							<td class="center">'.$details['weight'].'</td>
							<td class="center">'.$details['SW'].'</td>
						</tr>';
			}
						
		}
		if(isset($res['details']['ql'])) {
			echo '<tr>
					<th colspan="2">Qualitative characters</th>
					<th>Sample</th>
					<th>Species</th>
					<th>State score</th>
					<th>Character score</th>
					<th>Weight</th>
					<th>SW</th>
				</tr>';
			foreach($res['details']['ql'] as $key => $details) {
				$char = '';
				$q2 = mysql_query("SELECT name_char, nb_states, code_char 
								   FROM characters 
								   WHERE code_char = '".$key."' AND characters.name_genus = '".$genus_name."' ");

				while($row2 = mysql_fetch_assoc($q2)){
					$code = $row2['code_char'];
					$character = $row2['name_char'];
					//$char = $code." - ".$character;
					$nb_states = $row2['nb_states'];
				}
				echo '<tr>
						<td class="center">'.$code.'</td>
						<td>'.$character.'</td>
						<td class="center">'.$details['sample'].'</td>
						<td class="center">'.$details['species'].'</td>';
						if($nb_states == 1) echo '<td class="center">-</td>
												  <td class="center">'.$details['state_score'].'</td>
												  <td class="center">'.$details['weight'].'</td>
												  <td class="center">'.$details['SW'].'</td>';
						else echo ' <td class="center">'.$details['state_score'].'</td>
									<td colspan = "3"></td>
					</tr>';
				if (isset($details['char_score'])) {
					echo '<tr>
							<td></td>
							<td>'.substr($character,0,-2).'</td>
							<td colspan = "3"></td>
							<td class="center">'.$details['char_score'].'</td>
							<td class="center">'.$details['weight'].'</td>
							<td class="center">'.$details['SW'].'</td>
						</tr>';
				}
			}
		}
		
		echo '<tr>
				<th>References</th>
			</tr>
			<tr>
				<th>Author(s)</th>
				<th>Publied in</th>
				<th>Publication title</th>
				<th>Journal</th>
			</tr>';
		$q3 = mysql_query("SELECT DISTINCT author, publi_in, title, journal 
						   FROM define, `references`  
						   WHERE define.id_ref = `references`.id_ref AND define.code_spe = '".$spe."' AND define.description = '".$desc."'");

		while($row3 = mysql_fetch_assoc($q3)){
			$authors = $row3['author'];
			$publi_in = $row3['publi_in'];
			$title = $row3['title'];
			$journal = $row3['journal'];
		}
		echo '<tr>
				<td>'.$authors.'</td>
				<td class="center">'.$publi_in.'</td>
				<td>'.$title.'</td>
				<td>'.$journal.'</td>
			</tr>';
		
		echo '</table></td>';
		echo '</tr>';
		mysql_close();
	}}
	?>
</table></div>

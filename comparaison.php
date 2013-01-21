<?php
include('includes/haut.php');

	$genus_name = define_genus();
	$files_list = get_user_samples();
?>

	<div id="comparaison">
		<h3>Sample selection</h3>
		<form action="algorithms.php" method="post">
		<?php if(!empty($files_list)) {?>
				<h4>Your samples</h4>
				<select name="user_sample" multiple size="5">
					<?php 
					foreach($files_list as $f) {
						echo '<option value="'.$f.'">'.substr($f,0,1).' - '.str_replace('.xml','',substr(str_replace("-user".$_SESSION['user_id']."_","",$f),1)) .'</option>';
					}
					?>
				</select>
		<?php } else { echo '<p>You must first select one of your saved samples or enter a new one.  <a href="new_sample.php">Click here to be redirected...</a> </p>';} ?>
			<div style="display:none;">
				<h4>Database entry</h4> - non fonctionnel
				<select name="database_sample" multiple size="10" style="display:none">
					<?php /* connexion_bdd();
					$q = mysql_query("SELECT DISTINCT specie, description  
									  FROM species, define 
									  WHERE name_genus = '".$genus_name."' AND define.code_spe = species.code_spe");

					while($row = mysql_fetch_assoc($q)){
						echo '<option value="'.$f.'">'.$row['description'].' - '.$row['specie'].'</option>';
					}
					
					mysql_close(); */ ?>
				</select>
			</div>
			<br /><br />
			<h3>Comparison parameters</h3>
			<h4>Validity of species</h4>
			<input type="checkbox" name="validity" value="use_unvalid" checked="true">Include invalid species  <br/> <br/>
			<h4>Type of description</h4>
			<input type="radio" name="choix" value="mixed" checked="true">Original and composites descriptions <br/>
			<input type="radio" name="choix" value="composite">Composites descriptions <br/>
			<input type="radio" name="choix" value="originale">Only original descriptions <br/>
			<input type="radio" name="choix" value="all">All original and later descriptions treated individually (no composite descriptions) <br/><br/>
			<input type="submit" value="Compute coefficients of similarity" id="compute">
		</form>
	</div>
	<div id="loading">
		<img src="<?php echo ROOTPATH.'/includes/img/ajax-loader.gif'; ?>"><br />
		<!-- image generee sur http://ajaxload.info/ -->
		Loading...
	</div>

	<script language="javascript">
		$('#compute').click(function() {
			$("#comparaison").css('display','none');
			$("#loading").css('display','block');
		})
	</script>

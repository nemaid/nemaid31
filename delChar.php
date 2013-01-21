<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>Characters : Delete tool</h1></center>
	<br />
	<p>
		Just check the characters you want to delete and validate.
		<br />Be careful, the deletion of a character will delete all the related data for each description.
		<br />A deletion is permanent.
	</p>
<?php
	
// On récupère les données à supprimer
$Tdata = recupChar();
/*echo "<pre>";
print_r($Tdata);
echo "</pre>";*/

// On regarde si on vient d'envoyer des données à supprimer
if (isset($_POST['Send'])) 
{
	// On transfert les données à supprimer
	for ($i = 0 ; ($i < count($Tdata)) ; $i++)
	{
		if (isset($_POST['cb_'.$i]))
			$cochee = true;
		else
			$cochee = false;
		
		if ($cochee == true)
			delChar($Tdata[$i]['id']);
	}
		
	//// On se redirige sur une nouvelle page
	header('Location: administration.php?action=del');
	exit;
}
?>
<br />
<br />
<?php
	////////// Affichage du tableau avec des cases à cocher pour supprimer
	if ($Tdata != -1)
	{
	?>
		<form method="post" action="delChar.php">
			<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table>
				<tr>
				   <th></th>
				   <th>Code</th>
				   <th>Complete name</th>
				   <th>Explanations</th>
				   <th>Weight</th>
				   <th>Correction</th>
				   <th>Min</th>
				   <th>Max</th>
				   <th>Number of States</th>
				   <th>Related genus</th>
				</tr>
				<?php
			for ($i = 0 ; $i < count($Tdata) ; $i++)
			{
		?>
				<tr>
					<td>
						<?php echo '<input type="checkbox" id="cb_'.$i.'" name="cb_'.$i.'" /> '; echo $Tdata[$i]['id']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['code'])) echo $Tdata[$i]['code']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['name'])) echo $Tdata[$i]['name']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['exp'])) echo $Tdata[$i]['exp']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['weight'])) echo $Tdata[$i]['weight']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['correction'])) echo $Tdata[$i]['correction']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['min'])) echo $Tdata[$i]['min']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['max'])) echo $Tdata[$i]['max']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['states'])) echo $Tdata[$i]['states']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['genus'])) echo $Tdata[$i]['genus']; ?>
					</td>
				</tr>
		<?php
			}
		?>
			</table>
		<br />
		<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
		<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>

<?php
	}
?>
</div>

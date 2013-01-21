<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>Genera : Delete tool</h1></center>
	<br />
	<p>
		Just check the genera you want to delete and validate.
		<br />Be careful, the deletion of a genera will delete all the related species and the related data !
		<br />A deletion is permanent.
	</p>
<?php
	
// On récupère les données à supprimer
$Tdata = recupGen();
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
			delGen($Tdata[$i]);
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
		<form method="post" action="delGen.php">
			<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table>
				<tr>
				   <th></th>
				   <th>Code</th>
				   <th>Genus<th>
				</tr>
				<?php
			for ($i = 0 ; $i < count($Tdata) ; $i++)
			{
		?>
				<tr>
					<td>
						<?php echo '<input type="checkbox" id="cb_'.$i.'" name="cb_'.$i.'" /> '; $cpt = $i + 1; echo $cpt; ?>
					</td>
					<td>
						<?php echo if (isset($Tdata[$i])) $Tdata[$i]; ?>
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

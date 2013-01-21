<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>Species : Delete tool</h1></center>
	<br />
	<p>
		Just check the references you want to delete and validate.
		<br />Be careful, the deletion of a specie will delete all the related descriptions.
		<br />A deletion is permanent.
	</p>
<?php

// Récupération de la lettre choisie dans l'alphabet de sélection
if (!isset($_GET['lettre']) && !isset($_POST['lettre']))
	$lettre = 'A';
else
{
	if (isset($_GET['lettre']))
		$lettre = $_GET['lettre'];
	else
		$lettre = $_POST['lettre'];
}

// On récupère les données à supprimer
$Tdata = recupSpeLettre($lettre);
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
			delSpe($Tdata[$i]['code']);
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
		<form method="post" action="delSpe.php">
			<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table>
				<tr>
				   <th></th>
				   <th>Genus</th>
				   <th>Code</th>
				   <th>Specie</th>
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
						<?php echo if (isset($Tdata[$i]['genus'])) $Tdata[$i]['genus']; ?>
					</td>
					<td>
						<?php echo if (isset($Tdata[$i]['code'])) $Tdata[$i]['code']; ?>
					</td>
					<td>
						<?php echo if (isset($Tdata[$i]['specie'])) $Tdata[$i]['specie']; ?>
					</td>
				</tr>
		<?php
			}
		?>
			</table>
		<br />
		<input type="hidden" name="lettre" value="<?php echo $lettre; ?>" />
		<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
		<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>

<?php
	}
	else
	{
		echo "<br /><br />Sorry, there is no specie which begins by the letter '".$lettre."'.<br /><br />";
		echo '<input type="button" name="Return" value="Return" onClick="document.location=\'administration.php?action=adm\'" />';
	}
?>
</div>

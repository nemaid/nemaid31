<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>References : Delete tool</h1></center>
	<br />
	<p>
		Just check the references you want to delete and validate.
		<br />Be careful, the deletion of a character will delete all the related descriptions.
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
$Tdata = recupRefLettre($lettre);
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
			delRef($Tdata[$i]['id']);
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
		<form method="post" action="delRef.php">
			<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table>
				<tr>
				   <th></th>
				   <th>Author(s)</th>
				   <th>Year of publication</th>
				   <th>Year of work</th>
				   <th>Title</th>
				   <th>Journal</th>
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
						<?php if (isset($Tdata[$i]['author'])) echo $Tdata[$i]['author']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['year'])) echo $Tdata[$i]['year']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['wYear'])) echo $Tdata[$i]['wYear']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['title'])) echo $Tdata[$i]['title']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['journal'])) echo $Tdata[$i]['journal']; ?>
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
		echo "<br /><br />Sorry, there is no references which begins by the letter '".$lettre."'.<br /><br />";
		echo '<input type="button" name="Return" value="Return" onClick="document.location=\'administration.php?action=adm\'" />';
	}
?>
</div>

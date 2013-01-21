<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>Users : Delete tool</h1></center>
	<br />
	<p>
		Just check the users you want to delete and validate.
		<br />Be careful, a deletion is permanent.
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
$Tdata = recupUserLettre($lettre);
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
			delUser($Tdata[$i]['id']);
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
		<form method="post" action="delUser.php">
			<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table>
				<tr>
				   <th></th>
				   <th>First name</th>
				   <th>Last name</th>
				   <th>Email</th>
				   <th>Institution</th>
				   <th>City</th>
				   <th>Country</th>
				   <th>Administrator</th>
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
						<?php if (isset($Tdata[$i]['f_name'])) echo $Tdata[$i]['f_name']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['l_name'])) echo $Tdata[$i]['l_name']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['email'])) echo $Tdata[$i]['email']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['institution'])) echo $Tdata[$i]['institution']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['city'])) echo $Tdata[$i]['city']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['country'])) echo $Tdata[$i]['country']; ?>
					</td>
					<td>
						<?php if (isset($Tdata[$i]['admin'])) echo $Tdata[$i]['admin']; ?>
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
		echo "<br /><br />Sorry, there is no user which begins by the letter '".$lettre."'.<br /><br />";
		echo '<input type="button" name="Return" value="Return" onClick="document.location=\'administration.php?action=adm\'" />';
	}
?>
</div>

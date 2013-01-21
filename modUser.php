<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>Users : Modification tool</h1></center>
	<br />
	<p>
		Be careful, a modification is permanent !
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
if ((!isset($_POST['Send'])) && $Tdata != -1)
{
	for ($i = 0 ; $i < count($Tdata) ; $i++)
	{
		if (isset($Tdata[$i]['id']))
			$_POST['id_'.$i] = $Tdata[$i]['id'];
		if (isset($Tdata[$i]['f_name']))
			$_POST['fna_'.$i] = $Tdata[$i]['f_name'];
		if (isset($Tdata[$i]['l_name']))
			$_POST['lna_'.$i] = $Tdata[$i]['l_name'];
		if (isset($Tdata[$i]['email']))
			$_POST['mai_'.$i] = $Tdata[$i]['email'];
		if (isset($Tdata[$i]['password']))
			$_POST['pwd_'.$i] = $Tdata[$i]['password'];
		if (isset($Tdata[$i]['institution']))
			$_POST['ins_'.$i] = $Tdata[$i]['institution'];
		if (isset($Tdata[$i]['city']))
			$_POST['cit_'.$i] = $Tdata[$i]['city'];
		if (isset($Tdata[$i]['country']))
			$_POST['cou_'.$i] = $Tdata[$i]['country'];
		if (isset($Tdata[$i]['admin']))
			$_POST['rig_'.$i] = $Tdata[$i]['admin'];
	}
}

$transf = true;

// On regarde si on vient d'envoyer des données à supprimer
if (isset($_POST['Send'])) 
{
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < count($Tdata) ; $i++)
		{
			if (!empty($_POST['mai_'.$i]) && !empty($_POST['cou_'.$i]))
			{
				// On part du principe que l'élément n'a jamais été transféré si la variable n'existe pas encore ou qu'elle est vide
				if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
					$_POST['trf_'.$i] = false;
				
				// Si l'élément n'a pas déja été transféré, on le transfert, sinon on passe à l'élément suivant
				if ($_POST['trf_'.$i] == false)
				{					
					$exist = modUser($_POST['id_'.$i], $_POST['fna_'.$i], $_POST['lna_'.$i], $_POST['mai_'.$i], $_POST['pwd_'.$i], $_POST['ins_'.$i], $_POST['cit_'.$i], $_POST['cou_'.$i], $_POST['rig_'.$i]);
					// Si $exist = true, c'est que l'élément existait déjà dans la BDD
					//echo $exist;
					if ($exist == true)
					{
						$transf = false;
						$errorBDD = true;
						break;
					}
					else // Si l'élément n'existait pas encore, il a bien été ajouté dans on met trf = true;
						$_POST['trf_'.$i] = true;
				}
			}
			elseif (empty($_POST['mai_'.$i]) && empty($_POST['cou_'.$i]) && empty($_POST['ins_'.$i]) && empty($_POST['cit_'.$i]))
			{
				// hé bé on fait toujours rien
			}
			else
			{
				$transf = false;
				$noData = true;
				break;
			}
		}
	}
	if ($transf == true)
	{
		//// Si le transfert a été fait, on se redirige sur une nouvelle page
		//echo "<br />Transfert accomplie !<br />";
		header('Location: administration.php?action=mod');
		exit;
	}
}
?>
<br />
<br />
<?php
	////////// Affichage du tableau avec des cases à cocher pour supprimer
	if ($Tdata != -1)
	{
	?>
		<form method="post" action="modUser.php">
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
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
						<?php
							echo $_POST['id_'.$i];
							echo '<input type="hidden" name="id_'.$i.'" value="'.$_POST['id_'.$i].'" />';
							echo '<input type="hidden" name="trf_'.$i.'" value="'; if (isset($_POST['trf_'.$i])) echo $_POST['trf_'.$i]; echo '" />';
						?>
					</td>
					<td>
						<?php
							echo $_POST['lna_'.$i];
							echo '<input type="hidden" name="lna_'.$i.'" value="'.$_POST['lna_'.$i].'" />';
						?>
					</td>
					<td>
						<?php
							echo $_POST['fna_'.$i];
							echo '<input type="hidden" name="fna_'.$i.'" value="'.$_POST['fna_'.$i].'" />';
						?>
					</td>
					<td>
						<?php
							echo '<input type="text" name="mai_'.$i.'" value="'; if (isset($_POST['mai_'.$i])) echo $_POST['mai_'.$i]; echo '" />';
							echo '<input type="hidden" name="pwd_'.$i.'" value="'; if (isset($_POST['pwd_'.$i])) echo $_POST['pwd_'.$i]; echo '" />';
						?>
					</td>
					<td>
						<?php echo '<input type="text" name="ins_'.$i.'" value="'; if (isset($_POST['ins_'.$i])) echo $_POST['ins_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="cit_'.$i.'" value="'; if (isset($_POST['cit_'.$i])) echo $_POST['cit_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="cou_'.$i.'" value="'; if (isset($_POST['cou_'.$i])) echo $_POST['cou_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php
							echo '<select name="rig_'.$i.'">';
							if (isset($_POST['rig_'.$i]))
							{
								if ($_POST['rig_'.$i] == 1)
								{
									echo '<option selected="selected" value="1">Administrator</option><br />';
									echo '<option value="0">User</option><br />';
								}
								else
								{
									echo '<option selected="selected" value="0">User</option><br />';
									echo '<option value="1">Administrator</option><br />';
								}
							}
							else
							{
								echo '<option selected="selected" value="0">User</option><br />';
								echo '<option value="1">Administrator</option><br />';
							}
							echo "</select>";
						?>
					</td>
				</tr>
		<?php
			}
		?>
			</table>
		<br />
		<input type="hidden" name="lettre" value="<?php echo $lettre; ?>" />
		<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
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

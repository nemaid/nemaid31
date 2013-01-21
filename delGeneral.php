<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>

<div id="admin">
	<center><h1>General Data : Delete tool</h1></center>
	<br />
	<p>
		Just check the descriptions you want to delete and validate. Be careful, a deletion is permanent !
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
	
// On récupère les différents "characters"
$Tchar = recupChar();

// On place les données dans des $_POST pour les afficher dans les champs
$Tdata = recupData($lettre);
/*echo "<pre>";
print_r($Tdata);
echo "</pre>";*/

for ($i = 0 ; ($i < count($Tdata)) ; $i++)
{
	$_POST['spe_'.$i] = $Tdata[$i]['specie'];
	$_POST['iSp_'.$i] = $Tdata[$i]['code_spe'];
	$j = 0;
	foreach ($Tdata[$i]['characters'] as $key1 => $val1)
	{
		if (isset($Tdata[$i]['characters'][$j]['id_ref']) && !empty($Tdata[$i]['characters'][$j]['id_ref']))
		{
			$_POST['des_'.$i.'_'.$j] = $Tdata[$i]['characters'][$j]['desc'];
			$_POST['ref_'.$i.'_'.$j] = $Tdata[$i]['characters'][$j]['id_ref'];
			$_POST['aut_'.$i.'_'.$j] = $Tdata[$i]['characters'][$j]['author'];
			$_POST['yea_'.$i.'_'.$j] = $Tdata[$i]['characters'][$j]['year'];
			$_POST['vad_'.$i.'_'.$j] = $Tdata[$i]['characters'][$j]['validity'];
			$_POST['not_'.$i.'_'.$j] = $Tdata[$i]['characters'][$j]['notes'];
			$k = 0;
			foreach ($Tchar as $char)
			{
				if (isset($Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val']) && !empty($Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val']))
				{
					$_POST['def_'.$i.'_'.$j.'_'.$k] = $Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['def'];
					$_POST['dat_'.$i.'_'.$j.'_'.$k] = $Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['data'];
					$_POST['cod_'.$i.'_'.$j.'_'.$k] = $Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['code'];
					$_POST['iCh_'.$i.'_'.$j.'_'.$k] = $Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['id_char'];
					$_POST['val_'.$i.'_'.$j.'_'.$k] = $Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val'];
				}
				else
				{
					$_POST['def_'.$i.'_'.$j.'_'.$k] = "";
					$_POST['dat_'.$i.'_'.$j.'_'.$k] = "";
					$_POST['cod_'.$i.'_'.$j.'_'.$k] = "";
					$_POST['iCh_'.$i.'_'.$j.'_'.$k] = "";
					$_POST['val_'.$i.'_'.$j.'_'.$k] = "";
				}
				$k++;
			}
		}
		$j++;
	}
}

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
		{
			delDataSpe($_POST['iSp_'.$i]);
			$_POST['iSp_'.$i] = -1;
		}
		else
		{
			/*$taille = count($Tdata[$i]['characters']);
			if (isset($_POST['newJ_'.$i]))
				$taille = $_POST['newJ_'.$i];
			for ($j = 0 ; $j < $taille ; $i++)*/
			$j = 0;
			foreach ($Tdata[$i]['characters'] as $key1 => $val1)
			{
				if (isset($_POST['cb_'.$i.'_'.$j]))
				{
					$cochee = true;
					//echo "<br />C'est coché !<br />";
				}
				else
				{
					$cochee = false;
					//echo "<br />C'est pas coché !<br />";
				}
					
				if ($cochee == true)
				{
					//echo "<br />C'est toujours coché !<br />";
					delDataDesc($_POST['iSp_'.$i], $_POST['ref_'.$i.'_'.$j]);
					$_POST['newJ_'.$i] = newDesc($_POST['iSp_'.$i]);
				}
				/*else
					echo "Rien ne se passe pour la description de l'auteur ".$_POST['ref_'.$i.'_'.$j]." de l'espèce ".$_POST['iSp_'.$i]."<br />";*/
				$j++;
			}
		}
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
		<form method="post" action="delGeneral.php">
			<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table>
				<tr>
				   <th>Id</th>
				   <th>Species</th>
				</tr>
		<?php
			for ($i = 0 ; ($i < count($Tdata)) ; $i++)
			{
				if (isset($_POST['iSp_'.$i]) && ($_POST['iSp_'.$i] != -1))
				{
		?>
				<tr>
					<td>
						<?php echo $i+1; ?>
					</td>
					<td>
						<?php
							$nbDesc = count($Tdata[$i]['characters']);
							echo '<input type="checkbox" id="cb_'.$i.'" name="cb_'.$i.'" onClick="cocherDescription('.$i.', '.$nbDesc.')" />';
							echo $_POST['spe_'.$i];
							echo '<input type="hidden" name="iSp_'.$i.'" value="'.$_POST['iSp_'.$i].'" />';
						?>
					</td>
					<td>
						<table id="<?php echo "ssTab_".$i; ?>">
							<tr>
								<th>Descriptions</th>
								<th>Authors</th>
								<th>Validity</th>
								<th>Comment</th>
		<?php
								foreach ($Tchar as $char)
									echo "<th>".$char['code']."</th>";
		?>
							</tr>
		<?php
				$taille = count($Tdata[$i]['characters']);
				if (isset($_POST['newJ_'.$i]))
					$taille = $_POST['newJ_'.$i];
				for ($j = 0 ; $j < $taille ; $j++)
				{
					if (isset($_POST['ref_'.$i.'_'.$j]) && ($_POST['ref_'.$i.'_'.$j] != -1))
					{
		?>
					<tr>
						<td>
							<?php
								echo '<input type="checkbox" id="cb_'.$i.'_'.$j.'" name="cb_'.$i.'_'.$j.'" onClick="cocherEspece('.$i.', '.$nbDesc.')" />';
								echo '<input type="hidden" name="ref_'.$i.'_'.$j.'" value="'.$_POST['ref_'.$i.'_'.$j].'" />';
								if (isset($_POST['des_'.$i.'_'.$j]))
									echo $_POST['des_'.$i.'_'.$j];
								else
									echo '-';
							?>
						</td>
						<td>
							<?php
								$title = explode(' ', $Tdata[$i]['characters'][$j]['title']);
								echo $Tdata[$i]['characters'][$j]['year']." - ".$Tdata[$i]['characters'][$j]['author']." - ".$title[0]." ".$title[1]." ".$title[2]."...";
							?>
						</td>
						<td>
							<?php
								if (isset($_POST['vad_'.$i.'_'.$j]))
									echo $_POST['vad_'.$i.'_'.$j];
								else
									echo '-';
							?>
						</td>
						<td>
							<?php
								if (isset($_POST['not_'.$i.'_'.$j]))
									echo $_POST['not_'.$i.'_'.$j];
								else
									echo '-';
							?>
						</td>
			<?php
						$k = 0;
						foreach ($Tchar as $char)
						{
							if (isset($Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val']) && (!empty($Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val'])))
							{
			?>
								<td>
									<?php echo $_POST['val_'.$i.'_'.$j.'_'.$k]; ?>
								</td>
			<?php
							}
							else
							{
			?>
								<td>
									<?php echo '-'; ?>
								</td>
			<?php
							}
							$k++;
						}
			?>
					</tr>
			<?php
					}
				}
			?>
				</table>
				</td>
				</tr>
			<?php
				}
			}
		?>
		<input type="hidden" name="lettre" value="<?php echo $lettre; ?>" />
		<input type="hidden" name="nbSpe" value="<?php echo $i; ?>" />
		</table>
		<br />
		<input type="submit" name="Send" value="Delete" onclick="return(confirm('Are you sure you want to delete the selected data ?'));" />
		<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>

<?php
	}
	else
	{
		echo "<br /><br />Sorry, there is no specie which begins by the letter '".$lettre."'.<br /><br />";
		echo '<input type="button" name="Return" value="Return" onClick="document.location=\'administration.php?action=adm\'" />'
	}	
?>
</div>

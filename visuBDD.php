<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

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

?>
<br />
<br />

<!------ Affichage du tableau ------>
<div id="admin"
	<center><h1>General view of the database</h1></center>
	<br />
	<p>
		Use the alphabet to select species beginning by the letter you want to choose. Do not hesistate to contact Mr Fortuner at fortuner@wanadoo.fr to obtain more details about the database.
	</p>
<?php
	//// Alphabet de sélection
	echo "<center><h3>";
	foreach (range('A', 'Z') as $lettre)
	{
		echo "<a href='visuBDD.php?lettre=".$lettre."'>".$lettre."</a>";
		if ($lettre != 'Z')
			echo " - ";
		else
			echo "</center></h3>";
	}

	if ($Tdata != -1)
	{
	?>
	<br />
	<input type="button" name="Return" value="Return" onClick="document.location='main.php'" />
	<br /><br />
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
					</tr>
<?php
		$taille = count($Tdata[$i]['characters']);
		for ($j = 0 ; $j < $taille ; $j++)
		{
			if (isset($_POST['ref_'.$i.'_'.$j]) && ($_POST['ref_'.$i.'_'.$j] != -1))
			{
?>
			<tr>
				<td>
					<?php
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
</table>
<br />
<input type="button" name="Return" value="Return" onClick="document.location='main.php'" />

<?php
	}
	else
	{
		echo "<br /><br />Sorry, there is no specie which begins by the letter '".$lettre."'.";
	}
?>
</div>

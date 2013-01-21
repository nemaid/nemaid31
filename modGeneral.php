<?php
// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");
?>
<div id="admin">
<center><h1>General Data : Modification tool</h1></center>
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

// On récupère les différents "characters"
$Tchar = recupChar();

// Et enfin on récupère les data
$Tdata = recupData($lettre);
/*echo "<pre>";
print_r($Tdata);
echo "</pre>";*/

// On place les données dans des $_POST pour les afficher dans les champs
if (!isset($_POST['Send']))
{
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
						$_POST['trf_'.$i.'_'.$j.'_'.$k] = false;
					}
					else
					{
						$_POST['def_'.$i.'_'.$j.'_'.$k] = "";
						$_POST['dat_'.$i.'_'.$j.'_'.$k] = "";
						$_POST['cod_'.$i.'_'.$j.'_'.$k] = $Tchar[$k]['code'];
						$_POST['iCh_'.$i.'_'.$j.'_'.$k] = $Tchar[$k]['id'];
						$_POST['val_'.$i.'_'.$j.'_'.$k] = "";
					}
					$k++;
				}
			}
			$j++;
		}
	}
}

// On suppose que tout est conforme dans l'envoie des données
$transf = true;
$speOrRef = false;
$doubRef = false;

// On regarde si on vient d'envoyer des données à transférer
if (isset($_POST['Send'])) 
{
	//// On transfert les modifications
	for ($i = 0 ; ($i < $_POST['nbSpe']) ; $i++)
	{	
		//// Test d'affichage des variables à transmettre
		/*echo "<br /> Après le formulaire : <br />";
		echo "- Code de l'espèce : ".$_POST['iSp_'.$i]."<br />";*/
		
		$nbDesc = $_POST['nbDesc_'.$i];
		
		//// On vérifie que le user n'entre pas plusieurs fois la même référence
		$Tref = array();
		for ($j = 0 ; $j < $nbDesc ; $j++)
			$Tref[$j] = $_POST['ref_'.$i.'_'.$j];
		$doubRef = doublons($Tref);
		if ($doubRef == true)
		{
			$transf = false;
			break;
		}
		
		for ($j = 0 ; ($j < $nbDesc) ; $j++)
		{
			//// On vérifie qu'une référence a bien été sélectionnée
			if (!isset($_POST['ref_'.$i.'_'.$j]) || ($_POST['ref_'.$i.'_'.$j] == "all"))
			{
				if (isset($_POST['Ignore']))
					continue;
				else
				{
					$transf = false;
					$speOrRef = true;
					break 2;
				}
			}
			else
			{
				if (!isset($_POST['des_'.$i.'_'.$j]))
					$_POST['des_'.$i.'_'.$j] = "";
				if (!isset($_POST['vad_'.$i.'_'.$j]))
					$_POST['vad_'.$i.'_'.$j] = "";
				if (!isset($_POST['not_'.$i.'_'.$j]))
					$_POST['not_'.$i.'_'.$j] = "";

				//// Test d'affichage des variables à transmettre
				/*echo "- Description n° : ".$_POST['des_'.$i.'_'.$j]."<br />";
				echo "- Code de la référence : ".$_POST['ref_'.$i.'_'.$j]."<br />";
				echo "- Validité : ".$_POST['vad_'.$i.'_'.$j]."<br />";
				echo "- Commentaires : ".$_POST['not_'.$i.'_'.$j]."<br />";*/
				
				$k = 0;
				foreach ($Tchar as $char)
				{
					// On part du principe que l'élément n'a jamais été transféré si la variable n'existe pas encore ou qu'elle est vide
					if (!isset($_POST['trf_'.$i.'_'.$j.'_'.$k]) || empty($_POST['trf_'.$i.'_'.$j.'_'.$k]))
						$_POST['trf_'.$i.'_'.$j.'_'.$k] = false;

					// Si l'élément n'a pas déja été transféré, on le transfert, sinon on passe à l'élément suivant
					if ($_POST['trf_'.$i.'_'.$j.'_'.$k] != true)
					{
						if (!isset($_POST['dat_'.$i.'_'.$j.'_'.$k]))
							$_POST['dat_'.$i.'_'.$j.'_'.$k] = "";
						if (!isset($_POST['val_'.$i.'_'.$j.'_'.$k]))
							$_POST['val_'.$i.'_'.$j.'_'.$k] = "";
						
						/*if (isset ($_POST['def_'.$i.'_'.$j.'_'.$k]) && !empty($_POST['def_'.$i.'_'.$j.'_'.$k]))
						{*/
							//// Test d'affichage des variables à transmettre
							/*echo "- id define : ".$_POST['def_'.$i.'_'.$j.'_'.$k]."<br />";
							echo "- id data : ".$_POST['dat_'.$i.'_'.$j.'_'.$k]."<br />";
							echo "- Character : ".$_POST['iCh_'.$i.'_'.$j.'_'.$k]." - Valeur : ".$_POST['val_'.$i.'_'.$j.'_'.$k]."<br />";*/
							
							$_POST['trf_'.$i.'_'.$j.'_'.$k] = modData($_POST['iSp_'.$i], $_POST['des_'.$i.'_'.$j], $_POST['ref_'.$i.'_'.$j],
												$_POST['vad_'.$i.'_'.$j], $_POST['not_'.$i.'_'.$j], $_POST['def_'.$i.'_'.$j.'_'.$k],
												$_POST['dat_'.$i.'_'.$j.'_'.$k], $_POST['val_'.$i.'_'.$j.'_'.$k], $_POST['iCh_'.$i.'_'.$j.'_'.$k]);
						//}
					}
					$k++;
				}
			}
		}
			
		//// On se redirige sur une nouvelle page
		//echo "<br />Transfert réussi !<br />";
		header('Location: administration.php?action=mod');
		exit;
	}
}
?>
<br />
<br />
<?php
	////////// Affichage du tableau sous la forme d'un formulaire rempli :
	if ($Tdata != -1)
	{
	?>
	<form method="post" action="modGeneral.php">
	<?php
		// Si il y a eu un problème dans le transfert des données, on le signale
		if ($transf == false)
		{
			if ($speOrRef == true)
				echo "Sorry, but species and/or references are missing !<br /><br />";
			if ($doubRef == true)
				echo "Sorry, but you are entering twice the same reference for the same specie !<br />";
		}
	?>
		<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modification(s) ?'));" />
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
			if (isset($Tdata[$i]['code_spe']))
			{
	?>
			<tr>
				<td>
					<?php echo $i+1; ?>
				</td>
				<td>
					<?php
						// On affiche pas l'espèce dans un champs pour être sur que les modifications sont un minimum maîtrisée
						echo $_POST['spe_'.$i];
						echo '<input type="hidden" name="spe_'.$i.'" value="'.$_POST['spe_'.$i].'" />';
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
			$j = 0;
			foreach ($Tdata[$i]['characters'] as $key1 => $val1)
			{
	?>
				<tr>
					<td class="no_border">
						<?php echo '<input type="text" name="des_'.$i."_".$j.'" size="3" value="'; if (isset($_POST['des_'.$i.'_'.$j])) echo $_POST['des_'.$i.'_'.$j]; echo '" />'; ?>
					</td>
					<td class="no_border">
						<?php
							$ligneRef = recupRef();
							echo "<select name='ref_".$i."_".$j."'>";
							echo '<option selected="selected" value="all">--------</option><br />';
							foreach ($ligneRef as $ligne)
							{
								// On fragmente le titre dans un tableau pour n'afficher que les 3 premiers mots
								$title = explode(' ', $ligne['title']);
								if (isset($_POST['ref_'.$i.'_'.$j]) && !empty($_POST['ref_'.$i.'_'.$j]))
								{
									if ($ligne['id'] == $_POST['ref_'.$i.'_'.$j])
									{
										echo '<option selected="selected" value="'.$ligne['id'].'">';
											echo $ligne['year']." - ".$ligne['author']." - ".$title[0]." ".$title[1]." ".$title[2]."...";
										echo '</option>'."<br />";
									}
									else
									{
										echo '<option value="'.$ligne['id'].'">';
											echo $ligne['year']." - ".$ligne['author']." - ".$title[0]." ".$title[1]." ".$title[2]."...";
										echo '</option>'."<br />";
									}
								}
								else
								{
									echo '<option value="'.$ligne['id'].'">';
										echo $ligne['year']." - ".$ligne['author']." - ".$title[0]." ".$title[1]." ".$title[2]."...";
									echo '</option>'."<br />";
								}
							}
							echo "</select>";
						?>
					</td>
					<td class="no_border">
						<?php echo '<input type="text" name="vad_'.$i.'_'.$j.'" size="1" value="'; if (isset($_POST['vad_'.$i.'_'.$j])	) echo $_POST['vad_'.$i.'_'.$j]; echo '" />'; ?>
					</td>
					<td class="no_border">
						<?php echo '<input type="text" name="not_'.$i.'_'.$j.'" size="75" value="'; if (isset($_POST['not_'.$i.'_'.$j]) && !empty($_POST['not_'.$i.'_'.$j])) echo $_POST['not_'.$i.'_'.$j]; echo '" />'; ?>
					</td>
		<?php
					$k = 0;
					foreach ($Tchar as $char)
					{
						/*if (isset($Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val']) && (!empty($Tdata[$i]['characters'][$j]['values'][$Tchar[$k]['code']]['val'])))
						{*/
		?>
							<td class="no_border">
								<?php
									echo '<input type="text" name="val_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['val_'.$i.'_'.$j.'_'.$k])) echo $_POST['val_'.$i.'_'.$j.'_'.$k]; echo '" />';
									echo '<input type="hidden" name="def_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['def_'.$i.'_'.$j.'_'.$k]) && !empty($_POST['def_'.$i.'_'.$j.'_'.$k])) echo $_POST['def_'.$i.'_'.$j.'_'.$k]; echo '" />';
									echo '<input type="hidden" name="dat_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['dat_'.$i.'_'.$j.'_'.$k]) && !empty($_POST['dat_'.$i.'_'.$j.'_'.$k])) echo $_POST['dat_'.$i.'_'.$j.'_'.$k]; echo '" />';
									echo '<input type="hidden" name="cod_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['cod_'.$i.'_'.$j.'_'.$k]) && !empty($_POST['cod_'.$i.'_'.$j.'_'.$k])) echo $_POST['cod_'.$i.'_'.$j.'_'.$k]; echo '" />';
									echo '<input type="hidden" name="iCh_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['iCh_'.$i.'_'.$j.'_'.$k]) && !empty($_POST['iCh_'.$i.'_'.$j.'_'.$k])) echo $_POST['iCh_'.$i.'_'.$j.'_'.$k]; echo '" />';
									echo '<input type="hidden" name="trf_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['trf_'.$i.'_'.$j.'_'.$k]) && !empty($_POST['trf_'.$i.'_'.$j.'_'.$k])) echo $_POST['trf_'.$i.'_'.$j.'_'.$k]; echo '" />';
								?>
							</td>
		<?php
						/*}
						else
						{
		?>
							<td>
								<?php echo '<input type="text" name="val_'.$i.'_'.$j.'_'.$k.'" size="10" value="" />'; ?>
							</td>
		<?php
						}*/
						$k++;
					}
					$j++;
		?>
				</tr>
		<?php
			}
		?>
				<input type="hidden" name="<?php echo "nbDesc_".$i; ?>" value="<?php echo $j; ?>" />
			</table>
		</td>
		</tr>
	<?php
			}
		}
	?>
		<input type="hidden" name="nbSpe" value="<?php echo $i; ?>" />
		<input type="hidden" name="lettre" value="<?php echo $lettre; ?>" />
		</table><br />
		<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modification(s) ?'));" />
		<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>
	<?php
	}
	else
	{
		echo "<br /><br />Sorry, there is no specie which begins by the letter '".$lettre."'.";
	}	
	?>
</div>

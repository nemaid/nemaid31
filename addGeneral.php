<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

// Récupération du nombre de lignes à ajouter
if (!isset($_GET['nbAdd']) && !isset($_POST['nbAdd']))
	$nbAdd = 1;
else
{
	if (isset($_GET['nbAdd']))
		$nbAdd = $_GET['nbAdd'];
	else
		$nbAdd = $_POST['nbAdd'];
}

// On récupère les différents "characters"
$Tchar = recupChar();

// On suppose que tout est conforme dans l'envoie des données
$transf = true;
$speOrRef = false;
$doubSpe = false;
$doubRef = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des données à transférer
if (isset($_POST['Send']) || isset($_POST['Ignore'])) 
{
	//// On vérifie que le user n'entre pas plusieurs fois la même espèce
	$TspeDoub = array();
	for ($i = 0 ; $i < $nbAdd ; $i++)
		$TspeDoub[$i] = $_POST['spe_'.$i];
	//print_r($TspeDoub);
	unset($TspeDoub[array_search("all", $TspeDoub)]);
	//print_r($TspeDoub);
	$doubSpe = doublons($TspeDoub);
	if ($doubSpe == true)
		$transf = false;
	
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < $nbAdd ; $i++)
		{		
			if (!isset($_POST['spe_'.$i]) || ($_POST['spe_'.$i] == "all"))
			{
				if (isset($_POST['Ignore']))
					continue;
				else
				{
					$transf = false;
					$speOrRef = true;
					break;
				}
			}
			
			$nbDesc = $_POST['nbDescO_'.$i] + 1;
			
			//// Test d'affichage des variables à transmettre
			/*echo "<br /> Après le formulaire, j'envoie : <br />";
			echo "- Code de l'espèce : ".$_POST['spe_'.$i]."<br />";
			echo "- Nombre de descriptions : ".$nbDesc."<br />";*/
			
			//// On vérifie que le user n'entre pas plusieurs fois la même référence pour la même espèce
			$Tref = array();
			for ($j = 0 ; $j < $nbDesc ; $j++)
				$Tref[$j] = $_POST['ref_'.$i.'_'.$j];
			//print_r($Tref);
			unset($Tref[array_search("all", $Tref)]);
			//print_r($Tref);
			$doubRef = doublons($Tref);
			if ($doubRef == true)
			{
				$transf = false;
				break;
			}
			
			for ($j = 0 ; $j < $nbDesc ; $j++)
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
					$tour = 1;
					$entree = 1;
					foreach ($Tchar as $char)
					{
						// On part du principe que l'élément n'a jamais été transféré si la variable n'existe pas encore ou qu'elle est vide
						if (!isset($_POST['trf_'.$i.'_'.$j.'_'.$k]) || empty($_POST['trf_'.$i.'_'.$j.'_'.$k]))
							$_POST['trf_'.$i.'_'.$j.'_'.$k] = false;
						
						// Si l'élément n'a pas déja été transféré, on le transfert, sinon on passe à l'élément suivant
						if ($_POST['trf_'.$i.'_'.$j.'_'.$k] == false)
						{
							if (!isset($_POST['val_'.$i.'_'.$j.'_'.$k]))
								$_POST['val_'.$i.'_'.$j.'_'.$k] = "";
							
							//// Test d'affichage des variables à transmettre
							//echo "- Character : ".$_POST['cCh_'.$i.'_'.$j.'_'.$k]." - Valeur : ".$_POST['val_'.$i.'_'.$j.'_'.$k]."<br />";
							
							//// Si la variable est nulle et qu'on déjà rentré les autres info ($tour > 1), on ne transfert pas
							if (empty($_POST['val_'.$i.'_'.$j.'_'.$k]) && ($tour > 1))
							{
							 // et bé on fait rien
							}
							else
							{
								$exist = addData($_POST['spe_'.$i], $_POST['des_'.$i.'_'.$j], $_POST['ref_'.$i.'_'.$j], $_POST['vad_'.$i.'_'.$j], 
												$_POST['not_'.$i.'_'.$j], $_POST['cCh_'.$i.'_'.$j.'_'.$k], $_POST['val_'.$i.'_'.$j.'_'.$k], $entree);
								$entree++;
							}
							$tour++;
							
							// Si $exist = true, c'est que l'élément existait déjà dans la BDD
							echo $exist;
							if ($exist == true)
							{
								$transf = false;
								$errorBDD = true;
								break 3;
							}
							else // Si l'élément n'existait pas encore, il a bien été ajouté dans on met trf = true;
								$_POST['trf_'.$i.'_'.$j.'_'.$k] = true;
						}
						$k++;
					}
				}
			}
		}
	}
	if ($transf == true)
	{
		//// Si le transfert a été fait, on se redirige sur une nouvelle page
		//echo "<br />Transfert accomplie !<br />";
		header('Location: administration.php?action=add');
		exit;
	}
}
?>
<br />
<?php	
	////////// Affichage du tableau sous la forme d'un formulaire vide :
?>
<div id="admin">
	<center><h1>General Data : Addition tool</h1></center>
	<br />
	<p>
		Before adding new data, do not forget to first add the corresponding genera, species and references in their respective part.
	</p>
		<form name="mainF" method="post" action="addGeneral.php">
		<?php
			// Si il y a eu un problème dans le transfert des données, on le signale
			if ($transf == false)
			{
				if ($speOrRef == true)
				{
					echo "Sorry, but species and/or references are missing !<br />";
					echo "To ignore the related descriptions and to send the other data, click here : ";
					echo "<input type='submit' name='Ignore' value='Ignore' /><br /><br />";
				}
				if ($doubSpe == true)
				{
					echo "Sorry, but you enter twice the same specie !<br />";
					echo "Use the line \"Enter --- new description(s) |OK|\" to add other descriptions to a same specie.<br /><br />";
				}
				if ($doubRef == true)
					echo "Sorry, but you enter twice the same reference for the same specie !<br />";
				if ($errorBDD == true)
				{
					echo "Sorry, but one or several specie(s) you want to enter already exist(s) in the database !<br />";
					echo "To add a new description to a specie already existing, use the specific addition tool in the ";
					echo "<a href ='administration.php?action=adm'>management part</a>.<br /><br />";
				}
			}
		?>
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table id="mainTab">
				<tr>
				   <th>Id</th>
				   <th>Species</th>
				</tr>
		<?php
			for ($i = 0 ; $i < $nbAdd ; $i++)
			{
		?>
				<tr>
					<td>
						<?php $cId = $i + 1; echo $cId; ?>
					</td>
					<td>
		<?php
						//////// Sélection d'un genre et d'une espèce dans une liste déroulante
						$Tspe = recupSpe();
						echo '<select name="spe_'.$i.'">';
						echo '<option selected="selected" value="all">--------</option><br />';
						foreach ($Tspe as $ligne)
						{
							if (isset($_POST['spe_'.$i]))
							{
								if ($ligne['code_spe'] == $_POST['spe_'.$i])
								{
									echo '<option selected="selected" value="'.$ligne['code_spe'].'">'.$ligne['code_spe'].' - '.substr($ligne['name_genus'], 0, 1).'. '.$ligne['specie'];
									echo '</option>'."<br />";
								}
								else
								{
									echo '<option value="'.$ligne['code_spe'].'">'.$ligne['code_spe'].' - '.substr($ligne['name_genus'], 0, 1).'. '.$ligne['specie'];
									echo '</option>'."<br />";
								}
							}
							else
							{
								echo '<option value="'.$ligne['code_spe'].'">'.$ligne['code_spe'].' - '.substr($ligne['name_genus'], 0, 1).'. '.$ligne['specie'];
								echo '</option>'."<br />";
							}
						}
						echo "</select>";
						$j = 0;
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
						////// Gestion du nombres de description à afficher
						if (!isset($_POST['nbDescO_'.$i]) || empty($_POST['nbDescO_'.$i]))
							$_POST['nbDescO_'.$i] = 0;
						if (!isset($_POST['nbDesc_'.$i]) || empty($_POST['nbDesc_'.$i]))
							$_POST['nbDesc_'.$i] = 0;
							
						//// Le nb total de descriptions = ancien nb + nb à ajouter
						$_POST['nbDescO_'.$i] = $_POST['nbDescO_'.$i] + $_POST['nbDesc_'.$i];
						echo '<input type="hidden" name="nbDescO_'.$i.'" value="'.$_POST['nbDescO_'.$i].'" />';
						
						////// Lignes de descriptions à remplir
						for ($j = 0 ; $j <= $_POST['nbDescO_'.$i] ; $j++)
						{
		?>
							<tr>
								<td class="no_border">
									<?php echo $j; ?>
									<?php echo '<input type="hidden" name="des_'.$i."_".$j.'" size="3" value="'; if (isset($_POST['des_'.$i.'_'.$j])) echo $_POST['des_'.$i.'_'.$j]; else echo $j; echo '" />'; ?>
								</td>
								<td class="no_border">
									<?php
										////////// Sélection d'une référence dans une liste déroulante
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
									<?php echo '<input type="text" name="vad_'.$i.'_'.$j.'" size="1" value="'; if (isset($_POST['vad_'.$i.'_'.$j])) echo $_POST['vad_'.$i.'_'.$j]; echo '" />'; ?>
								</td>
								<td class="no_border">
									<?php echo '<input type="text" name="not_'.$i.'_'.$j.'" size="75" value="'; if (isset($_POST['not_'.$i.'_'.$j])) echo $_POST['not_'.$i.'_'.$j]; echo '" />'; ?>
								</td>
		<?php
								$k = 0;
								foreach ($Tchar as $char)
								{
		?>
									<td class="no_border">
										<?php echo '<input type="text" name="val_'.$i.'_'.$j.'_'.$k.'" size="6" value="'; if (isset($_POST['val_'.$i.'_'.$j.'_'.$k])) echo $_POST['val_'.$i.'_'.$j.'_'.$k]; echo '" />'; ?>
										<?php echo '<input type="hidden" name="cCh_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['cCh_'.$i.'_'.$j.'_'.$k])) echo $_POST['cCh_'.$i.'_'.$j.'_'.$k]; else echo $char['id']; echo '" />'; ?>
										<?php echo '<input type="hidden" name="trf_'.$i.'_'.$j.'_'.$k.'" value="'; if (isset($_POST['trf_'.$i.'_'.$j.'_'.$k])) echo $_POST['trf_'.$i.'_'.$j.'_'.$k]; echo '" />'; ?>
									</td>
		<?php
									$k++;
								}
		?>
							</tr>
		<?php
						}
		?>
						<!--<input type="hidden" name="letter" value="<?php echo $lettre; ?>" />-->
						</table>
						Enter <input type="text" name="<?php echo 'nbDesc_'.$i; ?>" size="3" /> new description(s) 
						<input type="submit" name="newRow" value="OK" />
					</td>
				</tr>
		<?php
			}
		?>
			<input type="hidden" name="nbAdd" value="<?php echo $nbAdd; ?>" />
			</table><br />
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>
</div>

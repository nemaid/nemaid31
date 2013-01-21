<?php

	// On inclut les pages de connection � la BDD et des fonctions
	include("includes/haut.php");
	include("connectionSQL.php");
	include("functions.php");

?>

<div id="admin">
<center><h1>General Data : Description addition tool</h1></center>
<br />
<p>
	Before adding new descriptions, do not forget to first add the corresponding references in its respective part.
</p>

<?php
// On r�cup�re le nombre de descriptions � ajouter
if (!isset($_GET['nbDesc']) && !isset($_POST['nbDescO']))
	$nbDesc = 1;
else
{
	if (isset($_GET['nbDesc']))
		$nbDesc = $_GET['nbDesc'];
	else
		$nbDesc = $_POST['nbDescO'];
}

// On r�cup�re l'esp�ce concern�e par l'ajout
if (isset($_GET['specie']))
	$specie = $_GET['specie'];
else
	$specie = $_POST['specie'];

// On r�cup�re les donn�es de l'esp�ce concern�e par l'ajout
$Tdata = recupDatSpe($specie);

// On r�cup�re les diff�rents "characters"
$Tchar = recupChar();

// On suppose que tout est conforme dans l'envoie des donn�es
$transf = true;
$errorRef = false;
$doubRef = false;
$dejaRef = false;
$speOrRef = false;

// On regarde si on vient d'envoyer des donn�es � transf�rer
if (isset($_POST['Send']) || isset($_POST['Ignore'])) 
{
	//$nbDesc = $_POST['nbDescO_'.$j] + 1;
	$nbDesc = $_POST['nbDescO'];
	
	//// Test d'affichage des variables � transmettre
	/*echo "<br /> Apr�s le formulaire, j'envoie : <br />";
	echo "- Code de l'esp�ce : ".$_POST['specie']."<br />";
	echo "- Nombre de descriptions : ".$nbDesc."<br />";*/
	
	//// On v�rifie que le user n'entre pas plusieurs fois la m�me r�f�rence dans les new descriptions
	for ($j = 0 ; $j < $nbDesc ; $j++)
		$Tref[$j] = $_POST['ref_'.$j];
	unset($Tref[array_search("all", $Tref)]);
	$doubRef = doublons($Tref);
	if ($doubRef == true)
		$transf = false;
	
	//// On v�rifie que le user n'entre pas plusieurs fois une r�f�rence d�j� pr�sente dans la bdd
	for ($j = 0 ; $j < count($Tdata['characters']) ; $j++)
		$TrefO[$j] = $Tdata['characters'][$j]['id_ref'];
	$Tcommun = array_intersect($Tref, $TrefO);
	//print_r($Tcommun);
	if (isset($Tcommun) && !empty($Tcommun))
	{
		for ($j = 0 ; $j < count($Tdata['characters']) ; $j++)
		{
			$transf = false;
			$dejaRef = true;
			$k = 0;
			foreach ($Tchar as $char)
			{
				if ($_POST['trf_'.$j.'_'.$k] == "true")
				{
					$transf = true;
					$dejaRef = false;
					break 2;
				}
				$k++;
			}
		}
	}
	
	if ($transf != false)
	{
		for ($j = 0 ; $j < $nbDesc ; $j++)
		{
			//// On v�rifie qu'une r�f�rence a bien �t� s�lectionn�e
			if (!isset($_POST['ref_'.$j]) || ($_POST['ref_'.$j] == "all"))
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
			else
			{
				if (!isset($_POST['vad_'.$j]))
					$_POST['vad_'.$j] = "";
				if (!isset($_POST['not_'.$j]))
					$_POST['not_'.$j] = "";
					
				//// Test d'affichage des variables � transmettre
				/*echo "- Description n� : ".$_POST['des_'.$j]."<br />";
				echo "- Code de la r�f�rence : ".$_POST['ref_'.$j]."<br />";
				echo "- Validit� : ".$_POST['vad_'.$j]."<br />";
				echo "- Commentaires : ".$_POST['not_'.$j]."<br />";*/
				
				$k = 0;
				$tour = 1;
				foreach ($Tchar as $char)
				{
					// Si l'�l�ment n'a pas d�ja �t� transf�r�, on le transfert, sinon on passe � l'�l�ment suivant
					if ($_POST['trf_'.$j.'_'.$k] != "true")
					{
						if (!isset($_POST['val_'.$j.'_'.$k]))
							$_POST['val_'.$j.'_'.$k] = "";
						
						//// Test d'affichage des variables � transmettre
						//echo "- Character : ".$_POST['cCh_'.$j.'_'.$k]." - Valeur : ".$_POST['val_'.$j.'_'.$k]."<br />";
						
						//// Si la variable est nulle et qu'on d�j� rentr� les autres info ($tour > 1), on ne transfert pas
						if (empty($_POST['val_'.$j.'_'.$k]) && ($tour > 1))
						{
						 // et b� on fait rien
						}
						else
						{
							$_POST['trf_'.$j.'_'.$k] = addDatDesc($_POST['specie'], $_POST['des_'.$j], $_POST['ref_'.$j], $_POST['vad_'.$j], 
																$_POST['not_'.$j], $_POST['cCh_'.$j.'_'.$k], $_POST['val_'.$j.'_'.$k]);
						}
						$tour++;
					}
					$k++;
				}
			}
		}
		
	}
	if ($transf == true)
	{
		//// Si le transfert a �t� fait, on se redirige sur une nouvelle page 
		header('Location: administration.php?action=add');
		exit;
	}
}
?>
<br />
<?php	
	////////// Affichage du tableau sous la forme d'un formulaire moiti� rempli et moiti� vide :
	?>

		<form name="mainF" method="post" action="addDesc.php">
		<?php
			// Si il y a eu un probl�me dans le transfert des donn�es, on le signale
			if ($transf == false)
			{
				if ($speOrRef == true)
				{
					echo "Sorry, but species and/or references are missing !<br />";
					echo "To ignore the related descriptions and to send the other data, click here : ";
					echo "<input type='submit' name='Ignore' value='Ignore' /><br /><br />";
				}
				if ($doubRef == true)
					echo "Sorry, but you enter twice the same reference for the same specie !<br />";
				if ($dejaRef == true)
					echo "Sorry, but you enter one or several reference(s) you want to enter already exists !<br />";
			}
		?>
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<h3>Existing descriptions :</h3>
			<br />
			<table id="mainTab">
				<tr>
				   <th>Species</th>
				</tr>
				<tr>
					<td>
		<?php
						echo $Tdata['code_spe'].' - '.$Tdata['specie'];
		?>
					</td>
					<td>
						<table id="<?php echo "ssTab"; ?>">
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
						for ($j = 0 ; $j < count($Tdata['characters']) ; $j++)
						{
		?>
							<tr>
								<td>
									<?php echo $j; ?>
								</td>
								<td>
		<?php
										$title = explode(' ', $Tdata['characters'][$j]['title']);
										echo $Tdata['characters'][$j]['year']." - ".$Tdata['characters'][$j]['author']." - ".$title[0]." ".$title[1]." ".$title[2]."...";
		?>
								</td>
								<td>
									<?php echo $Tdata['characters'][$j]['validity']; ?>
								</td>
								<td>
									<?php echo $Tdata['characters'][$j]['notes']; ?>
								</td>
		<?php
								$k = 0;
								foreach ($Tchar as $char)
								{
		?>
									<td>
										<?php
											if (isset($Tdata['characters'][$j]['values'][$Tchar[$k]['code']]['val']) && !empty($Tdata['characters'][$j]['values'][$Tchar[$k]['code']]['val']))
												echo $Tdata['characters'][$j]['values'][$Tchar[$k]['code']]['val'];
										?>
									</td>
		<?php
									$k++;
								}
		?>
							</tr>
		<?php
						}
						$descO = $j;
		?>
						</table>
					</td>
				</tr>
			</table>
			<br /><br />
			<h3>New descriptions :</h3>
			<br />
			<table>
				<tr>
				   <th>Species</th>
				</tr>
				<tr>
					<td>
		<?php
						echo $Tdata['code_spe'].' - '.$Tdata['specie'];
		?>
					</td>
					<td>
						<table id="<?php echo "ssTab"; ?>">
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
						////// Gestion du nombres de description � afficher
						if (!isset($_POST['nbDescO']) || empty($_POST['nbDescO']))
							$_POST['nbDescO'] = $nbDesc;
						if (!isset($_POST['nbDesc']) || empty($_POST['nbDesc']))
							$_POST['nbDesc'] = 0;
							
						//// Le nb total de descriptions = ancien nb + nb � ajouter
						$_POST['nbDescO'] = $_POST['nbDescO'] + $_POST['nbDesc'];
						echo '<input type="hidden" name="nbDescO" value="'.$_POST['nbDescO'].'" />';
						
						////// Lignes de descriptions � remplir
						for ($j = 0 ; $j < $_POST['nbDescO'] ; $j++)
						{
		?>
							<tr>
								<td class="no_border">
									<?php echo $j; ?>
									<?php echo '<input type="hidden" name="des_'.$j.'" size="3" value="'; if (isset($_POST['des_'.$j])) echo $_POST['des_'.$j]; else echo $descO+$j; echo '" />'; ?>
								</td>
								<td class="no_border">
									<?php
										////////// S�lection d'une r�f�rence dans une liste d�roulante
										$ligneRef = recupRef();
										echo "<select name='ref_".$j."'>";
										echo '<option selected="selected" value="all">--------</option><br />';
										foreach ($ligneRef as $ligne)
										{
											// On fragmente le titre dans un tableau pour n'afficher que les 3 premiers mots
											$title = explode(' ', $ligne['title']);
											if (isset($_POST['ref_'.$j]) && !empty($_POST['ref_'.$j]))
											{
												if ($ligne['id'] == $_POST['ref_'.$j])
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
									<?php echo '<input type="text" name="vad_'.$j.'" size="1" value="'; if (isset($_POST['vad_'.$j])) echo $_POST['vad_'.$j]; echo '" />'; ?>
								</td>
								<td class="no_border">
									<?php echo '<input type="text" name="not_'.$j.'" size="75" value="'; if (isset($_POST['not_'.$j])) echo $_POST['not_'.$j]; echo '" />'; ?>
								</td>
		<?php
								$k = 0;
								foreach ($Tchar as $char)
								{
		?>
									<td class="no_border">
										<?php echo '<input type="text" name="val_'.$j.'_'.$k.'" size="6" value="'; if (isset($_POST['val_'.$j.'_'.$k])) echo $_POST['val_'.$j.'_'.$k]; echo '" />'; ?>
										<?php echo '<input type="hidden" name="cCh_'.$j.'_'.$k.'" value="'; if (isset($_POST['cCh_'.$j.'_'.$k])) echo $_POST['cCh_'.$j.'_'.$k]; else echo $char['id']; echo '" />'; ?>
										<?php echo '<input type="hidden" name="trf_'.$j.'_'.$k.'" value="'; if (isset($_POST['trf_'.$j.'_'.$k])) echo $_POST['trf_'.$j.'_'.$k]; else echo "false"; echo '" />'; ?>
									</td>
		<?php
									$k++;
								}
		?>
							</tr>
		<?php
						}
		?>
						</table>
						Enter <input type="text" name="nbDesc" size="3" /> new description(s) 
						<input type="submit" name="newRow" value="OK" />
					</td>
				</tr>
			<input type="hidden" name="letter" value="<?php echo $lettre; ?>" />
			<input type="hidden" name="specie" value="<?php echo $specie; ?>" />
			<input type="hidden" name="nbAdd" value="<?php echo $nbAdd; ?>" />
			</table><br />
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>
</div>

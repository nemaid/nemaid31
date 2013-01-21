<?php

// On inclut les pages de connection � la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

// R�cup�ration du nombre de lignes � ajouter
if (!isset($_GET['nbAdd']) && !isset($_POST['nbAdd']))
	$nbAdd = 1;
else
{
	if (isset($_GET['nbAdd']))
		$nbAdd = $_GET['nbAdd'];
	else
		$nbAdd = $_POST['nbAdd'];
}

// On suppose que tout est conforme dans l'envoie des donn�es
$transf = true;
$doubSpe = false;
$noGen = false;
$noData = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des donn�es � transf�rer
if (isset($_POST['Send']) || isset($_POST['Ignore']))
{
	//// On v�rifie que le user n'entre pas plusieurs fois la m�me esp�ce
	$TspeDoub = array();
	for ($i = 0 ; $i < $nbAdd ; $i++)
		$TspeDoub[$i] = $_POST['spe_'.$i];
	//print_r($TspeDoub);
	$doubSpe = doublons($TspeDoub);
	if ($doubSpe == true)
		$transf = false;
	
	if ($transf == true)
	{
		//// On v�rifie que le user n'entre pas plusieurs fois la m�me esp�ce
		$TspeDoub = array();
		for ($i = 0 ; $i < $nbAdd ; $i++)
			$TspeDoub[$i] = $_POST['spe_'.$i];
		//print_r($TspeDoub);
		unset($TspeDoub[array_search("", $TspeDoub)]);
		$doubSpe = doublons($TspeDoub);
		if ($doubSpe == true)
			$transf = false;
	}
	
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < $nbAdd ; $i++)
		{
			//// On v�rifie qu'un genre a bien �t� s�lectionn�e
			if (!isset($_POST['gen_'.$i]) || ($_POST['gen_'.$i] == "all"))
			{
				if (isset($_POST['Ignore']))
				{
					$step = $i + 1;
					if ($step == $nbAdd)
						header('Location: administration.php?action=add');
					else
						continue;
				}
				else
				{
					$transf = false;
					$noGen = true;
					break;
				}
			}
			else
			{
				if (isset($_POST['spe_'.$i]) && isset($_POST['cod_'.$i]) && isset($_POST['gen_'.$i]) && !empty($_POST['spe_'.$i]) && !empty($_POST['cod_'.$i]) && ($_POST['gen_'.$i] != "all"))
				{
					// On part du principe que l'�l�ment n'a jamais �t� transf�r� si la variable n'existe pas encore ou qu'elle est vide
					if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
						$_POST['trf_'.$i] = false;
					
					// Si l'�l�ment n'a pas d�ja �t� transf�r�, on le transfert, sinon on passe � l'�l�ment suivant
					if ($_POST['trf_'.$i] == false)
					{					
						$exist = addSpe($_POST['gen_'.$i], $_POST['spe_'.$i], $_POST['cod_'.$i]);
						// Si $exist = true, c'est que l'�l�ment existait d�j� dans la BDD
						//echo $exist;
						if ($exist == true)
						{
							$transf = false;
							$errorBDD = true;
							break;
						}
						else // Si l'�l�ment n'existait pas encore, il a bien �t� ajout� dans on met trf = true;
							$_POST['trf_'.$i] = true;
					}
				}
				elseif (empty($_POST['spe_'.$i]) && empty($_POST['cod_'.$i]) && ($_POST['gen_'.$i] == "all"))
				{
					// h� b� on fait rien ^^
				}
				else
				{
					$transf = false;
					$noData = true;
					break;
				}
			}
		}
	}
	if ($transf == true)
	{
		//// Si le transfert a �t� fait, on se redirige sur une nouvelle page
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
	<center><h1>Species : Addition tool</h1></center>
	<br />
		<form name="mainF" method="post" action="addSpe.php">
		<?php
			// Si il y a eu un probl�me dans le transfert des donn�es, on le signale
			if ($transf == false)
			{
				if ($noGen == true)
				{
					echo "Sorry, but genera are missing !<br />";
					echo "To ignore the related descriptions and to send the other data, click here : ";
					echo "<input type='submit' name='Ignore' value='Ignore' /><br /><br />";
				}
				if ($doubSpe == true)
					echo "Sorry, but you enter twice the same species !<br />";
				if ($noData == true)
					echo "Sorry, but data are missing !<br />";
				if ($errorBDD == true)
					echo "Sorry, but one or several species you want to enter already exist(s) in the database !<br />";
			}
		?>
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table id="mainTab">
				<tr>
					<th></th>
				   <th>Genus</th>
				   <th>Code</th>
				   <th>Specie</th>
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
								//////// S�lection d'un genre dans une liste d�roulante
								$TGen = recupGen();
								echo '<select name="gen_'.$i.'">';
								echo '<option selected="selected" value="all">--------</option><br />';
								foreach ($TGen as $ligne)
								{
									if (isset($_POST['gen_'.$i]))
									{
										if ($ligne == $_POST['gen_'.$i])
											echo '<option selected="selected" value="'.$ligne.'">'.$ligne.'</option><br />';
										else
											echo '<option value="'.$ligne.'">'.$ligne.'</option><br />';
									}
									else
										echo '<option value="'.$ligne.'">'.$ligne.'</option><br />';
								}
								echo "</select>";
						?>
					</td>
					<td>
						<?php echo '<input type="text" name="cod_'.$i.'" size="3" value="'; if (isset($_POST['cod_'.$i])) echo $_POST['cod_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="spe_'.$i.'" value="'; if (isset($_POST['spe_'.$i])) echo $_POST['spe_'.$i]; echo '" />'; ?>
						<?php echo '<input type="hidden" name="trf_'.$i.'" value="'; if (isset($_POST['trf_'.$i])) echo $_POST['trf_'.$i]; echo '" />'; ?>
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

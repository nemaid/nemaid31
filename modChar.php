<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

// On récupère les données associées à la table
$Tdata = recupChar();
if ((!isset($_POST['Send'])) && $Tdata != -1)
{
	for ($i = 0 ; $i < count($Tdata) ; $i++)
	{
		if (isset($Tdata[$i]['id']))
			$_POST['id_'.$i] = $Tdata[$i]['id'];
		if (isset($Tdata[$i]['code']))
			$_POST['cod_'.$i] = $Tdata[$i]['code'];
		if (isset($Tdata[$i]['name']))
			$_POST['cha_'.$i] = $Tdata[$i]['name'];
		if (isset($Tdata[$i]['exp']))
			$_POST['exp_'.$i] = $Tdata[$i]['exp'];
		if (isset($Tdata[$i]['weight']))
			$_POST['wei_'.$i] = $Tdata[$i]['weight'];
		if (isset($Tdata[$i]['correction']))
			$_POST['cor_'.$i] = $Tdata[$i]['correction'];
		if (isset($Tdata[$i]['min']))
			$_POST['min_'.$i] = $Tdata[$i]['min'];
		if (isset($Tdata[$i]['max']))
			$_POST['max_'.$i] = $Tdata[$i]['max'];
		if (isset($Tdata[$i]['states']))
			$_POST['sta_'.$i] = $Tdata[$i]['states'];
		if (isset($Tdata[$i]['genus']))
			$_POST['gen_'.$i] = $Tdata[$i]['genus'];
	}
}

// On suppose que tout est conforme dans l'envoie des données
$transf = true;
$doubChar = false;
$noGen = false;
$noData = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des données à transférer
if (isset($_POST['Send']) || isset($_POST['Ignore']))
{
	//// On vérifie que le user n'entre pas plusieurs fois le même character
	$TchaDoub = array();
	for ($i = 0 ; $i < count($Tdata) ; $i++)
		$TchaDoub[$i] = $_POST['cha_'.$i];
	//print_r($TchaDoub);
	$doubChar = doublons($TchaDoub);
	if ($doubChar == true)
		$transf = false;
		
	if ($transf == true)
	{
		$TcodDoub = array();
		for ($i = 0 ; $i < count($Tdata) ; $i++)
			$TcodDoub[$i] = $_POST['cod_'.$i];
		//print_r($TcodDoub);
		unset($TcodDoub[array_search("all", $TcodDoub)]);
		$doubCode = doublons($TcodDoub);
		if ($doubCode == true)
			$transf = false;
	}
	
	if ($transf == true)
	{
		/// On transfert les modifications
		for ($i = 0 ; $i < count($Tdata) ; $i++)
		{
			//// On vérifie qu'un genre a bien été sélectionnée
			if (!isset($_POST['gen_'.$i]) || ($_POST['gen_'.$i] == "all"))
			{
				if (isset($_POST['Ignore']))
					continue;
				else
				{
					$transf = false;
					$noGen = true;
					break;
				}
			}
			else
			{
				if (!empty($_POST['cod_'.$i]) && !empty($_POST['cha_'.$i]) && !empty($_POST['sta_'.$i]) && ($_POST['gen_'.$i] != "all"))
				{
					// On part du principe que l'élément n'a jamais été transféré si la variable n'existe pas encore ou qu'elle est vide
					if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
						$_POST['trf_'.$i] = false;
					
					// Si l'élément n'a pas déja été transféré, on le transfert, sinon on passe à l'élément suivant
					if ($_POST['trf_'.$i] == false)
					{
						/*if (empty($_POST['min_'.$i]))
							$_POST['min_'.$i] = "NULL";
						if (empty($_POST['max_'.$i]))
							$_POST['max_'.$i] = "NULL";
						if (empty($_POST['cor_'.$i]))
							$_POST['cor_'.$i] = "NULL";
						if (empty($_POST['wei_'.$i]))
							$_POST['wei_'.$i] = "NULL";*/
						$exist = modChar($_POST['id_'.$i], $_POST['cod_'.$i], $_POST['cha_'.$i], $_POST['exp_'.$i], $_POST['wei_'.$i], $_POST['cor_'.$i],
										$_POST['min_'.$i], $_POST['max_'.$i], $_POST['sta_'.$i], $_POST['gen_'.$i]);
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
				elseif (empty($_POST['cod_'.$i]) && empty($_POST['cha_'.$i]) && empty($_POST['exp_'.$i]) && empty($_POST['wei_'.$i]) && empty($_POST['cor_'.$i]) 
						&& empty($_POST['min_'.$i]) && empty($_POST['max_'.$i]) && empty($_POST['sta_'.$i]) && ($_POST['gen_'.$i] == "all"))
				{
					//hé bé on fait rien encore
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
		//// Si le transfert a été fait, on se redirige sur une nouvelle page
		//echo "<br />Transfert accomplie !<br />";
		header('Location: administration.php?action=mod');
		exit;
	}
}
?>
<br />
<?php	
	////////// Affichage du tableau sous la forme d'un formulaire vide :
?>
<div id="admin">
	<center><h1>Characters : Modification tool</h1></center>
	<br />
	<p>
		Be careful, a modification is permanent !
	</p>
		<form name="mainF" method="post" action="modChar.php">
		<?php
			// Si il y a eu un problème dans le transfert des données, on le signale
			if ($transf == false)
			{
				if ($noGen == true)
				{
					echo "Sorry, but genera are missing !<br />";
					echo "To ignore the related descriptions and to send the other data, click here : ";
					echo "<input type='submit' name='Ignore' value='Ignore' /><br /><br />";
				}
				if (($doubChar == true) || ($doubCode == true))
					echo "Sorry, but you enter twice the same characters !<br />";
				if ($noData == true)
					echo "Sorry, but data are missing !<br />";
				if ($errorBDD == true)
					echo "Sorry, but one or several characters you want to enter already exist(s) in the database !<br />";
			}
		?>
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table id="mainTab">
				<tr>
					<th></th>
				   <th>Code</th>
				   <th>Complete name</th>
				   <th>Explanations</th>
				   <th>Weight</th>
				   <th>Correction</th>
				   <th>Min</th>
				   <th>Max</th>
				   <th>Number of States</th>
				   <th>Related genus</th>
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
						?>
					</td>
					<td>
						<?php echo '<input type="text" name="cod_'.$i.'" size="10" value="'; if (isset($_POST['cod_'.$i])) echo $_POST['cod_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="cha_'.$i.'" value="'; if (isset($_POST['cha_'.$i])) echo $_POST['cha_'.$i]; echo '" />'; ?>
						<?php echo '<input type="hidden" name="trf_'.$i.'" value="'; if (isset($_POST['trf_'.$i])) echo $_POST['trf_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="exp_'.$i.'" value="'; if (isset($_POST['exp_'.$i])) echo $_POST['exp_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="wei_'.$i.'" value="'; if (isset($_POST['wei_'.$i])) echo $_POST['wei_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="cor_'.$i.'" value="'; if (isset($_POST['cor_'.$i])) echo $_POST['cor_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="min_'.$i.'" value="'; if (isset($_POST['min_'.$i])) echo $_POST['min_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="max_'.$i.'" value="'; if (isset($_POST['max_'.$i])) echo $_POST['max_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="sta_'.$i.'" value="'; if (isset($_POST['sta_'.$i])) echo $_POST['sta_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php
							//////// Sélection d'un genre dans une liste déroulante
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
				</tr>
		<?php
			}
		?>
			</table><br />
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>
</div>

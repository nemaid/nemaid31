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

// On suppose que tout est conforme dans l'envoie des données
$transf = true;
$doubGen = false;
$noData = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des données à transférer
if (isset($_POST['Send']))
{
	//// On vérifie que le user n'entre pas plusieurs fois la même espèce
	$TgenDoub = array();
	for ($i = 0 ; $i < $nbAdd ; $i++)
		$TgenDoub[$i] = $_POST['gen_'.$i];
	//print_r($TgenDoub);
	$doubGen = doublons($TgenDoub);
	if ($doubGen == true)
		$transf = false;
	
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < $nbAdd ; $i++)
		{
			if (isset($_POST['gen_'.$i]) && !empty($_POST['gen_'.$i]))
			{
				// On part du principe que l'élément n'a jamais été transféré si la variable n'existe pas encore ou qu'elle est vide
				if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
					$_POST['trf_'.$i] = false;
				
				// Si l'élément n'a pas déja été transféré, on le transfert, sinon on passe à l'élément suivant
				if ($_POST['trf_'.$i] == false)
				{					
					$exist = addGen($_POST['gen_'.$i]);
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
	<center><h1>Genera : Addition tool</h1></center>
	<br />
		<form name="mainF" method="post" action="addGen.php">
		<?php
			// Si il y a eu un problème dans le transfert des données, on le signale
			if ($transf == false)
			{
				if ($doubGen == true)
					echo "Sorry, but you enter twice the same genus !<br />";
				if ($errorBDD == true)
					echo "Sorry, but one or several genera you want to enter already exist(s) in the database !<br />";
				if ($noData == true)
					echo "Sorry, but data are missing !<br />";
			}
		?>
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table id="mainTab">
				<tr>
					<th></th>
				   <th>Genus</th>
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
						<?php echo '<input type="text" name="gen_'.$i.'" value="'; if (isset($_POST['gen_'.$i])) echo $_POST['gen_'.$i]; echo '" />'; ?>
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

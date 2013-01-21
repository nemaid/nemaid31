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
$noData = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des donn�es � transf�rer
if (isset($_POST['Send']))
{
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < $nbAdd ; $i++)
		{
			if (!empty($_POST['aut_'.$i]) && !empty($_POST['pub_'.$i]) && !empty($_POST['tit_'.$i]))
			{
				// On part du principe que l'�l�ment n'a jamais �t� transf�r� si la variable n'existe pas encore ou qu'elle est vide
				if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
					$_POST['trf_'.$i] = false;
				
				// Si l'�l�ment n'a pas d�ja �t� transf�r�, on le transfert, sinon on passe � l'�l�ment suivant
				if ($_POST['trf_'.$i] == false)
				{					
					$exist = addRef($_POST['aut_'.$i], $_POST['pub_'.$i], $_POST['yea_'.$i], $_POST['tit_'.$i], $_POST['jou_'.$i]);
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
			elseif (empty($_POST['aut_'.$i]) && empty($_POST['pub_'.$i]) && empty($_POST['tit_'.$i]) && empty($_POST['yea_'.$i]) && empty($_POST['jou_'.$i]))
			{
				// h� b� on fait toujours rien
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
	<center><h1>References : Addition tool</h1></center>
	<br />
		<form name="mainF" method="post" action="addRef.php">
		<?php
			// Si il y a eu un probl�me dans le transfert des donn�es, on le signale
			if ($transf == false)
			{
				if ($noData == true)
					echo "Sorry, but data are missing !<br />";
				if ($errorBDD == true)
					echo "Sorry, but one or several references you want to enter already exist(s) in the database !<br />";
			}
		?>
			<input type="submit" name="Send" value="Add" onclick="return(confirm('Are you sure you want to add these data ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table id="mainTab">
				<tr>
					<th></th>
				   <th>Author(s)</th>
				   <th>Year of publication</th>
				   <th>Year of work</th>
				   <th>Title</th>
				   <th>Journal</th>
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
						<?php echo '<input type="text" name="aut_'.$i.'" value="'; if (isset($_POST['aut_'.$i])) echo $_POST['aut_'.$i]; echo '" />'; ?>
						<?php echo '<input type="hidden" name="trf_'.$i.'" value="'; if (isset($_POST['trf_'.$i])) echo $_POST['trf_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="pub_'.$i.'" value="'; if (isset($_POST['pub_'.$i])) echo $_POST['pub_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="yea_'.$i.'" value="'; if (isset($_POST['yea_'.$i])) echo $_POST['yea_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="tit_'.$i.'" value="'; if (isset($_POST['tit_'.$i])) echo $_POST['tit_'.$i]; echo '" />'; ?>
					</td>
					<td>
						<?php echo '<input type="text" name="jou_'.$i.'" value="'; if (isset($_POST['jou_'.$i])) echo $_POST['jou_'.$i]; echo '" />'; ?>
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

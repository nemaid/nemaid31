<?php

// On inclut les pages de connection à la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

// On récupère la lettre
if (!isset($_GET['lettre']) && !isset($_POST['lettre']))
	$lettre = 'A';
else
{
	if (isset($_GET['lettre']))
		$lettre = $_GET['lettre'];
	else
		$lettre = $_POST['lettre'];
}

// On récupère les données associées à la table
$Tdata = recupRefLettre($lettre);
if ((!isset($_POST['Send'])) && $Tdata != -1)
{
	for ($i = 0 ; $i < count($Tdata) ; $i++)
	{
		if (isset($Tdata[$i]['id']))
			$_POST['id_'.$i] = $Tdata[$i]['id'];
		if (isset($Tdata[$i]['author']))
			$_POST['aut_'.$i] = $Tdata[$i]['author'];
		if (isset($Tdata[$i]['wYear']))
			$_POST['yea_'.$i] = $Tdata[$i]['wYear'];
		if (isset($Tdata[$i]['year']))
			$_POST['pub_'.$i] = $Tdata[$i]['year'];
		if (isset($Tdata[$i]['title']))
			$_POST['tit_'.$i] = $Tdata[$i]['title'];
		if (isset($Tdata[$i]['journal']))
			$_POST['jou_'.$i] = $Tdata[$i]['journal'];
	}
}

// On suppose que tout est conforme dans l'envoie des données
$transf = true;
$noData = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des données à transférer
if (isset($_POST['Send']))
{
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < count($Tdata) ; $i++)
		{
			if (!empty($_POST['aut_'.$i]) && !empty($_POST['pub_'.$i]) && !empty($_POST['tit_'.$i]))
			{
				// On part du principe que l'élément n'a jamais été transféré si la variable n'existe pas encore ou qu'elle est vide
				if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
					$_POST['trf_'.$i] = false;
				
				// Si l'élément n'a pas déja été transféré, on le transfert, sinon on passe à l'élément suivant
				if ($_POST['trf_'.$i] == false)
				{					
					$exist = modRef($_POST['id_'.$i], $_POST['aut_'.$i], $_POST['pub_'.$i], $_POST['yea_'.$i], $_POST['tit_'.$i], $_POST['jou_'.$i]);
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
			elseif (empty($_POST['aut_'.$i]) && empty($_POST['pub_'.$i]) && empty($_POST['tit_'.$i]) && empty($_POST['yea_'.$i]) && empty($_POST['jou_'.$i]))
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
<?php	
	////////// Affichage du tableau sous la forme d'un formulaire vide :
?>
<div id="admin">
	<center><h1>References : Modification tool</h1></center>
	<br />
	<p>
		Be careful, a modification is permanent !
	</p>
<?php
	if ($Tdata != -1)
	{

?>
		<form name="mainF" method="post" action="modRef.php">
		<?php
			// Si il y a eu un problème dans le transfert des données, on le signale
			if ($transf == false)
			{
				if ($noData == true)
					echo "Sorry, but data are missing !<br />";
				if ($errorBDD == true)
					echo "Sorry, but one or several references you want to enter already exist(s) in the database !<br />";
			}
		?>
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
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
			<input type="hidden" name="lettre" value="<?php echo $lettre; ?>" />
			</table><br />
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>
<?php
	}
	else
	{
		echo "<br /><br />Sorry, there is no references which begins by the letter '".$lettre."'.<br /><br />";
		echo '<input type="button" name="Return" value="Return" onClick="document.location=\'administration.php?action=adm\'" />';
	}
?>
</div>

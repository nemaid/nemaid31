<?php

// On inclut les pages de connection � la BDD et des fonctions
include("connectionSQL.php");
include("functions.php");
include("includes/haut.php");

// R�cup�ration des donn�es associ�es
$Tdata = recupGen();
if (!isset($_POST['Send']) && ($Tdata != -1))
{
	for ($i = 0 ; $i < count($Tdata) ; $i++)
	{
		if (isset($Tdata[$i]))
			$_POST['gen_'.$i] = $Tdata[$i];
	}
}

// On suppose que tout est conforme dans l'envoie des donn�es
$transf = true;
$doubGen = false;
$noData = false;
$errorBDD = false;

// On regarde si on vient d'envoyer des donn�es � transf�rer
if (isset($_POST['Send']))
{
	//// On v�rifie que le user n'entre pas plusieurs fois la m�me esp�ce
	$TgenDoub = array();
	for ($i = 0 ; $i < count($Tdata) ; $i++)
		$TgenDoub[$i] = $_POST['gen_'.$i];
	//print_r($TgenDoub);
	$doubGen = doublons($TgenDoub);
	if ($doubGen == true)
		$transf = false;
	
	if ($transf == true)
	{
		//// On transfert les modifications
		for ($i = 0 ; $i < count($Tdata) ; $i++)
		{
			if (isset($_POST['gen_'.$i]) && !empty($_POST['gen_'.$i]))
			{
				// On part du principe que l'�l�ment n'a jamais �t� transf�r� si la variable n'existe pas encore ou qu'elle est vide
				if (!isset($_POST['trf_'.$i]) || empty($_POST['trf_'.$i]))
					$_POST['trf_'.$i] = false;
				
				// Si l'�l�ment n'a pas d�ja �t� transf�r�, on le transfert, sinon on passe � l'�l�ment suivant
				if ($_POST['trf_'.$i] == false)
				{					
					$exist = modGen($_POST['gen_'.$i]);
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
		}
	}
	if ($transf == true)
	{
		//// Si le transfert a �t� fait, on se redirige sur une nouvelle page
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
	<center><h1>Genera : Modification tool</h1></center>
	<br />
	<p>
		Be careful, a modification is permanent !
	</p>
		<form name="mainF" method="post" action="modGen.php">
		<?php
			// Si il y a eu un probl�me dans le transfert des donn�es, on le signale
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
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
			<br />
			<table id="mainTab">
				<tr>
					<th></th>
				   <th>Genus</th>
				</tr>
		<?php
			for ($i = 0 ; $i < count($Tdata) ; $i++)
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
			</table><br />
			<input type="submit" name="Send" value="Modify" onclick="return(confirm('Are you sure you want to validate the modifications ?'));" />
			<input type="button" name="Return" value="Return" onClick="document.location='administration.php?action=adm'" />
		</form>
</div>

<?php
	include("includes/haut.php");
	include("connectionSQL.php");
	include("functions.php");
?>
<center><h1>The database management</h1></center><br />
<?php
	if (isset($_GET['action']))
	{
		switch ($_GET['action'])
		{
			case "mod" : echo "Your modifications have been applied.<br /><br />"; break;
			case "add" : echo "The selected data have been added.<br /><br />"; break;
			case "del" : echo "The selected data have been deleted.<br /><br />"; break;
			default : NULL;
		}
	}
	if (isset($_POST['sendAdd']))
	{
		if (isset($_POST['nbAdd']) && !empty($_POST['nbAdd']))
		{
			switch ($_POST['selTableAdd'])
			{
				case "characters" : header("Location:addChar.php?nbAdd=".$_POST['nbAdd']); break;
				case "data" : header("Location:addGeneral.php?nbAdd=".$_POST['nbAdd']); break;
				case "genera" : header("Location:addGen.php?nbAdd=".$_POST['nbAdd']); break;
				case "references" : header("Location:addRef.php?nbAdd=".$_POST['nbAdd']); break;
				case "species" : header("Location:addSpe.php?nbAdd=".$_POST['nbAdd']); break;
				case "users" : header("Location:inscription.php"); break;
				default : echo "Sorry, but you do not select a table !<br /><br />";
			}
		}
		else
			echo "Sorry, but the number of lines to add is missing !<br /><br />";
	}
	elseif (isset($_POST['sendAddDesc']))
	{
		if (isset($_POST['nbDesc']) && !empty($_POST['nbDesc']) && isset($_POST['selSpecie']) && !empty($_POST['selSpecie']))
		{
			header("Location:addDesc.php?nbDesc=".$_POST['nbDesc']."&specie=".$_POST['selSpecie']);
		}
		else
			echo "Sorry, but information are missing to add descriptions !<br /><br />";
	}
?>
<input type="button" name="Return" value="Return" onClick="document.location='main.php'" /><br />
<form method="post" action="administration.php">
	<table>
		<tr>
			<td class="admin no_border">
				<fieldset class="admin">
				<legend><h3>The addition part</h3></legend>
			<?php
				$Ttable = recupTable();
			?>
				- To add <input type="text" name="nbAdd" size="3" /> lines in the 
				<select name="selTableAdd">
					<option value="all">--------</option><br />
			<?php
					for ($i = 0 ; $i < count($Ttable) ; $i++)
					{
						if ($Ttable[$i] != "users")
							echo "<option value='".$Ttable[$i]."'>".$Ttable[$i]."</option><br />";
					}
			?>
				</select>
				table: <input type="submit" name="sendAdd" value="OK" />.<br />
			<?php
				$Tspe = recupSpe();
			?>
				- To add <input type="text" name="nbDesc" size="3" /> 
				descriptions to the 
				<select name="selSpecie">
					<option value="all">--------</option><br />
			<?php
					for ($i = 0 ; $i < count($Tspe) ; $i++)
						echo "<option value='".$Tspe[$i]['code_spe']."'>".$Tspe[$i]['code_spe'].' - '.substr($Tspe[$i]['name_genus'], 0, 1).'. '.$Tspe[$i]['specie']."</option><br />";
			?>
				</select>
				 specie : <input type="submit" name="sendAddDesc" value="OK" />.
				 </fieldset>
			</td>
			<td class="admin no_border">
				<fieldset class="admin">
				<legend><h3>The modification part</h3></legend>
			<?php
				$Ttable = recupTable();
			?>
				To modify the 
				<select id="selTableMod">
					<option value="all">--------</option><br />
			<?php
					for ($i = 0 ; $i < count($Ttable) ; $i++)
					{
						if ($Ttable[$i] != "genera")
							echo "<option value='".$Ttable[$i]."'>".$Ttable[$i]."</option><br />";
					}
			?>
				</select>
				 table in which elements begin by :<br /><br />
			<?php
				////////// Alphabet de sélection
				echo "<center><h3>";
				foreach(range('A', 'Z') as $lettre)
				{
					echo "<input type='button' id='mod_".$lettre."' value='".$lettre."' onclick='envoyerVers(\"mod\", \"".$lettre."\")'/>";
					if ($lettre != 'Z')
						echo " - ";
				}
				echo "</h3></center><br /><br />";
			?>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td class="admin no_border">
				<fieldset class="admin">
				<legend><h3>The deletion part</h3></legend>
			<?php
				$Ttable = recupTable();
			?>
				To delete data in the 
				<select id="selTableDel">
					<option value="all">--------</option><br />
			<?php
					for ($i = 0 ; $i < count($Ttable) ; $i++)
						echo "<option value='".$Ttable[$i]."'>".$Ttable[$i]."</option><br />";
			?>
				</select>
				 table in which elements begin by :<br /><br />
			<?php
				////////// Alphabet de sélection
				echo "<center><h3>";
				foreach(range('A', 'Z') as $lettre)
				{
					echo "<input type='button' id='del_".$lettre."' value='".$lettre."' onclick='envoyerVers(\"del\", \"".$lettre."\")'/>";
					if ($lettre != 'Z')
						echo " - ";
				}
				echo "</h3></center><br /><br />";
			?>
			</fieldset>
			</td>
			<td class="admin no_border">
				<!--<fieldset class="admin">
				<legend><h3>The users management</h3></legend>
				</fieldset>-->
			</td>
		</tr>
	</table>
</form>

<?php 
session_start();
header('Content-type: text/html; charset=utf-8');
include('includes/functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="content-language" content="en-US" />
		<title>Nemaid 3.1</title>
		<meta name="language" content="en-US" />
		<link rel="stylesheet" media="screen" type="text/css" href="style.css" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="includes/js_function.js"></script>
	</head>
	<body>
		<div id="header">
			<center>
				<a href="<?php if(!empty($_SESSION['user_id'])) { echo ROOTPATH.'/main.php'; } else { echo ROOTPATH.'/index.php';} ?>">
					<img src="images/bannTemp.png" border="2px darkblue">

				</a>
			</center>
<center id="author">
							Designed by Renaud Fortuner (fortuner@wanadoo.fr) - Developped by students of the University of Poitiers, France							
						</center>
			<?php
				if(!empty($_SESSION['user_id']))
				{
					if (curPageName()!='deconnexion.php' && curPageName()!='connexion.php' && curPageName()!='index.php' )
					{
			?>
						<center>
							<table class="menu">
								<tr>	
									<?php 
									if (curPageName()=='new_sample.php') {
										echo'<td class="menu"><a href="javascript:warningMessChangeTab(true,\'main.php\')">Home</a></td>';
										echo'<td class="menu"><a href="select_genus.php">Select genus</a></td>';		
										echo'<td class="menu"><a href="new_sample.php">Your samples</a></td>';
										//echo'<td class="menu"><a href="parameters.php">Set parameters</a></td>';
										echo'<td class="menu"><a href="comparaison.php">Perform a comparison</a></td>';

										if(isset($_SESSION['admin'])){
											echo '<td class="menu"><a href="addSpecies.php">Add a line in the Database </a></td>';
											echo '<td class="menu"><a href="visuBDD.php">Databases</a></td>';}
										else
											echo '<td class="menu"><a href="visuBDD.php">Databases</a></td>';
									echo'<td class="menu"><a href="FAQ.php">Help</a></td>';
									}
									else {
										echo'<td class="menu"><a href="main.php">Home</a></td>';
										echo'<td class="menu"><a href="select_genus.php">Select genus</a></td>';		
										echo'<td class="menu"><a href="new_sample.php">Your samples</a></td>';
										//echo'<td class="menu"><a href="parameters.php">Set parameters</a></td>';
										echo'<td class="menu"><a href="comparaison.php">Perform a comparison</a></td>';

										if(isset($_SESSION['admin'])){
											echo '<td class="menu"><a href="addSpecies.php">Add a line in the Database </a></td>';
											echo '<td class="menu"><a href="visuBDD.php">Databases</a></td>';}
										else
											echo '<td class="menu"><a href="visuBDD.php">Databases</a></td>';
									echo'<td class="menu"><a href="FAQ.php">Help</a></td>';
}
?>

								<tr>
							</table>
						</center>
						
						<form action="deconnexion.php" method="post">
							<input class="alignRight" type="submit" name="deconnexion" value="Logout" />
						</form>
			<?php
					}
				}
				elseif (curPageName()!='index.php' && curPageName()!='connexion.php' && curPageName()!='inscription.php' && curPageName()!='account_creation.php' && curPageName()!='resetPassword.php' && curPageName()!='newPassword.php')
				{
					$informations = Array(true,'You must be logged to access to this section.',ROOTPATH.'/index.php',2);
					require_once('informations.php');
					exit();
				}
			?>
			<br />
		</div>
		<div id="content">

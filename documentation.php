<?php
include('../includes/haut.php');

if(!empty($_SESSION['user_id'])) 
{
	if (curPageName()!='deconnexion.php' && curPageName()!='connexion.php' && curPageName()!='index.php') 
	{ 
	?>
		<ul>
			<li><a href="FAQ.php">FAQ</a></li>
		</ul>
	<?php
	}
}
?>

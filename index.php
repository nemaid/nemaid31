<?php include('includes/haut.php'); 
if(isset($_SESSION['user_id'])) { 
	header('Location: '.ROOTPATH.'/main.php');	exit();} ?>
<table>
	<tr>
		<td class="index no_border">				<h3>Authentification</h3>
			<form action="connexion.php" method="post">
				<table>
					<tr>
						<td class="no_border">Email:</td> 
						<td class="no_border"> <input type="text" name="login" /> </td>
					</tr>
					<tr>
						<td class="no_border">Password:</td> 
						<td class="no_border"> <input type="password" name="password" /> </td>
					</tr>
				</table>
				<br />
				<center><input type="submit" name="connection" value="Login" /></center>
			</form>
			<br />
			<center><a href="resetPassword.php">I forgot my password</a></center>
			<br /><br />
			<center><a href="inscription.php">Get an account</a></center>
		</td>
		<td class="no_border">
			<h1>Welcome to NEMAID 3.1 !</h1>
			<p>NEMAID is an identification aid that calculates the similarity between the specimens to be identified and all of the species in a genus. It does not give one answer but a list of species that are most similar to the unknown specimens. It is then up to the user to study the published descriptions of the likely candidates and make the final identification.</p>
			<p>NEMAID was first implemented in 1983 and an improved version was proposed in 1985. Nemaid 3.0 was implemented in 2012 and the current version (3.1) in 2013. The earlier versions were described in the following publications:</p>
			<ul>
				<li> <a href="http://genisys.prd.fr/Fortuner_1983b.pdf"> Fortuner (1983): short presentation of the program </a> </li>
				<li> <a href="http://genisys.prd.fr/Fortuner_Wong_1983.pdf"> Fortuner &amp; Wong (1983): NEMAID 1.0 user's manual </a> </li>
				<li> <a href="http://genisys.prd.fr/Fortuner_Wong_1985.pdf"> Fortuner &amp; Wong (1985): published rationale for the program </a> </li>
				<li> <a href="http://genisys.prd.fr/Fortuner_Ahmadi_1986.pdf"> Fortuner &amp; Ahmadi (1986): NEMAID 2.0  user's manual </a> </li>
				<li> <a href="http://genisys.prd.fr/Fortuner_1986d.pdf"> Fortuner (1986): modification of the computation algorithm for qualitative characters </a> </li>
			</ul>
			<p><b>Nemaid 3.1 is free, but you must be registered to use it.</b></p>
		</td>
	</tr>
</table>

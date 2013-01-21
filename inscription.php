<?php 
include('includes/haut.php'); 
vidersession();
?>

	<center><h3>Account creation</h3></center>
	<center>	<form action="account_creation.php" method="post">
		<table>
			<tr>
				<td class="no_border"><b>First name*</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="text" name="firstname"></td>
			</tr>
			<tr>
				<td class="no_border"><b>Last name*</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="text" name="lastname"></td>
			</tr>
			<tr>
				<td class="no_border"><b>Country*</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="text" name="country"></td>
			</tr>
			<tr>
				<td class="no_border"><b>City</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="text" name="town"></td>
			</tr>
			<tr>
				<td class="no_border"><b>Institution</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="text" name="institution"></td>
			</tr>
			<tr>
				<td class="no_border"><b>Email*</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="text" name="email"></td>
			</tr>
			<tr>
				<td class="no_border"><b>Password*</b></td>
				<td class="no_border">: </td>
				<td class="no_border"><input type="password" name="password"></td>
			</tr>
		</table>
	</center>	<br />
	<center>
			<input type="button" name="Return" value="Return" onClick="javascript:history.go(-1);" /> - 
			<input type="submit" value="Create" name="create_account">
	</center>
	</form>
<?php
$_SESSION['inscrit'] = "en cours";
?>

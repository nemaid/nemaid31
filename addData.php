<?php
include('includes/haut.php');
include('connectionSQL.php');
/*
 * Created on 19 mars 2013 by Thomas CROS
 *
 * 
 * 
 */
 //on récupère le code de l'espèce
 $code_spe = $_POST['code_spe'];


if(isset ($_POST['id_ref'])){
	$id_ref = $_POST['id_ref'];
	

}

else {
//on récupère toutes les données de la références créee prélalblement	
$author = $_POST['author'];
echo $author;
$year = $_POST['year'];
echo $year;
$publi_in = $_POST['publi_in'];
echo $publi_in;
$title = $_POST['title'];
echo $title;
$journal = $_POST['journal'];
echo $journal;

//requete d'insertion dans la base de la ligne de référence

mysql_query("INSERT INTO `references` (id_ref, author, year, publi_in, title, journal)
VALUES ('', '$author', '$year', '$publi_in', '$title', '$journal')");

$id_ref = mysql_insert_id();
echo "The reference has been added in the Database";	
}
 
	echo $id_ref; echo "</br>";
	echo  $code_spe;

?>

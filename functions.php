<?php
	include ("connectionSQL.php");
	
	////////// Fonction qui récupères les genres
	function recupGen()
	{
		$req = "SELECT * FROM `genera` ORDER BY name_genus";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$ligne[$i] = $rep['name_genus'];
			$i++;
		}
		if (!empty($ligne))
			return $ligne;
		else
			return -1;
	}
	
	////////// Fonction qui récupères les données relatives à une seule espèce
	function recupDatSpe($codeSpe)
	{
		$req = "SELECT define.id_def, data.id_data, define.code_spe, specie, species.name_genus, description,
						define.id_ref, author, publi_in, title, validity, notes, value, data.id_char, code_char
				FROM `species`
				LEFT OUTER JOIN `define` ON define.code_spe = species.code_spe
				LEFT OUTER JOIN `references` ON define.id_ref = references.id_ref
				LEFT OUTER JOIN `data` ON define.id_data = data.id_data
				LEFT OUTER JOIN `characters` ON data.id_char = characters.id_char
				WHERE define.code_spe = '".$codeSpe."'";
		$req .= " ORDER BY specie, description, data.id_data";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());

		/// Récupération des données et arrangeage dans un bô tableau :
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Tdata['specie'] = $rep['specie'];
			$Tdata['code_spe'] = $rep['code_spe'];
			$Tdata['characters'][$rep['description']]['desc'] = $rep['description'];
			$Tdata['characters'][$rep['description']]['id_ref'] = $rep['id_ref'];
			$Tdata['characters'][$rep['description']]['author'] = $rep['author'];
			$Tdata['characters'][$rep['description']]['year'] = $rep['publi_in'];
			$Tdata['characters'][$rep['description']]['title'] = $rep['title'];
			$Tdata['characters'][$rep['description']]['validity'] = $rep['validity'];
			$Tdata['characters'][$rep['description']]['notes'] = $rep['notes'];
			$Tdata['characters'][$rep['description']]['values'][$rep['code_char']]['def'] = $rep['id_def'];
			$Tdata['characters'][$rep['description']]['values'][$rep['code_char']]['data'] = $rep['id_data'];
			$Tdata['characters'][$rep['description']]['values'][$rep['code_char']]['code'] = $rep['code_char'];
			$Tdata['characters'][$rep['description']]['values'][$rep['code_char']]['id_char'] = $rep['id_char'];
			$Tdata['characters'][$rep['description']]['values'][$rep['code_char']]['val'] = $rep['value'];
			$i++;
		}
		if ($i > 0)
			return $Tdata;
		else
			return -1;
	}
	
	////////// Fonction qui récupères les références
	function recupRef()
	{
		$req = "SELECT * FROM `references` ORDER BY author";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$ligneRef[$i]['id'] = $rep['id_ref'];
			$ligneRef[$i]['author'] = $rep['author'];
			$ligneRef[$i]['wYear'] = $rep['year'];
			$ligneRef[$i]['year'] = $rep['publi_in'];
			$ligneRef[$i]['title'] = $rep['title'];
			$ligneRef[$i]['journal'] = $rep['journal'];
			$i++;
		}
		if (!empty($ligneRef))
			return $ligneRef;
		else
			return -1;
	}
	
	////////// Fonction qui récupères les références
	function recupRefLettre($letter)
	{
		$req = "SELECT * FROM `references` WHERE author LIKE '".$letter."%' ORDER BY author";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$ligneRef[$i]['id'] = $rep['id_ref'];
			$ligneRef[$i]['author'] = $rep['author'];
			$ligneRef[$i]['wYear'] = $rep['year'];
			$ligneRef[$i]['year'] = $rep['publi_in'];
			$ligneRef[$i]['title'] = $rep['title'];
			$ligneRef[$i]['journal'] = $rep['journal'];
			$i++;
		}
		if (!empty($ligneRef))
			return $ligneRef;
		else
			return -1;
	}
	
	//////// Fonction qui récupère les identifiants des différents "characters"
	function recupChar()
	{
		$req = "SELECT * FROM characters ORDER BY name_genus";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($ligne = mysql_fetch_array($ret))
		{
			$Tchar[$i]['id'] = $ligne['id_char'];
			$Tchar[$i]['code'] = $ligne['code_char'];
			$Tchar[$i]['name'] = $ligne['name_char'];
			$Tchar[$i]['exp'] = $ligne['explanations'];
			$Tchar[$i]['weight'] = $ligne['weight'];
			$Tchar[$i]['correction'] = $ligne['correction'];
			$Tchar[$i]['min'] = $ligne['min'];
			$Tchar[$i]['max'] = $ligne['max'];
			$Tchar[$i]['states'] = $ligne['nb_states'];
			$Tchar[$i]['genus'] = $ligne['name_genus'];
			$i++;
		}
		if (!empty($Tchar))
			return $Tchar;
		else
			return -1;
	}
	
	//////// Fonction qui récupère les espèces
	function recupSpe()
	{
		$req = "SELECT name_genus, code_spe, specie FROM species ORDER BY specie";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($ligne = mysql_fetch_array($ret))
		{
			$Tspe[$i]['code_spe'] = $ligne['code_spe'];
			$Tspe[$i]['specie'] = $ligne['specie'];
			$Tspe[$i]['name_genus'] = $ligne['name_genus'];
			$i++;
		}
		if (!empty($Tspe))
			return $Tspe;
		else
			return -1;
	}
	
	//////// Fonction qui récupère les espèces
	function recupSpeLettre($letter)
	{
		$req = "SELECT * FROM species WHERE specie LIKE '".$letter."%' ORDER BY specie";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($ligne = mysql_fetch_array($ret))
		{
			$Tspe[$i]['code'] = $ligne['code_spe'];
			$Tspe[$i]['specie'] = $ligne['specie'];
			$Tspe[$i]['genus'] = $ligne['name_genus'];
			$i++;
		}
		if (!empty($Tspe))
			return $Tspe;
		else
			return -1;
	}
	
	//////// Fonction qui récupère les espèces
	function recupUserLettre($letter)
	{
		$req = "SELECT * FROM users WHERE l_name LIKE '".$letter."%' ORDER BY l_name";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($ligne = mysql_fetch_array($ret))
		{
			$Tspe[$i]['id'] = $ligne['id_user'];
			$Tspe[$i]['l_name'] = $ligne['l_name'];
			$Tspe[$i]['f_name'] = $ligne['f_name'];
			$Tspe[$i]['email'] = $ligne['email'];
			$Tspe[$i]['password'] = $ligne['password'];
			$Tspe[$i]['institution'] = $ligne['institution'];
			$Tspe[$i]['city'] = $ligne['town'];
			$Tspe[$i]['country'] = $ligne['country'];
			$Tspe[$i]['admin'] = $ligne['admin'];
			$i++;
		}
		if (!empty($Tspe))
			return $Tspe;
		else
			return -1;
	}
	
	//////// Fonction qui récupère les données des espèces commençant par une lettre en particulier
	function recupData()
	{
		$req = "SELECT define.id_ref, define.code_spe, specie, species.name_genus, description, author, publi_in, title, validity, notes
				FROM `species`
				LEFT OUTER JOIN `define` ON define.code_spe = species.code_spe
				LEFT OUTER JOIN `references` ON define.id_ref = references.id_ref";
		$req .= " ORDER BY specie, description";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());

		/// Récupération des données et arrangeage dans un bô tableau :
		$specie = "";
		$i = -1;
		$cpt = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			/// On ne garde qu'une seule fois les données (id et nom de l'espèce) communes à toutes les lignes mysql récupérées :
			if ($rep['specie'] != $specie)
			{
				$i++;
				$Tdata[$i]['specie'] = $rep['specie'];
				$Tdata[$i]['code_spe'] = $rep['code_spe'];
				$specie = $rep['specie'];
				$desc = -1;
			}
			/// On construit le reste du tableau avec chaque character de chaque références de l'espèce :
			$Tdata[$i]['characters'][$rep['description']]['id_ref'] = $rep['id_ref'];
			$Tdata[$i]['characters'][$rep['description']]['author'] = $rep['author'];
			$Tdata[$i]['characters'][$rep['description']]['year'] = $rep['publi_in'];
			$Tdata[$i]['characters'][$rep['description']]['title'] = $rep['title'];
			$Tdata[$i]['characters'][$rep['description']]['validity'] = $rep['validity'];
			$Tdata[$i]['characters'][$rep['description']]['notes'] = $rep['notes'];
			$cpt++;
		}
		if ($cpt > 0)
			return $Tdata;
		else
			return -1;
	}
	
	//////// Fonction récupère le nom des tables de la base de données
	function recupTable()
	{
		$req = "SELECT * FROM `tables`";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Ttable[$i] = $rep['table_name'];
			$i++;
		}
		if (!empty($Ttable))
			return $Ttable;
		else
			return -1;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour une modification
	function modData($idSpe, $des, $ref, $vad, $not, $def, $dat, $val, $idChar)
	{	
		// On envoie les modif dans la table data
		if (!empty($val))
		{
			if (!empty($dat))
			{
				$req = "REPLACE INTO data (id_data, value, id_char) VALUES (".$dat.", ".$val.", ".$idChar.")";
				//echo "<br /><br />".$req."<br />";
				$ret = mysql_query($req) or die (mysql_error());
				//echo "Ok dans DATA, sans suppression.<br />";
			}
			else
			{
				$req = "INSERT INTO data (value, id_char) VALUES (".$val.", ".$idChar.")";
				//echo "<br /><br />".$req."<br />";
				$ret = mysql_query($req) or die (mysql_error());
				
				//// On récupère l'identifiant clef primaire qui vient d'être généré
				$dat = mysql_insert_id();
				//echo "Ok dans DATA : ".$dat."<br />";
			}
		}
		else
		{
			if (!empty($dat))
			{
				$req = "DELETE FROM data WHERE id_data = ".$dat;
				//echo "<br /><br />".$req."<br />";
				$dat = "NULL";
				$ret = mysql_query($req) or die (mysql_error());
				//echo "Ok dans DATA, avec suppression.<br />";
			}
		}
		
		if (!isset($dat) || empty($dat))
			$dat = "NULL";
		
		if (!isset($def) || empty($def))
		{
			if ($dat != "NULL")
			{
				$req = "INSERT INTO define (id_def, validity, description, notes, code_spe, id_ref, id_data)
						VALUES (NULL, '".$vad."', ".$des.", '".$not."', '".$idSpe."', ".$ref.", ".$dat.")";
				//echo "<br />".$req."<br />";
				// On envoie les modif dans la table define
				$ret = mysql_query($req) or die (mysql_error());
			}
		}
		else
		{
			$req = "REPLACE INTO define (id_def, validity, description, notes, code_spe, id_ref, id_data)
					VALUES (".$def.", '".$vad."', ".$des.", '".$not."', '".$idSpe."', ".$ref.", ".$dat.")";
			//echo "<br />".$req."<br />";
			// On envoie les modif dans la table define
			$ret = mysql_query($req) or die (mysql_error());
		}
		//echo "Ok dans DEFINE<br />";
		//// On retourne true pour montrer que le transfert a bien été fait
		return false;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout complet
	function addData($idSpe, $des, $ref, $vad, $not, $char, $val, $entree)
	{
		//// On vérifie que l'espèce et la ref associée n'existe pas déjà quand on rentre un premier character
		$exist = false;
		if ($entree == 1)
		{
			$req = "SELECT id_def FROM define WHERE code_spe = '".$idSpe."' AND id_ref = ".$ref;
			//echo "<br /><br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
			$i = 0;
			while ($rep = mysql_fetch_array($ret))
			{
				$Trep[$i] = $rep['id_def'];
				$i++;
			}
			
			if (isset($Trep))
			{
				$exist = true;
				/*echo "<pre>";
				print_r($Trep);
				echo "</pre>";*/
			}
		}
		
		if ($exist == false)
		{
			if (!empty($val))
			{
				//// On envoie les modif dans la table data
				$req = "INSERT INTO data (id_data, value, id_char) VALUES (NULL, ".$val.", '".$char."')";
				//echo "<br /><br />".$req."<br />";
				$ret = mysql_query($req) or die (mysql_error());
				//echo "Ok dans DATA<br />";
				
				//// On récupère l'identifiant clef primaire qui vient d'être généré
				$dat = mysql_insert_id();
				//echo "Ok pr récupérer l'id_dat : ".$dat."<br />";
			}
			
			//// On envoie les modif dans la table define
			if (empty($dat))
				$dat = "NULL";
			$req = "INSERT INTO define (id_def, validity, description, notes, code_spe, id_ref, id_data)
					VALUES (NULL, '".$vad."', ".$des.", '".$not."', '".$idSpe."', ".$ref.", ".$dat.")";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
			//echo "Ok dans DEFINE<br />";
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout spécifique à une espèce (partiel donc)
	function addDatDesc($idSpe, $des, $ref, $vad, $not, $char, $val)
	{
		if (!empty($val))
		{
			//// On envoie les modif dans la table data
			$req = "INSERT INTO data (id_data, value, id_char) VALUES (NULL, ".$val.", '".$char."')";
			//echo "<br /><br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
			//echo "Ok dans DATA<br />";
			
			//// On récupère l'identifiant clef primaire qui vient d'être généré
			$dat = mysql_insert_id();
			//echo "Ok pr récupérer l'id_dat : ".$dat."<br />";
		}
		
		//// On envoie les modif dans la table define
		if (!isset($dat) || empty($dat))
			$dat = "NULL";
		$req = "INSERT INTO define (id_def, validity, description, notes, code_spe, id_ref, id_data)
				VALUES (NULL, '".$vad."', ".$des.", '".$not."', '".$idSpe."', ".$ref.", ".$dat.")";
		//echo "<br />".$req."<br />";
		$ret = mysql_query($req) or die (mysql_error());
		//echo "Ok dans DEFINE<br />";
		return "true";
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout de genre
	function addGen($gen)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		$req = "SELECT * FROM genera WHERE name_genus = '".$gen."'";
		//echo "<br /><br />".$req."<br />";
		$ret = mysql_query($req) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Trep[$i] = $rep['name_genus'];
			$i++;
		}
		
		if (isset($Trep))
		{
			$exist = true;
			/*echo "<pre>";
			print_r($Trep);
			echo "</pre>";*/
		}
		
		if ($exist == false)
		{
			$req = "INSERT INTO genera (name_genus) VALUES ('".$gen."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour une modification de genre
	function modGen($gen)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		
		if ($exist == false)
		{
			$req = "REPLACE INTO genera (name_genus) VALUES ('".$gen."')";
			//echo "<br />".$req."<br />";
			//$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout d'espece
	function addSpe($gen, $spe, $cod)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		$req = "SELECT code_spe FROM species WHERE name_genus = '".$gen."' AND code_spe = '".$cod."'";
		//echo "<br /><br />".$req."<br />";
		$ret = mysql_query($req) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Trep[$i] = $rep['code_spe'];
			$i++;
		}
		
		if (isset($Trep))
		{
			$exist = true;
			/*echo "<pre>";
			print_r($Trep);
			echo "</pre>";*/
		}
		
		if ($exist == false)
		{
			$req = "INSERT INTO species (code_spe, specie, name_genus) VALUES ('".$cod."', '".$spe."', '".$gen."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout d'espece
	function modSpe($gen, $spe, $cod)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		if ($exist == false)
		{
			$req = "REPLACE INTO species (code_spe, specie, name_genus) VALUES ('".$cod."', '".$spe."', '".$gen."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout de characters
	function addChar($cod, $cha, $exp, $wei, $cor, $min, $max, $sta, $gen)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		$req = "SELECT * FROM characters WHERE code_char = '".$cod."' OR name_char = '".$cha."'";
		//echo "<br /><br />".$req."<br />";
		$ret = mysql_query($req) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Trep[$i] = $rep['code_char'];
			$i++;
		}
		
		if (isset($Trep))
		{
			$exist = true;
			/*echo "<pre>";
			print_r($Trep);
			echo "</pre>";*/
		}
		
		if ($exist == false)
		{
			$req = "INSERT INTO characters (id_char, code_char, name_char, explanations, weight, correction, min, max, nb_states, name_genus) 
					VALUES 
					(NULL, '".$cod."', '".$cha."', '".$exp."', '".$wei."', '".$cor."', '".$min."', '".$max."', '".$sta."', '".$gen."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout de characters
	function modChar($id, $cod, $cha, $exp, $wei, $cor, $min, $max, $sta, $gen)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		if ($exist == false)
		{
			$req = "REPLACE INTO characters (id_char, code_char, name_char, explanations, weight, correction, min, max, nb_states, name_genus) 
					VALUES 
					('".$id."', '".$cod."', '".$cha."', '".$exp."', '".$wei."', '".$cor."', '".$min."', '".$max."', '".$sta."', '".$gen."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout de characters
	function addRef($aut, $pub, $yea, $tit, $jou)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		$req = "SELECT id_ref FROM `references` WHERE title = '".$tit."' AND author = '".$aut."'";
		echo "<br /><br />".$req."<br />";
		$ret = mysql_query($req) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Trep[$i] = $rep['id_ref'];
			$i++;
		}
		
		if (isset($Trep))
		{
			$exist = true;
			/*echo "<pre>";
			print_r($Trep);
			echo "</pre>";*/
		}
		
		if ($exist == false)
		{
			$req = "INSERT INTO `references` (id_ref, author, publi_in, year, title, journal) 
					VALUES 
					(NULL, '".$aut."', '".$pub."', '".$yea."', '".$tit."', '".$jou."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout de characters
	function modRef($id, $aut, $pub, $yea, $tit, $jou)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		if ($exist == false)
		{
			$req = "REPLACE INTO `references` (id_ref, author, publi_in, year, title, journal) 
					VALUES 
					('".$id."', '".$aut."', '".$pub."', '".$yea."', '".$tit."', '".$jou."')";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui transfert les données dans la base de données pour un ajout de characters
	function modUser($id, $fname, $lname, $mail, $passwd, $instit, $city, $country, $rights)
	{
		//// On vérifie que l'entrée n'existe pas déjà
		$exist = false;
		if ($exist == false)
		{
			$req = "REPLACE INTO `users` (id_user, f_name, l_name, email, password, institution, town, country, admin) 
					VALUES 
					('".$id."', '".$fname."', '".$lname."', '".$mail."', '".$passwd."', '".$instit."', '".$city."', '".$country."', ".$rights.")";
			//echo "<br />".$req."<br />";
			$ret = mysql_query($req) or die (mysql_error());
		}
		return $exist;
	}
	
	//////// Fonction qui supprime les données relatives à toute une espèce
	function delDataSpe($spe)
	{
		$req = "SELECT id_data FROM define WHERE code_spe = '".$spe."'";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Trep[$i] = $rep['id_data'];
			$i++;
		}
		
		if (isset($Trep) && ($Trep[0] != null))
		{
			$req = "DELETE FROM data WHERE id_data IN
					(SELECT id_data FROM define WHERE code_spe = '".$spe."')";
			//echo $req."<br />";
		}
		else
		{
			$req = "DELETE FROM define WHERE code_spe = '".$spe."'";
			//echo $req."<br />";
		}
		$ret = (mysql_query($req)) or die (mysql_error());
		//echo "Ca supprime toute l'espèce ".$spe."<br />";
	}
	
	//////// Fonction qui supprime les données relatives à une description de l'espèce
	function delDataDesc($spe, $ref)
	{
		$req = "SELECT id_data FROM define WHERE code_spe = '".$spe."' AND  id_ref = ".$ref;
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Trep[$i] = $rep['id_data'];
			$i++;
		}
		/*echo "<pre>";
		print_r($Trep);
		echo "</pre>";*/
		
		if (isset($Trep) && !empty($Trep[0]))
		{
			$req = "DELETE FROM data WHERE id_data IN
					(SELECT id_data FROM define WHERE code_spe = '".$spe."' AND  id_ref = ".$ref.")";
			//echo $req."<br />";
		}
		else
		{
			$req = "DELETE FROM define WHERE code_spe = '".$spe."' AND  id_ref = ".$ref;
			//echo $req."<br />";
		}
		$ret = (mysql_query($req)) or die (mysql_error());
		//echo "Ca supprime la description de l'auteur ".$ref." de l'espèce ".$spe."<br />";
	}
	
	//////// Fonction qui supprime un character
	function delChar($id)
	{
		$req = "DELETE FROM `characters` WHERE id_char = ".$id;
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
	}
	
	//////// Fonction qui supprime un genre
	function delGen($id)
	{
		$req = "DELETE FROM `genera` WHERE name_genus = '".$id."'";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
	}
	
	//////// Fonction qui supprime une référence
	function delRef($id)
	{
		$req = "DELETE FROM `references` WHERE id_ref = ".$id;
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
	}
	
	//////// Fonction qui supprime une espèce
	function delSpe($id)
	{
		$req = "DELETE FROM `species` WHERE code_spe = '".$id."'";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
	}
	
	//////// Fonction qui supprime un utilisateur
	function delUser($id)
	{
		$req = "DELETE FROM `users` WHERE id_user = '".$id."'";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
	}
	
	//////// Fonction qui renvoie si un tableau comporte des doublons ou non
	function doublons($tab)
	{
		$freq = array_count_values($tab);
		$doublon = false;
		foreach ($freq as $val)
		{
			if ($val != 1)
			{
				$doublon = true;
				break;
			}
		}
		return $doublon;
	}
	
	//////// Fonction qui réordonne le numéro des descriptions d'une espèce
	function newDesc($spe)
	{
		$req = "SELECT count(DISTINCT description) as nbDesc FROM define WHERE code_spe = '".$spe."'";
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
		while ($rep = mysql_fetch_array($ret))
			$nbDesc = $rep['nbDesc'];
		//echo $nbDesc."<br />";
		$req = "SELECT id_def, description FROM define WHERE code_spe = '".$spe."' ORDER BY description"; // AND description = ".$i;
		//echo $req."<br />";
		$ret = (mysql_query($req)) or die (mysql_error());
		$i = 0;
		while ($rep = mysql_fetch_array($ret))
		{
			$Tid[$i]['def'] = $rep['id_def'];
			$Tid[$i]['desc'] = $rep['description'];
			$i++;
		}
		$j = 0;
		$desc = -1;
		$newDesc = -1;
		foreach ($Tid as $id)
		{
			if ($desc != $Tid[$j]['desc'])
			{
				$desc = $Tid[$j]['desc'];
				$newDesc++;
			}
			$req = "UPDATE define SET description = ".$newDesc." WHERE id_def = ".$Tid[$j]['def'];
			//echo $req."<br />";
			$ret = (mysql_query($req)) or die (mysql_error());
			$j++;
		}
		return $nbDesc;
	}
	
?>
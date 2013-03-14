<?php
	include('includes/haut.php');
	connexion_bdd();
	
	// R�cup�ration des donn�es de l'utilisateur
	$genus_name = define_genus();
	
	if(isset($_POST['user_sample'])) {
		$user_sample = get_xml_data('user_sample',$_POST['user_sample']);		
	} elseif(isset($_POST['database_sample'])) { 
		$user_sample = $_POST['database_sample'];
	} else {
		$informations = Array(true,'You must choose a sample to perform a comparison.',ROOTPATH.'/comparaison.php',5);
		require_once('informations.php');
		exit();
	}
	if(file_exists("users_files/user".$_SESSION['user_id']."_params.xml") && get_xml_data('genus') == $genus_name) {
		$params = get_xml_data('user_params');
	} else {
		$params = get_xml_data('default_params');
	}
	
	$_SESSION['results'] = array();
	
	// Cr�ation de la condition d'inclusion des esp�ces invalides
	if(!isset($_POST['validity'])) { $validity_condition = 'AND validity <> "0"';
	} else $validity_condition = '';
	
	// Appel de l'algo de comparaison selon le type de description coch�e par l'utilisateur
	if(isset($_POST['choix'])) { $_SESSION['res_type'] = $_POST['choix'];
		if ($_POST['choix'] == 'composite') {
			echo "composite algo".'</br>';
			if ($_POST['formulaVersion']=='30'){
				echo "algo composite 30".'</br>';
				compositeAlgo30($genus_name, $validity_condition, $user_sample, $params);
			}
			else {
				echo "algo composite 31".'</br>';
				compositeAlgo31($genus_name, $validity_condition, $user_sample, $params);
			}
		} elseif($_POST['choix'] == 'mixed') {
			echo "mixed algos".'</br>';
			if ($_POST['formulaVersion']=='30'){
				echo "algo composite 30".'</br>';
				compositeAlgo30($genus_name, $validity_condition, $user_sample, $params);
				echo "algo simple 30".'</br>';
				simpleAlgo30($genus_name, true, $validity_condition, $user_sample, $params);
			}
			else {
				echo "algo composite 31".'</br>';
				compositeAlgo31($genus_name, $validity_condition, $user_sample, $params);
				echo "algo simple 31".'</br>';
				simpleAlgo31($genus_name, true, $validity_condition, $user_sample, $params);
			}
			
			// Suppression des doublons (composite/originale) lorsqu'une seule description existe pour l'esp�ce
			$temp_tab = $_SESSION['results'];
			foreach($_SESSION['results'] as $key1 => $data1) {
				if(substr($key1,0,1) == '0') {
					foreach($temp_tab as $key2 => $data2) {
						if($key1 != $key2 && substr($key1,-2) == substr($key2,-2) && $data1['coef'] == $data2['coef']) {
							unset($_SESSION['results'][(string)'C'.substr($key1,-2)]);
							break;
						}
					}
				}
			}
		} else {
			if($_POST['choix'] == 'originale') {
				echo "originale algo".'</br>';
				if ($_POST['formulaVersion']=='30'){
					echo "algo simple 30".'</br>';
					simpleAlgo30($genus_name, $validity_condition, $user_sample, $params);
				}
				else {
					echo "algo simple 31".'</br>';
					simpleAlgo31($genus_name, $validity_condition, $user_sample, $params);
				}
			} else {
				if ($_POST['formulaVersion']=='30'){
					echo "algo simple 30".'</br>';
					simpleAlgo30($genus_name, $validity_condition, $user_sample, $params);
				}
				else {
					echo "algo simple 31".'</br>';
					simpleAlgo31($genus_name, $validity_condition, $user_sample, $params);
				}
			}
		}
	}

	// Suppression des descriptions qui ne correspondent pas aux choix de l'utilisateur
	foreach($_SESSION['results'] as $key => $res) {
	/*
	echo $res['details']['ql']['GENB_1']['species'].'<br />';
	echo $res['details']['ql']['GENB_2']['species'].'<br />';
	echo $res['details']['ql']['GENB_3']['species'].'<br />'.'<br />';
	*/
		if(isset($res['details']['ql'])) {
			switch($_SESSION['genus_n']) {
				case 'heli2': // Helicotylenchus s.srt [2 genital branches equally developed]
					if(!($res['details']['ql']['GENB_1']['species'] == 1 && $res['details']['ql']['GENB_2']['species'] == 0 && $res['details']['ql']['GENB_3']['species'] == 0))
						unset($_SESSION['results'][$key]);
					break;
				case 'heli3': // Rotylenchoides sensu Siddiqui & Husain, 1964 [1 anterior branch and a PUS]
					if(!($res['details']['ql']['GENB_1']['species'] == 0 && $res['details']['ql']['GENB_2']['species'] == 0 && $res['details']['ql']['GENB_3']['species'] == 1))
						unset($_SESSION['results'][$key]);
					break;
				case 'heli4': // Rotylenchoides sensu Sher, 1965 [1 ant. branch and post branch reduced in size or PUS]
					if(!($res['details']['ql']['GENB_1']['species'] == 0))
						unset($_SESSION['results'][$key]);
					break;
				default: break;
			}
		}
	}
	
	// Trie des r�sultats par ordre d�croissant des coefficients
	arsort($_SESSION['results']);
	mysql_close();
	
	// Redirection vers page d'affichage des r�sultats
	header('Location: results.php');
	
	/* Debuggage - affiche le tableau des resultats en entier
	echo '<pre>';
	print_r($_SESSION['results']);
	echo '</pre>';
	*/

/*
 * Fonction de calcul des coefficient de similarit�s pour les descriptions composites
 * Parametres :
 * 	- $validity_condition : string contenant le code conditionnel � l'exclusion des esp�ces non-valides
 *	- $user_sample : contient les donn�es de l'�chantillon de l'utilisateur
 *	- $params : contient les param�tres choisis par l'utilisateur
 */
function compositeAlgo30($genus_name, $validity_condition, $user_sample, $params){
	$use_char = false;
	
	// $user_sample 
	
	// R�cup�ration de tous les codes esp�ces pr�sents dans la bdd	
	$species = mysql_query('SELECT code_spe 	
				FROM species');
	// test affichge contenu $species -- marie 		
	/*while($speTest = mysql_fetch_array($species)){
		echo "while loop code spe:".$speTest[0].'</br>';
	}*/
	
	// Boucle qui va traiter tous les codes esp�ces un par un		
	while($spe = mysql_fetch_array($species)){
		$characters = mysql_query('SELECT code_char 	
				FROM characters');
		// Boucle qui va traiter tous les codes caract�res un par un
		while ($code_char = mysql_fetch_array($characters)){
			
			$query = mysql_query('SELECT 	avg('.$code_char[0].') AS moy, 
							correction/correction AS quantitative, 
							(max('.$code_char[0].')-min('.$code_char[0].'))/2 as Vi, 
							nb_states				
				FROM define, data, characters 
				WHERE code_spe = "'.$spe[0].'" AND data.id_data = define.id_data AND characters.code_char = "'.$code_char[0].'"
				AND name_genus = "'.$genus_name.'" '.$validity_condition.'
				ORDER BY code_spe ASC, quantitative DESC');
			
			// test affichage retour requete -- marie	
			/*while ($test = mysql_fetch_array($query)){
				echo "genus name ".$genus_name.'</br>';
				echo "validity cond ".$validity_condition.'</br>';
				echo "species ".$spe[0].'</br>';
				echo "char ".$code_char[0].'</br>';
				echo "while loop query moy ".$test[0].'</br>';
				echo "while loop query quantitative ".$test[1].'</br>';
				echo "while loop query Vi ".$test[2].'</br>';
				echo "while loop query nb_states ".$test[3].'</br>';
			}*/
					
		// Cr�ation indice selon code esp�ce
		$index = 'C'.$spe[0];
		// Initialisation de variables	
		$_SESSION['results'][$index]['coef'] = 0;
		$_SESSION['results'][$index]['nb_char_used'] = 0;
		$_SESSION['results'][$index]['nb_char_agree'] = 0;
		$weight_sum = 0;
		$temp = 0;
		
			while($row = mysql_fetch_assoc($query)){
				//echo "spe : ".$spe[0].'</br>';
				//echo "char : ".$code_char[0].'</br>';	
				$Mxi = (float)$user_sample[''.$code_char[0].'']; // r�cup�ration de la valeur du caract�re rentr�e par l'user (� partir du fichier xml)
				//echo "Mxi ".$Mxi.'</br>';
				$Msi = $row['moy']; // valeur du caract�re dans l'esp�ce (soit moyenne des valeurs de la bdd)
				//echo "Msi ".$Msi.'</br>';
			
				if($row['quantitative'] != NULL) {				
					$Ci = (float)$params[''.$code_char[0].'']['correction'];
					$Ri = (float)$params[''.$code_char[0].'']['range'];
				
					//echo "Ci ".$Ci.'</br>';
					//echo "Ri ".$Ri.'</br>';
				
					if($Mxi == "NULL") { $Wi = 0; $Mxi = 0;
					} else $Wi = (float)$params[''.$code_char[0].'']['weight'];
				
					//echo "Wi ".$Wi.'</br>';
				
					$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);
				
					if ($temp <= 0) $temp = 1;
					else $temp = 1 - $temp;
					if ($temp < 0) $temp = 0;
				
					$_SESSION['results'][$index]['coef'] += $temp*$Wi;
					$weight_sum += $Wi;
				
					// Sauvegarde des d�tails pour affichage
					$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['sample'] = $Mxi;
					$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['species'] = round($Msi,2);
					$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['score'] = round($temp,2);
					$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['weight'] = $Wi;
					$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['SW'] = round($temp*$Wi,2);
					if($Wi != 0) {
						if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
						$_SESSION['results'][$index]['nb_char_used']++;
					}
				} else { // else the character is qualitative
					// Initialisation des variables temporaires
					if (substr($code_char[0], -1) == 1 || $row['nb_states'] == 1) {
						//echo "substr ou nb_state ".substr($code_char[0], -1)." ou ".$row['nb_states'].'</br>';
					
						//$use_char = array();
						$Wi = (float)$params[''.$code_char[0].'']['weight'];
						$state_sum = 0;
					}
					/*
					if($Mxi != "NULL") $use_char[substr($row['code_char'], -1)] = 1;
					else {
						$use_char[substr($row['code_char'], -1)] = 0;
						$Mxi = 0;
					}
					*/
					$temp = 1 - (abs($Mxi-$Msi) - $row['Vi']);
					if ($temp > 1) $temp = 1;
				
					// Sauvegarde des d�tails pour affichage
					$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['sample'] = $Mxi;
					$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['species'] = round($Msi,2);
					$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['state_score'] = round($temp,2);
					$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['weight'] = $Wi;
				
					// Calcul du score final
					if (substr(''.$code_char[0].'', -1) == $row['nb_states'] || $row['nb_states'] == 1) {
						/*
						$use = true;				
						foreach($use_char as $u) {
							if($u == 0) {
								$use = false;
								break;
							}
						}
						*/
						//if($use == 0) $Wi = 0;
						$weight_sum += $Wi;
					
						$temp = ($state_sum+$temp) / $row['nb_states'];
						$_SESSION['results'][$index]['coef'] += $temp*$Wi;
					
						if($row['nb_states'] != 1) $_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['char_score'] = round($temp,2);
					
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['SW'] = round($temp*$Wi,2);
						if($Wi != 0) {
							if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
							$_SESSION['results'][$index]['nb_char_used']++;
						}
					} else {
						$state_sum += $temp;
					}
				} // fin if quantitative else qualitative
			} // fin while mysql_fetch_assoc de la requete des valeurs (moy, correction, nb_states etc...) pour un caract�re
		} // fin while mysql_fetch_array de la requete select code_char from characters
		
			if ($weight_sum != 0) {
				$taux = $_SESSION['results'][$index]['coef'] / $weight_sum;
				$_SESSION['results'][$index]['coef'] = sprintf('%.2f',round($taux,2));
			} else $_SESSION['results'][$index]['coef'] = 0;
			
		
	} // fin while mysql_fetch_array de la requete select code_spe from species	
		
}

/*
 * Fonction de calcul des coefficient de similarit�s pour les descriptions simples,
 * c'est � dire toutes les descriptions prises individuellement ou uniquement les descriptions originale
 * 
 * Parametres :
 *	- $only_original :  boolean - true: seule les descriptions originales seront prises en compte pour
 *						les calculs
 * 	- $validity_condition : string contenant le code conditionnel � l'exclusion des esp�ces non-valides
 *	- $user_sample : contient les donn�es de l'�chantillon de l'utilisateur
 *	- $params : contient les param�tres choisis par l'utilisateur
 */
function simpleAlgo30($genus_name, $only_original, $validity_condition, $user_sample, $params) {
	$use_char;
	$counter = 0;
	$previous = 'FIRST';
	
	if ($only_original){
		$original_condition = 'AND description = 0';
	} else $original_condition = '';
	
	$query = mysql_query('SELECT id_def, validity, description, code_spe, value, code_char, correction/correction AS quantitative, nb_states 
						  FROM define, data, characters 
						  WHERE data.id_data = define.id_data AND data.id_char = characters.id_char AND name_genus = "'.$genus_name.'" 
						  '.$original_condition.' '.$validity_condition.' 
						  ORDER BY code_spe ASC, description ASC, quantitative DESC, code_char ASC');
	
	while($row = mysql_fetch_assoc($query)){
		$index = $row['description'].$row['code_spe'];
		$counter++;
		if(!array_key_exists($index,$_SESSION['results'])) {
			if ($previous != 'FIRST') {
				if ($weight_sum != 0) {
					$taux = $_SESSION['results'][$previous]['coef'] / $weight_sum;
					$_SESSION['results'][$previous]['coef'] = sprintf('%.2f',$taux);
				} else $_SESSION['results'][$previous]['coef'] = 0;
			}
			$previous = $index;
			
			$_SESSION['results'][$index]['coef'] = 0;
			$_SESSION['results'][$index]['nb_char_used'] = 0;
			$_SESSION['results'][$index]['nb_char_agree'] = 0;
			$weight_sum = 0;
			$temp = 0;
		}
		
		$Mxi = (float)$user_sample[(string)$row['code_char']];
		$Msi = (float)$row['value'];
		
		if($row['quantitative'] != NULL) {
			$Ci = (float)$params[(string)$row['code_char']]['correction'];
			$Ri = (float)$params[(string)$row['code_char']]['range'];
			
			if($Mxi == "NULL") { $Wi = 0; $Mxi = 0;
			} else $Wi = (float)$params[(string)$row['code_char']]['weight'];
			
			$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);

			if ($temp <= 0) $temp = 1;
			else $temp = 1 - $temp;
			if ($temp < 0) $temp = 0;
			
			$_SESSION['results'][$index]['coef'] += $temp*$Wi;
			$weight_sum += $Wi;
			
			// Sauvegarde des d�tails pour affichage
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['sample'] = $Mxi;
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['species'] = round($Msi,2);
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['score'] = round($temp,2);
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['weight'] = $Wi;
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['SW'] = round($temp*$Wi,2);
			if($Wi != 0) {
				if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
				$_SESSION['results'][$index]['nb_char_used']++;
			}
		} else {
			if (substr($row['code_char'], -1) == 1 || $row['nb_states'] == 1) {
				$use_char = false;
				$Wi = (float)$params[(string)$row['code_char']]['weight'];
				$state_sum = 0;					
			}
			/*
			if($Mxi != "NULL") $use_char[substr($row['code_char'], -1)] = 1;
			else {
				$use_char[substr($row['code_char'], -1)] = 0;
				$Mxi = 0;
			}
			*/
			$temp = 1 - abs($Mxi-$Msi);
			if ($temp > 1) $temp = 1;
			
			// Sauvegarde des d�tails pour affichage
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['sample'] = $Mxi;
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['species'] = round($Msi,2);
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['state_score'] = round($temp,2);
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['weight'] = $Wi;
			
			if (substr($row['code_char'], -1) == $row['nb_states'] || $row['nb_states'] == 1) {
				/*
				$use = 1;				
				foreach($use_char as $u) {
					if($u == 0) {
						$use = 0;
						break;
					}
				}
				*/
				//if($use == 0) $Wi = 0;
				$weight_sum += $Wi;
			
				$temp = ($state_sum+$temp) / $row['nb_states'];
				$_SESSION['results'][$index]['coef'] += $temp*$Wi;
				
				if($row['nb_states'] != 1) $_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['char_score'] = round($temp,2);
				//else echo round($temp*$Wi,2).'<br/>';
				$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['SW'] = round($temp*$Wi,2);
				
				if($Wi != 0) {
					if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
					$_SESSION['results'][$index]['nb_char_used']++;
				}
			} else {
				$state_sum += $temp;
			}
		}
		
		if(mysql_affected_rows() == $counter) {
			if ($weight_sum != 0) {
				$taux = $_SESSION['results'][$previous]['coef'] / $weight_sum;
				$_SESSION['results'][$previous]['coef'] = sprintf('%.2f',$taux);
			} else $_SESSION['results'][$previous]['coef'] = 0;
		}
	}
}
/*
 * Fonction de calcul des coefficient de similarit�s pour les descriptions composites
 * Parametres :
 * 	- $validity_condition : string contenant le code conditionnel � l'exclusion des esp�ces non-valides
 *	- $user_sample : contient les donn�es de l'�chantillon de l'utilisateur
 *	- $params : contient les param�tres choisis par l'utilisateur
 */
function compositeAlgo31($genus_name, $validity_condition, $user_sample, $params){
	$use_char = false;	
	$species = mysql_query('SELECT code_spe 
							FROM species');
		
	while($spe = mysql_fetch_array($species)){
		$query = mysql_query('SELECT code_char, avg(value) AS moy, correction/correction AS quantitative, (max(value)-min(value))/2 as Vi, nb_states
							FROM define, data, characters 
							WHERE code_spe = "'.$spe[0].'" AND data.id_data = define.id_data AND data.id_char = characters.id_char 
									AND name_genus = "'.$genus_name.'" '.$validity_condition.' 
							GROUP BY code_char
							ORDER BY code_spe ASC, quantitative DESC, code_char ASC');
		
		$index = 'C'.$spe[0];
		
		$_SESSION['results'][$index]['coef'] = 0;
		$_SESSION['results'][$index]['nb_char_used'] = 0;
		$_SESSION['results'][$index]['nb_char_agree'] = 0;
		$weight_sum = 0;
		$temp = 0;
		
		while($row = mysql_fetch_assoc($query)){
			$Mxi = (float)$user_sample[(string)$row['code_char']];
			$Msi = $row['moy'];

			if($row['quantitative'] != NULL) {				
				$Ci = (float)$params[(string)$row['code_char']]['correction'];
				$Ri = (float)$params[(string)$row['code_char']]['range'];
				
				if($Mxi == "NULL") { $Wi = 0; $Mxi = 0;
				} else $Wi = (float)$params[(string)$row['code_char']]['weight'];
				
				$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);
				
				if ($temp <= 0) $temp = 1;
				else $temp = 1 - $temp;
				if ($temp < 0) $temp = 0;
				
				$_SESSION['results'][$index]['coef'] += $temp*$Wi;
				$weight_sum += $Wi;
				
				// Sauvegarde des d�tails pour affichage
				$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['sample'] = $Mxi;
				$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['species'] = round($Msi,2);
				$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['score'] = round($temp,2);
				$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['weight'] = $Wi;
				$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['SW'] = round($temp*$Wi,2);
				if($Wi != 0) {
					if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
					$_SESSION['results'][$index]['nb_char_used']++;
				}
			} else {
				// Initialisation des variables temporaires
				if (substr($row['code_char'], -1) == 1 || $row['nb_states'] == 1) {
					//$use_char = array();
					$Wi = (float)$params[(string)$row['code_char']]['weight'];
					$state_sum = 0;
				}
				/*
				if($Mxi != "NULL") $use_char[substr($row['code_char'], -1)] = 1;
				else {
					$use_char[substr($row['code_char'], -1)] = 0;
					$Mxi = 0;
				}
				*/
				$temp = 1 - (abs($Mxi-$Msi) - $row['Vi']);
				if ($temp > 1) $temp = 1;
				
				// Sauvegarde des d�tails pour affichage
				$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['sample'] = $Mxi;
				$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['species'] = round($Msi,2);
				$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['state_score'] = round($temp,2);
				$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['weight'] = $Wi;
				
				// Calcul du score final
				if (substr($row['code_char'], -1) == $row['nb_states'] || $row['nb_states'] == 1) {
					/*
					$use = true;				
					foreach($use_char as $u) {
						if($u == 0) {
							$use = false;
							break;
						}
					}
					*/
					//if($use == 0) $Wi = 0;
					$weight_sum += $Wi;
					
					$temp = ($state_sum+$temp) / $row['nb_states'];
					$_SESSION['results'][$index]['coef'] += $temp*$Wi;
					
					if($row['nb_states'] != 1) $_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['char_score'] = round($temp,2);
					
					$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['SW'] = round($temp*$Wi,2);
					if($Wi != 0) {
						if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
						$_SESSION['results'][$index]['nb_char_used']++;
					}
				} else {
					$state_sum += $temp;
				}
			}
		}
		
		if ($weight_sum != 0) {
			$taux = $_SESSION['results'][$index]['coef'] / $weight_sum;
			$_SESSION['results'][$index]['coef'] = sprintf('%.2f',round($taux,2));
		} else $_SESSION['results'][$index]['coef'] = 0;
	}
}

/*
 * Fonction de calcul des coefficient de similarit�s pour les descriptions simples,
 * c'est � dire toutes les descriptions prises individuellement ou uniquement les descriptions originale
 * 
 * Parametres :
 *	- $only_original :  boolean - true: seule les descriptions originales seront prises en compte pour
 *						les calculs
 * 	- $validity_condition : string contenant le code conditionnel � l'exclusion des esp�ces non-valides
 *	- $user_sample : contient les donn�es de l'�chantillon de l'utilisateur
 *	- $params : contient les param�tres choisis par l'utilisateur
 */
function simpleAlgo31($genus_name, $only_original, $validity_condition, $user_sample, $params) {
	$use_char;
	$counter = 0;
	$previous = 'FIRST';
	
	if ($only_original){
		$original_condition = 'AND description = 0';
	} else $original_condition = '';
	
	$query = mysql_query('SELECT id_def, validity, description, code_spe, value, code_char, correction/correction AS quantitative, nb_states 
						  FROM define, data, characters 
						  WHERE data.id_data = define.id_data AND data.id_char = characters.id_char AND name_genus = "'.$genus_name.'" 
						  '.$original_condition.' '.$validity_condition.' 
						  ORDER BY code_spe ASC, description ASC, quantitative DESC, code_char ASC');
	
	while($row = mysql_fetch_assoc($query)){
		$index = $row['description'].$row['code_spe'];
		$counter++;
		if(!array_key_exists($index,$_SESSION['results'])) {
			if ($previous != 'FIRST') {
				if ($weight_sum != 0) {
					$taux = $_SESSION['results'][$previous]['coef'] / $weight_sum;
					$_SESSION['results'][$previous]['coef'] = sprintf('%.2f',$taux);
				} else $_SESSION['results'][$previous]['coef'] = 0;
			}
			$previous = $index;
			
			$_SESSION['results'][$index]['coef'] = 0;
			$_SESSION['results'][$index]['nb_char_used'] = 0;
			$_SESSION['results'][$index]['nb_char_agree'] = 0;
			$weight_sum = 0;
			$temp = 0;
		}
		
		$Mxi = (float)$user_sample[(string)$row['code_char']];
		$Msi = (float)$row['value'];
		
		if($row['quantitative'] != NULL) {
			$Ci = (float)$params[(string)$row['code_char']]['correction'];
			$Ri = (float)$params[(string)$row['code_char']]['range'];
			
			if($Mxi == "NULL") { $Wi = 0; $Mxi = 0;
			} else $Wi = (float)$params[(string)$row['code_char']]['weight'];
			
			$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);

			if ($temp <= 0) $temp = 1;
			else $temp = 1 - $temp;
			if ($temp < 0) $temp = 0;
			
			$_SESSION['results'][$index]['coef'] += $temp*$Wi;
			$weight_sum += $Wi;
			
			// Sauvegarde des d�tails pour affichage
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['sample'] = $Mxi;
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['species'] = round($Msi,2);
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['score'] = round($temp,2);
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['weight'] = $Wi;
			$_SESSION['results'][$index]['details']['qt'][(string)$row['code_char']]['SW'] = round($temp*$Wi,2);
			if($Wi != 0) {
				if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
				$_SESSION['results'][$index]['nb_char_used']++;
			}
		} else {
			if (substr($row['code_char'], -1) == 1 || $row['nb_states'] == 1) {
				$use_char = false;
				$Wi = (float)$params[(string)$row['code_char']]['weight'];
				$state_sum = 0;					
			}
			/*
			if($Mxi != "NULL") $use_char[substr($row['code_char'], -1)] = 1;
			else {
				$use_char[substr($row['code_char'], -1)] = 0;
				$Mxi = 0;
			}
			*/
			$temp = 1 - abs($Mxi-$Msi);
			if ($temp > 1) $temp = 1;
			
			// Sauvegarde des d�tails pour affichage
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['sample'] = $Mxi;
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['species'] = round($Msi,2);
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['state_score'] = round($temp,2);
			$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['weight'] = $Wi;
			
			if (substr($row['code_char'], -1) == $row['nb_states'] || $row['nb_states'] == 1) {
				/*
				$use = 1;				
				foreach($use_char as $u) {
					if($u == 0) {
						$use = 0;
						break;
					}
				}
				*/
				//if($use == 0) $Wi = 0;
				$weight_sum += $Wi;
			
				$temp = ($state_sum+$temp) / $row['nb_states'];
				$_SESSION['results'][$index]['coef'] += $temp*$Wi;
				
				if($row['nb_states'] != 1) $_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['char_score'] = round($temp,2);
				//else echo round($temp*$Wi,2).'<br/>';
				$_SESSION['results'][$index]['details']['ql'][(string)$row['code_char']]['SW'] = round($temp*$Wi,2);
				
				if($Wi != 0) {
					if($temp == 1) $_SESSION['results'][$index]['nb_char_agree']++;
					$_SESSION['results'][$index]['nb_char_used']++;
				}
			} else {
				$state_sum += $temp;
			}
		}
		
		if(mysql_affected_rows() == $counter) {
			if ($weight_sum != 0) {
				$taux = $_SESSION['results'][$previous]['coef'] / $weight_sum;
				$_SESSION['results'][$previous]['coef'] = sprintf('%.2f',$taux);
			} else $_SESSION['results'][$previous]['coef'] = 0;
		}
	}
}
?>

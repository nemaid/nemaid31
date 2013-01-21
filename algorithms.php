<?php
	include('includes/haut.php');
	connexion_bdd();
	
	// Récupération des données de l'utilisateur
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
	
	// Création de la condition d'inclusion des espèces invalides
	if(!isset($_POST['validity'])) { $validity_condition = 'AND validity <> "0"';
	} else $validity_condition = '';
	
	// 
	if(isset($_POST['choix'])) { $_SESSION['res_type'] = $_POST['choix'];
		if ($_POST['choix'] == 'composite') {
			compositeAlgo($genus_name, $validity_condition, $user_sample, $params);
		} elseif($_POST['choix'] == 'mixed') {
			compositeAlgo($genus_name, $validity_condition, $user_sample, $params);
			simpleAlgo($genus_name, true,$validity_condition, $user_sample, $params);
			
			// Suppression des doublons (composite/originale) lorsqu'une seule description exsite pour l'espèce
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
				simpleAlgo($genus_name, true, $validity_condition, $user_sample, $params);
			} else {
				simpleAlgo($genus_name, false, $validity_condition, $user_sample, $params);
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
	
	// Trie des résultats par ordre décroissant des coefficients
	arsort($_SESSION['results']);
	mysql_close();
	
	// Redirection vers page d'affichage des résultats
	header('Location: results.php');
	
	/* Debuggage - affiche le tableau des resultats en entier
	echo '<pre>';
	print_r($_SESSION['results']);
	echo '</pre>';
	*/

/*
 * Fonction de calcul des coefficient de similarités pour les descriptions composites
 * Parametres :
 * 	- $validity_condition : string contenant le code conditionnel à l'exclusion des espèces non-valides
 *	- $user_sample : contient les données de l'échantillon de l'utilisateur
 *	- $params : contient les paramètres choisis par l'utilisateur
 */
function compositeAlgo($genus_name, $validity_condition, $user_sample, $params){
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
				
				// Sauvegarde des détails pour affichage
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
				
				// Sauvegarde des détails pour affichage
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
 * Fonction de calcul des coefficient de similarités pour les descriptions simples,
 * c'est à dire toutes les descriptions prises individuellement ou uniquement les descriptions originale
 * 
 * Parametres :
 *	- $only_original :  boolean - true: seule les descriptions originales seront prises en compte pour
 *						les calculs
 * 	- $validity_condition : string contenant le code conditionnel à l'exclusion des espèces non-valides
 *	- $user_sample : contient les données de l'échantillon de l'utilisateur
 *	- $params : contient les paramètres choisis par l'utilisateur
 */
function simpleAlgo($genus_name, $only_original, $validity_condition, $user_sample, $params) {
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
			
			// Sauvegarde des détails pour affichage
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
			
			// Sauvegarde des détails pour affichage
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

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
	
	if(file_exists("users_files/user".$_SESSION['user_id']."_params_31.xml") && get_xml_data('genus') == $genus_name) {
		$params31 = get_xml_data('user_params');
	} else {
		$params31 = get_xml_data('default_params_31');
	}

	$_SESSION['results'] = array();
	
	// Création de la condition d'inclusion des espèces invalides
	if(!isset($_POST['validity'])) { $validity_condition = 'AND validity <> "0"';
	} else $validity_condition = '';
	
	// Appel de l'algo de comparaison selon le type de description cochée par l'utilisateur
	if(isset($_POST['choix']) && isset($_POST['choixAlgoVersion'])) { 
		$_SESSION['res_type'] = $_POST['choix'];
		echo "choix".$_POST['choix'].'</br>';
		echo "choix algo".$_POST['choixAlgoVersion'].'</br>';
		$_SESSION['algoVersion'] = $_POST['choixAlgoVersion'];
		echo "res type".$_SESSION['res_type'].'</br>';
		echo "algo vers".$_SESSION['algoVersion'].'</br>';
		if ($_POST['choix'] == 'composite') {			
			if ($_POST['choixAlgoVersion']=='3.0'){
				echo "compositeAlgo30()".'</br>';
				compositeAlgo30($genus_name, $validity_condition, $user_sample, $params);
			}
			else {
				echo "compositeAlgo31()".'</br>';
				compositeAlgo31($genus_name, $validity_condition, $user_sample, $params31);
			}
		} elseif($_POST['choix'] == 'mixed') {
			if ($_POST['choixAlgoVersion']=='3.0'){
				echo "mixed compositeAlgo30() & simpleAlgo30()".'</br>';
				compositeAlgo30($genus_name, $validity_condition, $user_sample, $params);	
				simpleAlgo30($genus_name, true, $validity_condition, $user_sample, $params);
			}
			else {
				echo "mixed compositeAlgo31() & simpleAlgo31()".'</br>';
				compositeAlgo31($genus_name, $validity_condition, $user_sample, $params31);
				simpleAlgo31($genus_name, true, $validity_condition, $user_sample, $params31);
			}
			
			// Suppression des doublons (composite/originale) lorsqu'une seule description existe pour l'espèce
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
		} else { // $_POST['choix'] == 'all ' ou 'originale'
			if($_POST['choix'] == 'originale') {
				if ($_POST['choixAlgoVersion']=='3.0'){
					echo "simpleAlgo30() originale only".'</br>';
					simpleAlgo30($genus_name, true, $validity_condition, $user_sample, $params);
				}
				else {
					echo "simpleAlgo31() originale only".'</br>';
					simpleAlgo31($genus_name, true, $validity_condition, $user_sample, $params31);
				}
			} else { // $_POST['choix'] == 'all'
				if ($_POST['choixAlgoVersion']=='3.0'){
					echo "simpleAlgo30() originale and later (no composite)".'</br>';
					simpleAlgo30($genus_name, false, $validity_condition, $user_sample, $params);
				}
				else {
					echo "simpleAlgo31() originale and later (no composite)".'</br>';
					simpleAlgo31($genus_name, false, $validity_condition, $user_sample, $params31);
				}
			}
		}
	}
	/*foreach($_SESSION['results'] as $key1 => $data1) {
				echo "key : ".$key1."data : ".$data1['coef'].'</br>';
				}*/
	
	// Trie des résultats par ordre décroissant des coefficients
	arsort($_SESSION['results']);
	mysql_close();
	/*echo "=====================================================".'</br>';
	foreach($_SESSION['results'] as $key1 => $data1) {
				echo "key : ".$key1."data : ".$data1['coef'].'</br>';
				}*/
	
	// Redirection vers page d'affichage des résultats
	header('Location: results.php');

/*
 * Fonction de calcul des coefficient de similarités pour les descriptions composites
 * Parametres :
 * 	- $validity_condition : string contenant le code conditionnel à l'exclusion des espèces non-valides
 *	- $user_sample : contient les données de l'échantillon de l'utilisateur
 *	- $params : contient les paramètres choisis par l'utilisateur
 */
function compositeAlgo30($genus_name, $validity_condition, $user_sample, $params){	
	// Récupération de tous les codes espèces présents dans la bdd	
	$species = mysql_query('SELECT code_spe 	
				FROM species');
				
	// test affichge contenu $species -- marie 		
	/*while($speTest = mysql_fetch_array($species)){
		echo "while loop code spe:".$speTest[0].'</br>';
	}*/
	
	// Boucle qui va traiter toutes les espèces une par une	
	while($spe = mysql_fetch_array($species)){
		// Création indice selon le code espèce (<=> "Ccode_spe") pour stockage des résultats dans le tableau $results 
		$index = 'spe'.$spe[0];
			
		// Initialisation de variables	
		$_SESSION['results'][$index]['coef'] = 0; // somme des Si*Wi ($temp*Wi calculé pour chaque caractère)
		$_SESSION['results'][$index]['nb_char_used'] = 0;
		$_SESSION['results'][$index]['nb_char_agree'] = 0;
		$weight_sum = 0; // somme des Wi
		$temp = 0; // coefficient de similarité calculé (avant multiplication par le poids (Wi))
		
		$characters = mysql_query('SELECT code_char 	
				FROM characters WHERE name_genus="'.$genus_name.'"');
		// Boucle qui va traiter tous les caractères un par un (donc pour chaque espèce)
		while ($code_char = mysql_fetch_array($characters)){
									
			$query = mysql_query('	SELECT 	avg('.$code_char[0].') AS moy, 
							correction/correction AS quantitative, 
							(max('.$code_char[0].')-min('.$code_char[0].'))/2 as Vi, 
							nb_states				
						FROM define, data, characters 
						WHERE code_spe = "'.$spe[0].'" 
							AND data.id_data = define.id_data 
							AND characters.code_char = "'.$code_char[0].'"
							AND name_genus = "'.$genus_name.'"
							'.$validity_condition.'
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
		
			while($row = mysql_fetch_assoc($query)){
				//echo "spe : ".$spe[0].'</br>';
				//echo "char : ".$code_char[0].'</br>';	
				$Mxi = (float)$user_sample[''.$code_char[0].'']; // récupération de la valeur du caractère rentrée par l'user (à partir du fichier xml généré suite à l'enregistrement des données entrées dans le formulaire)
				//echo "Mxi ".$Mxi.'</br>';
				if ($row['moy'] != NULL){ // if the query return NULL the species could have no description or only invalid description and these are not included
					$Msi = $row['moy']; // valeur connue du caractère dans l'espèce (<=> moyenne des valeurs présentes dans la bdd)
					//echo "Msi ".$Msi.'</br>';
		
					if($row['quantitative'] != NULL) {				
						$Ci = (float)$params[''.$code_char[0].'']['correction'];
						$Ri = (float)$params[''.$code_char[0].'']['range'];				
						//echo "Ci ".$Ci.'</br>';
						//echo "Ri ".$Ri.'</br>';
			
						if($Mxi == "NULL") { // if the character is missing the corresponding row in the xml file contain the "NULL" string so the value is set to 0
							$Wi = 0;
							$Mxi = 0; 
						} else {
							$Wi = (float)$params[''.$code_char[0].'']['weight'];					
						}
						//echo "Wi ".$Wi.'</br>';
			
						$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);
						//echo "temp = ".$temp.'</br>';
						if ($temp <= 0) { // if the character was missing, the value was setted to 0 and the similarity score calculated ($temp) is negative or equal to zero so the value is set to 1 to neutralize it
							$temp = 1; 
						} else {
							$temp = 1 - $temp;
						}
				
						if ($temp < 0) {
							$temp = 0;
						}
			
						$_SESSION['results'][$index]['coef'] += $temp*$Wi;
						$weight_sum += $Wi;
			
						// Sauvegarde des détails pour affichage des résultats
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['sample'] = $Mxi;
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['species'] = round($Msi,2);
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['score'] = round($temp,2);
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['weight'] = $Wi;
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['SW'] = round($temp*$Wi,2);
				
						if($Wi != 0) {
							if($temp == 1) {
								$_SESSION['results'][$index]['nb_char_agree']++;
							}
							$_SESSION['results'][$index]['nb_char_used']++;						
						}
				
					} else { // else the character is qualitative	
						//echo "the char is qual".'</br>';				
						if (substr($code_char[0], -1) == 1 || $row['nb_states'] == 1) { // Si on est en train de traiter le premier état du caractère ou si celui-ci est un caractère à un état (présence/absence) alors on récupére le Weight et on initilise le $state_sum 
							//echo "substr ou nb_state ".substr($code_char[0], -1)." ou ".$row['nb_states'].'</br>';
							$Wi = (float)$params[''.$code_char[0].'']['weight'];
							$state_sum = 0;
						}
									
						$temp = 1 - (abs($Mxi-$Msi) - $row['Vi']);
						if ($temp > 1) {
							$temp = 1;
						}
			
						// Sauvegarde des détails pour affichage des résultats
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['sample'] = $Mxi;
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['species'] = round($Msi,2);
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['state_score'] = round($temp,2);
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['weight'] = $Wi;
			
						// Calcul du score final pour le caractère qualitatif							
						if (substr(''.$code_char[0].'', -1) == $row['nb_states'] || $row['nb_states'] == 1) { // si on est rendu au dernier état du caractère ou si c'est un caractère à un seul état alors on calcul le coef de similarité final				
							$weight_sum += $Wi;
				
							$temp = ($state_sum+$temp) / $row['nb_states'];
							$_SESSION['results'][$index]['coef'] += $temp*$Wi;
				
							if($row['nb_states'] != 1) {
								$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['char_score'] = round($temp,2);					
							}
					
							$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['SW'] = round($temp*$Wi,2);
					
							if($Wi != 0) {
								if($temp == 1) {
									$_SESSION['results'][$index]['nb_char_agree']++;
								}
								$_SESSION['results'][$index]['nb_char_used']++;
							}
					
						} else { // sinon on fait la somme du coef. de l'état en cours avec ceux des états précédents
							$state_sum += $temp;
						}
					} // fin if quantitative else qualitative
				} // fin if Msi != NULL
			} // fin while mysql_fetch_assoc de la requete des valeurs (moy, correction, nb_states etc...) pour un caractère
		} // fin while mysql_fetch_array de la requete select code_char from characters
		
		// Calcul et stockage du score final de similarité
		if ($weight_sum != 0) {
			$taux = $_SESSION['results'][$index]['coef'] / $weight_sum;
			$_SESSION['results'][$index]['coef'] = sprintf('%.2f',round($taux,2));			
		} else {
			$_SESSION['results'][$index]['coef'] = 0;
		}			
		
	} // fin while mysql_fetch_array de la requete select code_spe from species		
} // fin compositeAlgo30

/*
 * Fonction de calcul des coefficient de similarités pour les descriptions composites en utilisant la formule 3.1
 * c'est à dire sans différence de calcul entre le caractère quantitatif et qualitatif
 * Les requetes utilisent la table characters31 identiques à characters avec ajout de valeurs dans les colonnes correction, min et max
 * 
 * Parametres :
 * 	- $validity_condition : string contenant le code conditionnel à l'exclusion des espèces non-valides
 *	- $user_sample : contient les données de l'échantillon de l'utilisateur
 *	- $params : contient les paramètres choisis par l'utilisateur
 */
function compositeAlgo31($genus_name, $validity_condition, $user_sample, $params31){
	// Récupération de tous les codes espèces présents dans la bdd	
	$species = mysql_query('SELECT code_spe 	
				FROM species');
				
	// test affichge contenu $species -- marie 		
	/*while($speTest = mysql_fetch_array($species)){
		echo "while loop code spe:".$speTest[0].'</br>';
	}*/
	
	// Boucle qui va traiter toutes les espèces une par une	
	while($spe = mysql_fetch_array($species)){
		// Création indice selon le code espèce (<=> "Ccode_spe") pour stockage des résultats dans le tableau $results 
		$index = 'spe'.$spe[0];
			
		// Initialisation de variables	
		$_SESSION['results'][$index]['coef'] = 0; // somme des Si*Wi ($temp*Wi calculé pour chaque caractère)
		$_SESSION['results'][$index]['nb_char_used'] = 0;
		$_SESSION['results'][$index]['nb_char_agree'] = 0;
		$weight_sum = 0; // somme des Wi
		$temp = 0; // coefficient de similarité calculé (avant multiplication par le poids (Wi))
		
		$characters = mysql_query('SELECT code_char 	
				FROM characters31 WHERE name_genus="'.$genus_name.'"');
		// Boucle qui va traiter tous les caractères un par un (donc pour chaque espèce)
		while ($code_char = mysql_fetch_array($characters)){
			
			$query = mysql_query('	SELECT 	avg('.$code_char[0].') AS moy,
							nb_states				
						FROM define, data, characters31
						WHERE code_spe = "'.$spe[0].'" 
							AND data.id_data = define.id_data 
							AND characters31.code_char = "'.$code_char[0].'"
							AND name_genus = "'.$genus_name.'"
							'.$validity_condition.'
						ORDER BY code_spe ASC');
			
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
		
			while($row = mysql_fetch_assoc($query)){
				//echo "spe : ".$spe[0].'</br>';
				//echo "char : ".$code_char[0].'</br>';	
				$Mxi = (float)$user_sample[''.$code_char[0].'']; // récupération de la valeur du caractère rentrée par l'user (à partir du fichier xml généré suite à l'enregistrement des données entrées dans le formulaire)
				//echo "Mxi ".$Mxi.'</br>';
				if ($row['moy'] != NULL) { // if the query return NULL the species could have no description or only invalid description and these are not included
					$Msi = $row['moy']; // valeur connue du caractère dans l'espèce (<=> moyenne des valeurs présentes dans la bdd)
					//echo "Msi ".$Msi.'</br>';
								
					$Ci = (float)$params31[''.$code_char[0].'']['correction'];
					$Ri = (float)$params31[''.$code_char[0].'']['range'];				
					//echo "Ci ".$Ci.'</br>';
					//echo "Ri ".$Ri.'</br>';
				
					if($Mxi == "NULL") { // if the character is missing the corresponding row in the xml file contain the "NULL" string so the value is set to 0
						$Wi = 0;
						$Mxi = 0; 
					} else {
						$Wi = (float)$params31[''.$code_char[0].'']['weight'];					
					}
					//echo "Wi ".$Wi.'</br>';
			
					$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);

					if ($temp <= 0) { // if the character was missing, the value was setted to 0 and the similarity score calculated ($temp) is negative or equal to zero so the value is set to 1 to neutralize it
						$temp = 1; 
					} else {
						$temp = 1 - $temp;
					}
				
					if ($temp < 0) {
						$temp = 0;
					}
				
					if ($row['nb_states'] == 1) { // si le caractère traité a un seul état (càd si c'est un caractère quantitatif ou qualitatif à un état	
						$_SESSION['results'][$index]['coef'] += $temp*$Wi; // ajout de son score*Wi à la somme des Si*Wi
						$weight_sum += $Wi; // ajout de son Wi à la somme des Wi				
						// Sauvegarde des détails pour affichage des résultats
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['sample'] = $Mxi;
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['species'] = round($Msi,2);
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['score'] = round($temp,2);
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['weight'] = $Wi;
						$_SESSION['results'][$index]['details']['qt'][''.$code_char[0].'']['SW'] = round($temp*$Wi,2);
					
						if($Wi != 0) {
							if($temp == 1) {
								$_SESSION['results'][$index]['nb_char_agree']++;
							}
							$_SESSION['results'][$index]['nb_char_used']++;						
						}
					}else { if (substr($code_char[0], -1) == 1) { // si c'est l'état 1 du caractère 
							$curr_state_sum = 0;
						}
				
						// Sauvegarde des détails pour affichage des résultats
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['sample'] = $Mxi;
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['species'] = round($Msi,2);
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['state_score'] = round($temp,2);
						$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['weight'] = $Wi;
					
						$curr_state_sum += $temp;
												
						if (substr($code_char[0], -1) == $row['nb_states']) { // si c'est le dernier état du caractère
						
							$temp = $curr_state_sum / $row['nb_states'];	// moyenne des scores des différents états		
							$_SESSION['results'][$index]['coef'] += $temp*$Wi; // ajout du score du caractère à la somme des Si*Wi
							$weight_sum += $Wi; // ajout du Wi à la somme des Wi
					
							// stockage du score du caractère pour affichage des résultats
							$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['char_score'] = round($temp,2);
							$_SESSION['results'][$index]['details']['ql'][''.$code_char[0].'']['SW'] = round($temp*$Wi,2);
					
							if($Wi != 0) {
								if($temp == 1) {
									$_SESSION['results'][$index]['nb_char_agree']++;
								}
								$_SESSION['results'][$index]['nb_char_used']++;
							}						
						}
					} // fin else le caractère est un qualitatif à 2 états ou plus
				} // fin if Msi != NULL
			} // fin while mysql_fetch_assoc de la requete des valeurs (moy, correction, nb_states etc...) pour un caractère
		} // fin while mysql_fetch_array de la requete select code_char from characters
		
		// Calcul et stockage du score final de similarité
		if ($weight_sum != 0) {
			$taux = $_SESSION['results'][$index]['coef'] / $weight_sum;
			$_SESSION['results'][$index]['coef'] = sprintf('%.2f',round($taux,2));
		} else {
			$_SESSION['results'][$index]['coef'] = 0;
		}			
		
	} // fin while mysql_fetch_array de la requete select code_spe from species		
} // fin compositeAlgo31	
	
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
function simpleAlgo30($genus_name, $only_original, $validity_condition, $user_sample, $params) {
	
	if ($only_original){
		$original_condition = 'AND pop_type = "T"';
	} else {
		$original_condition = '';
	}
	
	// Initialisation et remplissage des tableaux :
	// - 1 dimension qui va contenir la liste des codes caractères du genre traité
	$char_code_array = array();
	// - 2 dimensions qui vont contenir les rapports correction/correction et le nombre d'état de chaque caractère
	$char_corr_array = array();
	$char_nb_state_array = array();
	
	$query_correction_nbStates = mysql_query('	SELECT 	code_char, 
								correction/correction AS quantitative, 
								nb_states
							FROM characters WHERE name_genus="'.$genus_name.'"');
													
	while ($corr_nbState = mysql_fetch_array($query_correction_nbStates)){
		$char_codes_array[] = $corr_nbState['code_char'];
		$char_corr_array[$corr_nbState['code_char']] = $corr_nbState['quantitative'];
		$char_nbStates_array[$corr_nbState['code_char']] = $corr_nbState['nb_states'];
	}	
	// Affichage des trois tableaux (codes des caractères, correction/correction et nbre d'états) pour vérif
	/*foreach ($char_codes_array as $char_name){
		echo "char name is ".$char_name.'</br>';
	}
	foreach ($char_corr_array as $char => $corr){
		echo "char is ".$char." corr = ".$corr.'</br>';
	}
	foreach ($char_nbStates_array as $char => $nbStates){
		echo "char is ".$char." nb states = ".$nbStates.'</br>';
	}*/
					
	$string_query_all_code_char='';	
	// concaténation de tous les codes caractères (separes par des virgule) pour le SELECT de tous les caractères d'une description
	foreach ($char_codes_array as $char_name){
		$string_query_all_code_char=$string_query_all_code_char.$char_name.",";
	}
	//echo $string_query_all_code_char.'</br>';

	$query = mysql_query('	SELECT 	id_def, 
					validity, 
					pop_type,
					'.$string_query_all_code_char.'
					code_spe					
				FROM define, data, characters
				WHERE data.id_data = define.id_data
					AND name_genus = "'.$genus_name.'"
					'.$original_condition.'
					'.$validity_condition.'
				GROUP BY id_def
				ORDER BY code_spe ASC');

	// pour chaque description présente dans la bdd
	while($row = mysql_fetch_assoc($query)){
		
		$index = $row['pop_type'].'def'.$row['id_def'].'spe'.$row['code_spe']; // Création indice selon l'id_def et le code espèce pour stockage des résultats dans le tableau $results
			
		// Initialisation de variables	
		$_SESSION['results'][$index]['coef'] = 0; // somme des Si*Wi ($temp*Wi calculé pour chaque caractère)
		$_SESSION['results'][$index]['nb_char_used'] = 0;
		$_SESSION['results'][$index]['nb_char_agree'] = 0;
		$weight_sum = 0; // somme des Wi
		$temp = 0; // coefficient de similarité calculé (avant multiplication par le poids (Wi))
		
		// pour chaque caractère de la description traitee
		foreach ($char_codes_array as $char_name){
		
			$Mxi = (float)$user_sample[''.$char_name.'']; // récupération de la valeur du caractère rentrée par l'user (à partir du fichier xml généré suite à l'enregistrement des données entrées dans le formulaire)
			
			$Msi = (float)$row[''.$char_name.'']; // valeur connue du caractère dans l'espèce (<=> moyenne des valeurs présentes dans la bdd)	
			if($char_corr_array[''.$char_name.''] != NULL) { // if the character is quantitative
				$Ci = (float)$params[''.$char_name.'']['correction'];
				$Ri = (float)$params[''.$char_name.'']['range'];
		
				if($Mxi == "NULL") { // if the character is missing the corresponding row in the xml file contain the "NULL" string so the value is set to 0
					$Wi = 0;
					$Mxi = 0;
				} else {
					$Wi = (float)$params[''.$char_name.'']['weight'];
				}
		
				$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);

				if ($temp <= 0) { // if the character was missing, the value was setted to 0 and the similarity score calculated ($temp) is negative or equal to zero so the value is set to 1 to neutralize it
					$temp = 1;
				} else {
					$temp = 1 - $temp;
				}
		
				if ($temp < 0) {
					$temp = 0;
				}
		
				$_SESSION['results'][$index]['coef'] += $temp*$Wi;
				$weight_sum += $Wi;
		
				// Sauvegarde des détails pour affichage des résultats
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['sample'] = $Mxi;
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['species'] = round($Msi,2);
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['score'] = round($temp,2);
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['weight'] = $Wi;
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['SW'] = round($temp*$Wi,2);
		
				if($Wi != 0) {
					if($temp == 1) {
						$_SESSION['results'][$index]['nb_char_agree']++;
					}
					$_SESSION['results'][$index]['nb_char_used']++;
				}
		
			} else { // else the character is qualitatif
				if (substr(''.$char_name.'', -1) == 1 || $char_nbStates_array[''.$char_name.''] == 1) {
					$Wi = (float)$params[''.$char_name.'']['weight'];
					$state_sum = 0;					
				}
		
				$temp = 1 - abs($Mxi-$Msi);
				if ($temp > 1) {
					$temp = 1;
				}
		
				// Sauvegarde des détails pour affichage des résultats
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['sample'] = $Mxi;
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['species'] = round($Msi,2);
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['state_score'] = round($temp,2);
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['weight'] = $Wi;
		
				if (substr(''.$char_name.'', -1) == $char_nbStates_array[''.$char_name.''] || $char_nbStates_array[''.$char_name.''] == 1) {
					$weight_sum += $Wi;
		
					$temp = ($state_sum+$temp) / $char_nbStates_array[''.$char_name.''];
					$_SESSION['results'][$index]['coef'] += $temp*$Wi;
			
					if($row['nb_states'] != 1) {
						$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['char_score'] = round($temp,2);
					}
					$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['SW'] = round($temp*$Wi,2);
			
					if($Wi != 0) {
						if($temp == 1) {
							$_SESSION['results'][$index]['nb_char_agree']++;
						}
						$_SESSION['results'][$index]['nb_char_used']++;
					}			
				} else {
					$state_sum += $temp;
				}
			} // fin if quantitatif else qualitatif
		} // fin foreach caractère
			
		// Calcul et stockage du score final de similarité	
		if ($weight_sum != 0) {
			$taux = $_SESSION['results'][$index]['coef'] / $weight_sum;
			if ($taux >=0){ // negative coefficient means that the specie has only one data and it invalid or the specie has no data
				$_SESSION['results'][$index]['coef'] = sprintf('%.2f',$taux);
			}			
		} else {
			$_SESSION['results'][$index]['coef'] = 0;
		}
	
	}// fin loop while mysql_fetch_array de la requete traitant toutes les descriptions de la bdd une par une	
}// fin simpleAlgo30

/*
 * Fonction de calcul des coefficient de similarités pour les descriptions simples,
 * c'est à dire toutes les descriptions prises individuellement ou uniquement les descriptions originale
 * en utilisant la formule 3.1
 * c'est à dire sans différence de calcul entre le caractère quantitatif et qualitatif
 * Les requetes utilisent la table characters31 identiques à characters avec ajout de valeurs dans les colonnes correction, min et max
 *
 * 
 * Parametres :
 *	- $only_original :  boolean - true: seule les descriptions originales seront prises en compte pour
 *						les calculs
 * 	- $validity_condition : string contenant le code conditionnel à l'exclusion des espèces non-valides
 *	- $user_sample : contient les données de l'échantillon de l'utilisateur
 *	- $params : contient les paramètres choisis par l'utilisateur
 */
function simpleAlgo31($genus_name, $only_original, $validity_condition, $user_sample, $params31) {

	if ($only_original){
		$original_condition = 'AND pop_type = "T"';
	} else {
		$original_condition = '';
	}
	
	// Initialisation et remplissage des tableaux :
	// - 1 dimension qui va contenir la liste des codes caractères du genre traité
	$char_code_array = array();
	// - 2 dimensions qui vont contenir les rapports correction/correction et le nombre d'état de chaque caractère
	$char_corr_array = array();
	$char_nb_state_array = array();
	
	$query_correction_nbStates = mysql_query('	SELECT 	code_char, 
								correction/correction AS quantitative, 
								nb_states
							FROM characters31 WHERE name_genus="'.$genus_name.'"');
													
	while ($corr_nbState = mysql_fetch_array($query_correction_nbStates)){
		$char_codes_array[] = $corr_nbState['code_char'];
		$char_corr_array[$corr_nbState['code_char']] = $corr_nbState['quantitative'];
		$char_nbStates_array[$corr_nbState['code_char']] = $corr_nbState['nb_states'];
	}	
	// Affichage des trois tableaux (codes des caractères, correction/correction et nbre d'états) pour vérif
	/*foreach ($char_codes_array as $char_name){
		echo "char name is ".$char_name.'</br>';
	}
	foreach ($char_corr_array as $char => $corr){
		echo "char is ".$char." corr = ".$corr.'</br>';
	}
	foreach ($char_nbStates_array as $char => $nbStates){
		echo "char is ".$char." nb states = ".$nbStates.'</br>';
	}*/
					
	$string_query_all_code_char='';	
	// concaténation de tous les codes caractères (separes par des virgule) pour le SELECT de tous les caractères d'une description
	foreach ($char_codes_array as $char_name){
		$string_query_all_code_char=$string_query_all_code_char.$char_name.",";
	}
	//echo $string_query_all_code_char.'</br>';

	$query = mysql_query('	SELECT 	id_def, 
					validity, 
					pop_type,
					'.$string_query_all_code_char.'
					code_spe					
				FROM define, data, characters31
				WHERE data.id_data = define.id_data
					AND name_genus = "'.$genus_name.'"
					'.$original_condition.'
					'.$validity_condition.'
				GROUP BY id_def
				ORDER BY code_spe ASC');

	// pour chaque description présente dans la bdd
	while($row = mysql_fetch_assoc($query)){
		
		$index = $row['pop_type'].'def'.$row['id_def'].'spe'.$row['code_spe']; // Création indice selon l'id_def et le code espèce pour stockage des résultats dans le tableau $results
			
		// Initialisation de variables	
		$_SESSION['results'][$index]['coef'] = 0; // somme des Si*Wi ($temp*Wi calculé pour chaque caractère)
		$_SESSION['results'][$index]['nb_char_used'] = 0;
		$_SESSION['results'][$index]['nb_char_agree'] = 0;
		$weight_sum = 0; // somme des Wi
		$temp = 0; // coefficient de similarité calculé (avant multiplication par le poids (Wi))
		
		// pour chaque caractère de la description traitee
		foreach ($char_codes_array as $char_name){
		
			$Mxi = (float)$user_sample[''.$char_name.'']; // récupération de la valeur du caractère rentrée par l'user (à partir du fichier xml généré suite à l'enregistrement des données entrées dans le formulaire)
			
			$Msi = (float)$row[''.$char_name.'']; // valeur connue du caractère dans l'espèce (<=> moyenne des valeurs présentes dans la bdd)	
			$Ci = (float)$params31[''.$char_name.'']['correction'];
			$Ri = (float)$params31[''.$char_name.'']['range'];
		
			if($Mxi == "NULL") { // if the character is missing the corresponding row in the xml file contain the "NULL" string so the value is set to 0
				$Wi = 0;
				$Mxi = 0;
			} else {
				$Wi = (float)$params31[''.$char_name.'']['weight'];
			}
			
			$temp = (abs($Mxi-$Msi) - $Ci) / ($Ri - $Ci);

			if ($temp <= 0) { // if the character was missing, the value was setted to 0 and the similarity score calculated ($temp) is negative or equal to zero so the value is set to 1 to neutralize it
				$temp = 1;
			} else {
				$temp = 1 - $temp;
			}
		
			if ($temp < 0) {
				$temp = 0;
			}
			
			if ($char_nbStates_array[''.$char_name.''] == 1) { // si le caractère traité a un seul état (càd si c'est un caractère quantitatif ou qualitatif à un état	
				$_SESSION['results'][$index]['coef'] += $temp*$Wi; // ajout de son score*Wi à la somme des Si*Wi
				$weight_sum += $Wi; // ajout de son Wi à la somme des Wi
		
				// Sauvegarde des détails pour affichage des résultats
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['sample'] = $Mxi;
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['species'] = round($Msi,2);
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['score'] = round($temp,2);
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['weight'] = $Wi;
				$_SESSION['results'][$index]['details']['qt'][''.$char_name.'']['SW'] = round($temp*$Wi,2);
		
				if($Wi != 0) {
					if($temp == 1) {
						$_SESSION['results'][$index]['nb_char_agree']++;
					}
					$_SESSION['results'][$index]['nb_char_used']++;
				}
			} else { 
				if (substr($char_name, -1) == 1) { // si c'est l'état 1 du caractère 
					$curr_state_sum = 0;
				}				
		
				// Sauvegarde des détails pour affichage des résultats
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['sample'] = $Mxi;
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['species'] = round($Msi,2);
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['state_score'] = round($temp,2);
				$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['weight'] = $Wi;
		
				$curr_state_sum += $temp;
				
				if (substr($char_name, -1) == $char_nbStates_array[''.$char_name.'']) { // si c'est le dernier état du caractère
		
					$temp = ($curr_state_sum+$temp) / $char_nbStates_array[''.$char_name.'']; // moyenne des scores des différents états	
					$_SESSION['results'][$index]['coef'] += $temp*$Wi; // ajout du score du caractère à la somme des Si*Wi
					
					$weight_sum += $Wi; // ajout du Wi à la somme des Wi
					
					// stockage du score du caractère pour affichage des résultats
					$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['char_score'] = round($temp,2);
					
					$_SESSION['results'][$index]['details']['ql'][''.$char_name.'']['SW'] = round($temp*$Wi,2);
			
					if($Wi != 0) {
						if($temp == 1) {
							$_SESSION['results'][$index]['nb_char_agree']++;
						}
						$_SESSION['results'][$index]['nb_char_used']++;
					}
				}
			}			
		} // fin foreach caractère
			
		// Calcul et stockage du score final de similarité	
		if ($weight_sum != 0) {
			$taux = $_SESSION['results'][$index]['coef'] / $weight_sum;
			if ($taux >=0){ // negative coefficient means that the specie has only one data and it invalid or the specie has no data
				$_SESSION['results'][$index]['coef'] = sprintf('%.2f',$taux);
			}			
		} else {
			$_SESSION['results'][$index]['coef'] = 0;
		}
	
	}// fin loop while mysql_fetch_array de la requete traitant toutes les descriptions de la bdd une par une	
}// fin simpleAlgo31
?>

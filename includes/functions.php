<?php
	session_start();
// Chemin d'acc�s au dossier de l'application NEMAID 3.0
define('ROOTPATH', 'http://'.$_SERVER['HTTP_HOST'].'/nemaid31', true);

// Donn�es relative a la FTP
define('FTP_SERVER',   '46.218.144.14');
define('FTP_USERNAME', 'genisys9685');
define('FTP_PASSWORD', '666732'); 

// Donn�es relative a la BDD
define('BDD_NAME',   '46.218.144.14');   
define('BDD_USERNAME', 'genisys9685');
define('BDD_PASSWORD', '666732');

// D�lai de connexion, en secondes
define('CONFIG_TIMEOUT',  2);     		 

// Adresse mail de l'administrateur
define('ADMIN_MAIL',  'li.redpanda@gmail.com');

function sqlquery($requete, $number) {
	$query = mysql_query($requete) or exit('Erreur SQL : '.mysql_error().' Ligne : '. __LINE__ .'.'); //requ�te
	
	/*
	Deux cas possibles ici :
	Soit on sait qu'on a qu'une seule entr�e qui sera
	retourn�e par SQL, donc on met $number � 1
	Soit on ne sait pas combien seront retourn�es,
	on met alors $number � 2.
	*/
	
	if($number == 1)
	{
		$query1 = mysql_fetch_assoc($query);
		mysql_free_result($query);
		/*mysql_free_result($query) lib�re le contenu de $query, je
		le fais par principe, mais c'est pas indispensable.*/
		return $query1;
	}
	
	else if($number == 2)
	{
		while($query1 = mysql_fetch_assoc($query))
		{
			$query2[] = $query1;
			/*On met $query1 qui est un array dans $query2 qui
			est un array. Ca fait un array d'arrays :o*/
		}
		mysql_free_result($query);
		return $query2;
	} else {
		exit('Argument de sqlquery non renseign� ou incorrect.');
	}
}

 
/*function CopieFichier($Source, $Destination)
{
    $Fichier = fopen ($Source, "r" );
 
    $ContenuFichier ='';
 
    while (!feof($Fichier)) $ContenuFichier .= fread($Fichier, 8192);
    fclose ($Fichier);
 
    $Fichier = fopen ($Destination, "w+" );
 
    if ( !fwrite($Fichier, $ContenuFichier)) die('Impossible d\'�crire dans le fichier');
 
    fclose ($Fichier);
}
 
CopieFichier('http://genisys.prd.fr/nemaid31/users_files', 'xml/contacts.xml');*/

 //Fonction qui permet le t�l�chargement
     function dl_file($file){
 
        //First, see if the file exists
       	if (!is_file($file)) { die("<b>404 File not found!</b>"); }
 
        //Gather relevent info about file
        $len = filesize($file);
        $filename = basename($file);
        //$file_extension = strtolower(substr(strrchr($filename,"."),1));
 
        //This will set the Content-Type to the appropriate setting for the file
        switch( $file_extension ) {
              case "pdf": $ctype="application/pdf"; break;
          case "exe": $ctype="application/octet-stream"; break;
          case "zip": $ctype="application/zip"; break;
          case "doc": $ctype="application/msword"; break;
          case "xls": $ctype="application/vnd.ms-excel"; break;
          case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
          case "gif": $ctype="image/gif"; break;
          case "png": $ctype="image/png"; break;
          case "jpeg":
          case "jpg": $ctype="image/jpg"; break;
          case "mp3": $ctype="audio/mpeg"; break;
          case "wav": $ctype="audio/x-wav"; break;
          case "mpeg":
          case "mpg":
		  case "xml": $ctype="application/xml"; break;
          case "mpe": $ctype="video/mpeg"; break;
          case "mov": $ctype="video/quicktime"; break;
          case "avi": $ctype="video/x-msvideo"; break;
 
          //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
          case "php":
          case "htm":
          case "html":
          case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;
 
          default: $ctype="application/force-download";
        }
 
        //Begin writing headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
 
        //Use the switch-generated Content-Type
        header("Content-Type: $ctype");
 
        //Force the download
        $header="Content-Disposition: attachment; filename=".$filename.";";
        header($header );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$len);
        @readfile($file);
        exit;
     }
 
    

function connexion_bdd() {
	//D�finition des variables de connexion � la base de donn�es
	$bd_host='46.218.144.13';
	$bd_login='genis9685';
	$bd_password='666732';
	$bd_nom_bd='genis9685';

	//Connexion � la base de donn�es
	mysql_connect($bd_host, $bd_login, $bd_password) or die (mysql_error());
	mysql_select_db($bd_nom_bd) or die (mysql_error());
	mysql_query("set names 'utf8'");
}

function vider_cookie() {
	foreach($_COOKIE as $cle => $element) {
		setcookie($cle, '', time()-3600);
	}
}

function vidersession() {
	foreach($_SESSION as $cle => $element) {
		unset($_SESSION[$cle]);
	}
}

function checkmail($email) {
	if(!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#is', $email)) return 'isnt';
	
	else {
		$result = sqlquery("SELECT COUNT(*) AS nbr FROM users WHERE email = '".mysql_real_escape_string($email)."'", 1);
		
		if($result['nbr'] > 0) return 'exists';
		else return 'ok';
	}
}

function inscription_mail($email, $pwd) {
	$to = $email;
	$subject = 'Inscription on Nemaid 3.0';
	$message = '<html>
				  <head></head>
				  <body>
					<h3>Your inscription is validated !</h3>
					<p>Remind of your information:</p>
					<ul>
						<li><b>Login:</b> '.$email.'</li>
						<li><b>Password:</b> '.$pwd.'</li>
					</ul>
				  </body>
				 </html>';
	$headers  = 'From: "Nemaid 3.0" <'.ADMIN_MAIL.'>' . "\r\n";
	$headers .= 'Reply-To: "Renaud Fortuner" <'.ADMIN_MAIL.'>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	mail($to, $subject, $message, $headers);
}
function information_mail($firstname, $lastname, $email, $institution, $town, $country) {
	$to = ADMIN_MAIL;
	$subject = 'A new user have registered on Nemaid 3.0 !';
	$message = '<html>
				  <head></head>
				  <body>
					<h3>A new user have registered on Nemaid 3.0 !</h3>
					<p>New user information:</p>
					<ul>
						<li><b>First name:</b> '.$firstname.'</li>
						<li><b>Last name:</b> '.$lastname.'</li>
						<li><b>Email:</b> '.$email.'</li>
						<li><b>Institution:</b> '.$institution.'</li>
						<li><b>Town:</b> '.$town.'</li>
						<li><b>Country:</b> '.$country.'</li>
					</ul>
				  </body>
				 </html>';
	$headers  = 'From: "Nemaid 3.0" <'.ADMIN_MAIL.'>' . "\r\n";
	$headers .= 'Reply-To: "Renaud Fortuner" <'.ADMIN_MAIL.'>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	mail($to, $subject, $message, $headers);
}

/*
 * G�n�ration du contenu du fichier xml
 * c'est � dire les param�tres pour un genre (Helico ou Aphasma)
 */
function generate_xml_content($gen, $dom, $root, $default) {
	//Element <genus name='nom genus'>
	$genus = $dom->createElement("genus");
	$root->appendChild($genus);
	$genus_name = $dom->createAttribute('name');
	$genus->appendChild($genus_name);
	$genus_name_value = $dom->createTextNode($gen);
	$genus_name->appendChild($genus_name_value);
		
	$query = mysql_query('SELECT code_char, weight, correction, max, min
						  FROM characters 
						  WHERE name_genus = "'.$gen.'" AND correction IS NOT NULL');
	while($row_quantitative = mysql_fetch_row($query)){
		// Element <char name='nom character'>
		$char = $dom->createElement('char');
		$genus->appendChild($char);
		$char_name = $dom->createAttribute('name');
		$char->appendChild($char_name);
		$char_name_value = $dom->createTextNode($row_quantitative[0]);
		$char_name->appendChild($char_name_value);
	
		// Element <weight>valeur</weight>
		$weight = $dom->createElement('weight');
		$char->appendChild($weight);
		if ($default) $weight_value = $dom->createTextNode($row_quantitative[1]);
		elseif ($row_quantitative[0] == "c''") $weight_value = $dom->createTextNode($_POST["c''_w"]);
		else $weight_value = $dom->createTextNode($_POST[$row_quantitative[0]."_w"]);
		$weight->appendChild($weight_value);
		
		// Element <correction>valeur</correction>
		$correction = $dom->createElement('correction');
		$char->appendChild($correction);
		if ($default) $correction_value = $dom->createTextNode($row_quantitative[2]);
		else $correction_value = $dom->createTextNode($_POST[$row_quantitative[0]."_c"]);
		$correction->appendChild($correction_value);
		
		// Element <range>valeur</range>
		$range = $dom->createElement('range');
		$char->appendChild($range);
		if ($default) $range_value = $dom->createTextNode($row_quantitative[3]-$row_quantitative[4]);
		else $range_value = $dom->createTextNode($_POST[$row_quantitative[0]."_r"]);
		$range->appendChild($range_value);
	
	}
		
	$query = mysql_query('SELECT code_char, nb_states, weight
						  FROM characters 
						  WHERE name_genus = "'.$gen.'" AND correction IS NULL');
	while($row_qualitative = mysql_fetch_row($query)){
		if($row_qualitative[1] == 1 || substr($row_qualitative[0], -1) == 1) {
			// Element <char name='nom character'>
			$char = $dom->createElement('char');
			$genus->appendChild($char);
			$char_name = $dom->createAttribute('name');
			$char->appendChild($char_name);
			$char_name_value = $dom->createTextNode($row_qualitative[0]);
			$char_name->appendChild($char_name_value);
		
			// Element <weight>valeur</weight>
			$weight = $dom->createElement('weight');
			$char->appendChild($weight);
			if ($default) $weight_value = $dom->createTextNode($row_qualitative[2]);
			else $weight_value = $dom->createTextNode($_POST[$row_qualitative[0]."_w"]);
			$weight->appendChild($weight_value);
		}
	}
}

/*
 * G�n�ration du contenu du fichier xml
 * c'est � dire les param�tres pour un genre (Helico ou Aphasma)
 * pour la version 31 de l'algo (ie o� les caracteres qualitatif ont des valeurs de corrections
 */
function generate_xml_content_31($gen, $dom, $root, $default) {
	//Element <genus name='nom genus'>
	$genus = $dom->createElement("genus");
	$root->appendChild($genus);
	$genus_name = $dom->createAttribute('name');
	$genus->appendChild($genus_name);
	$genus_name_value = $dom->createTextNode($gen);
	$genus_name->appendChild($genus_name_value);
		
	$query = mysql_query('SELECT code_char, weight, correction, max, min
						  FROM characters31
						  WHERE name_genus = "'.$gen.'"');
	while($row = mysql_fetch_row($query)){
		// Element <char name='nom character'>
		$char = $dom->createElement('char');
		$genus->appendChild($char);
		$char_name = $dom->createAttribute('name');
		$char->appendChild($char_name);
		$char_name_value = $dom->createTextNode($row[0]);
		$char_name->appendChild($char_name_value);
	
		// Element <weight>valeur</weight>
		$weight = $dom->createElement('weight');
		$char->appendChild($weight);
		if ($default) $weight_value = $dom->createTextNode($row[1]);
		elseif ($row[0] == "c''") $weight_value = $dom->createTextNode($_POST["c''_w"]);
		else $weight_value = $dom->createTextNode($_POST[$row[0]."_w"]);
		$weight->appendChild($weight_value);
		
		// Element <correction>valeur</correction>
		$correction = $dom->createElement('correction');
		$char->appendChild($correction);
		if ($default) $correction_value = $dom->createTextNode($row[2]);
		else $correction_value = $dom->createTextNode($_POST[$row[0]."_c"]);
		$correction->appendChild($correction_value);
		
		// Element <range>valeur</range>
		$range = $dom->createElement('range');
		$char->appendChild($range);
		if ($default) $range_value = $dom->createTextNode($row[3]-$row[4]);
		else $range_value = $dom->createTextNode($_POST[$row[0]."_r"]);
		$range->appendChild($range_value);
	
	}
}

/* 
 * Creation d'un fichier xml contenant les param�tres de r�glages
 * de l'application
 * $gen = null pour g�n�rer le fichier des param�tres par d�fault
 * sinon $gen prend le nom d'un genre, le fichier xml contient
 * alors les parametres d'un utilisateur sp�cifique
 */
function generate_xml_file($gen) {
	// create doctype
	$dom = new DOMDocument("1.0");

	// create root element
	$root = $dom->createElement("parameters");
	$dom->appendChild($root);
	$dom->formatOutput=true;

	connexion_bdd();
	
	if ($gen == 'default') {
		$query_genus = mysql_query('SELECT * FROM genera');
		while($row_genus = mysql_fetch_row($query_genus)){
			generate_xml_content($row_genus[0], $dom, $root, true);
		}
		$dom->save("default_params.xml");
		dl_file ( $_GET("users_files/user.default_params.xml" ));
		return $dom;
	} elseif ($gen == 'default31') {
		$query_genus = mysql_query('SELECT * FROM genera');
		while($row_genus = mysql_fetch_row($query_genus)){
			generate_xml_content_31($row_genus[0], $dom, $root, true);
		}
		$dom->save("default_params_31.xml");
		dl_file ( $_GET("users_files/user.default_params_31.xml" ));
		return $dom;
	} else {
		generate_xml_content($gen, $dom, $root, false);
		$dom->save("users_files/user".$_SESSION['user_id']."_params.xml");
		dl_file ("users_files/user".$_SESSION['user_id']."_params.xml") ;
		return $dom;
		//d�clenche le t�l�chargement du fichier xml de param�tres
		$url = ('users_files/user'.$_SESSION['user_id']."_params.xml");
	    header('Content-Description: xml download');
	    header('Content-Type: .xml');
	    header('Content-Disposition: attachment; filename="'. basename($url) .'";');
	    @readfile($url) OR die();
	}
	
	mysql_close();
}

/* Renvoit les donn�es enregistr�es par l'user
 * $what = genus, on renvoit le genre choisi
 * $what = params, on renvoit tous les parametres (weight, correction, range)
 * $what = sample, on envoit les donn�es du sample enregistr�
 * $what = default, on recupere les params par default
 */
function get_xml_data($what,$file = '') {
	switch ($what) {
		case 'user_params' : case 'genus': $xml = simplexml_load_file("users_files/user".$_SESSION['user_id']."_params.xml"); break;
		case 'default_params' : $xml = simplexml_load_file("default_params.xml"); break;
		case 'default_params_31' : $xml = simplexml_load_file("default_params_31.xml"); break;
		case 'user_sample' : $xml = simplexml_load_file("users_files/".$file); echo "file is ".$file.'</br>'; break;
		default: $xml = simplexml_load_file("default_params.xml"); break;
	}
	
	if ($what == "genus") {
		$genus = $xml->xpath('/parameters/genus');
		foreach( $genus as $g ){
			$res = $g['name'];
			return $res;
		}
	} elseif($what == "user_params" || $what == "default_params" || $what == "default_params_31") {
		$params = array();
	
		$char = $xml->xpath('/parameters/genus/char');
		foreach( $char as $c ){
			$params[(string)$c['name']] = array('weight' => sprintf('%.2f',$c->weight), 'correction' => sprintf('%.2f',$c->correction), 'range' => sprintf('%.2f',$c->range));
		}
		
		return($params);
	} elseif($what == "user_sample") {
		$sample = array();
	
		$char = $xml->xpath('/sample/char');
		foreach( $char as $c ){
			$sample[(string)$c['name']] = array('value' => sprintf('%.2f',$c->value),'weight' => sprintf('%.2f',$c->weight), 'correction' => sprintf('%.2f',$c->correction),'range' => "no range");	
			//if($value == "NULL") $sample[(string)$value['name']] = "NULL";
			//else $sample[(string)$value['name']] = sprintf('%.2f',$value);
		}
		return($sample);
	}
}

function get_weight($value_w){
	foreach ($_POST as $arr_int){
		if ($_POST[$value_w.'_w'] != ''){
			return $_POST[$value_w.'_w'];
		}
	}
}

function save_user_sample($genus, $sample_id, $sample_date, $sample_loc, $sample_host, $remarks) {
	// create doctype
	$dom = new DOMDocument("1.0");

	// create root element
	$root = $dom->createElement('sample');
	$dom->appendChild($root);
	$dom->formatOutput=true;

	// Remarques sur l'echantillon
	$rq = $dom->createElement('remarks');
	$root->appendChild($rq);
	$rq_value = $dom->createTextNode($remarks);
	$rq->appendChild($rq_value);
	
	// Date
	$date = $dom->createElement('date');
	$root->appendChild($date);
	$date_value = $dom->createTextNode($sample_date);
	$date->appendChild($date_value);
	
	connexion_bdd();
	$query = mysql_query('SELECT characters.code_char, ' .
			'characters.weight, ' .
			'characters31.weight, ' .
			'characters.correction, ' .
			'characters31.correction, ' .
			'(characters.max-characters.min), ' .
			'(characters31.max-characters31.min) ' .
			'FROM characters, characters31 ' .
			'WHERE characters.code_char = characters31.code_char and characters.name_genus = "'.$genus.'"');
							  
	$cpt = 0;	
	while($row = mysql_fetch_row($query)){ // <char name="char_name">value</char>		
		$cpt++;
		// Element <char></char>
		$char = $dom->createElement('char');
		$root->appendChild($char);
		
		$value = $dom->createElement('value');
		$char->appendChild($value);
		
		$weight = $dom->createElement('weight');
		$char->appendChild($weight);
		
		$weight31 = $dom->createElement('weight31');
		$char->appendChild($weight31);
		
		$correction = $dom->createElement('correction');
		$char->appendChild($correction);
		
		$correction31 = $dom->createElement('correction31');
		$char->appendChild($correction31);
		
		$range = $dom->createElement('range');
		$char->appendChild($range);
		
		$range31 = $dom->createElement('range31');
		$char->appendChild($range31);
		
		
		// Attribute name
		$char_name = $dom->createAttribute('name');
		$char->appendChild($char_name);
		$char_name_value = $dom->createTextNode($row[0]);
		$char_name->appendChild($char_name_value);
		
		
		//value
		$value_name = $dom->createAttribute('value');
		$value->appendChild($value_name);
		$value_name_value = $dom->createTextNode(($row[0]).'_v');
		$value_name->appendChild($value_name_value);
		
		if($_POST[$row[0].'_v'] != '')
			$value_v = $dom->createTextNode($_POST[$row[0].'_v']);
		else $value_v = $dom->createTextNode("NULL");
		$value->appendChild($value_v);
		
		//weight
		$weight_value = $dom->createAttribute('weight');
		$weight->appendChild($weight_value);
		$weight_name_value = $dom->createTextNode(($row[0]).'_w');
		$weight_value->appendChild($weight_name_value);
		
		if (empty($_POST[$row[0].'_w'])){
			$value_w = $dom->createTextNode($row[1]);
			$weight->appendChild($value_w);
		}else{
			$value_w = $dom->createTextNode(get_weight($row[0]));
			$weight->appendChild($value_w);
		}
		
		//weight31
		$weight_value31 = $dom->createAttribute('weight31');
		$weight31->appendChild($weight_value31);
		$weight_name_value31 = $dom->createTextNode(($row[0]).'_w');
		$weight_value31->appendChild($weight_name_value31);
		
		if (empty($_POST[$row[0].'_w'])){
			$value_w31 = $dom->createTextNode($row[2]);
			$weight31->appendChild($value_w31);
		}else{
			$value_w31 = $dom->createTextNode(get_weight($row[0]));
			$weight31->appendChild($value_w31);
		}
		
		//correction
		$correction_value = $dom->createAttribute('correction');
		$correction->appendChild($correction_value);//	$weight
		$correction_name_value = $dom->createTextNode(($row[0]).'_c');
		$correction_value->appendChild($correction_name_value);
		
		$value_c = $dom->createTextNode($_POST[$row[0].'_c']);
		$correction->appendChild($value_c);
		
		//correction31
		$correction_value31 = $dom->createAttribute('correction31');
		$correction31->appendChild($correction_value31);//	$weight
		$correction_name_value31 = $dom->createTextNode(($row[0]).'_c');
		$correction_value31->appendChild($correction_name_value31);
		
		if (empty($_POST[$row[0].'_w'])){
			$value_c31 = $dom->createTextNode($_POST[$row[0].'_c']);
			$correction31->appendChild($value_c31);
		}else{
			$value_c31 = $dom->createTextNode($_POST[$row[0].'_c']);
			$correction31->appendChild($value_c31);	
		}
		
		//range
		$range_value = $dom->createAttribute('range');
		$range->appendChild($range_value);//	$weight
		$range_name_value = $dom->createTextNode(($row[0]).'_r');
		$range_value->appendChild($range_name_value);
		
		if (empty($_POST[$row[0].'_w'])){
			$value_r = $dom->createTextNode($row[5]);
			$range->appendChild($value_r);
		}else{
			$value_r = $dom->createTextNode($row[5]);
			$range->appendChild($value_r);
		}
		
		//range31
		$range_value31 = $dom->createAttribute('range31');
		$range31->appendChild($range_value31);//	$weight
		$range_name_value31 = $dom->createTextNode(($row[0]).'_r');
		$range_value31->appendChild($range_name_value31);
		
		if (empty($_POST[$row[0].'_w'])){
			$value_r31 = $dom->createTextNode($row[6]);
			$range31->appendChild($value_r31);
		}else{
			$value_r31 = $dom->createTextNode($row[6]);
			$range31->appendChild($value_r31);
		}
	}
	
	$name = "users_files/".$_SESSION['nb_sample_saved']."-user".$_SESSION['user_id']."_sample";
	if($sample_id != '') $name .= "$".$sample_id;
	if($sample_loc != '') $name .= "_".$sample_loc;
	if($sample_host != '') $name .= "_".$sample_host;
	
	$dom->save($name.".xml");

	$_SESSION['current_name'] = $name;
	return $dom;
	mysql_close();
	
}


function curPageName() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

function delete_user_files() {
	$sample_files = get_user_samples();
	foreach($sample_files as $f) {
		if (file_exists("users_files/".$f)) unlink("users_files/".$f);
	}
	
	if (file_exists("users_files/user".$_SESSION['user_id']."_params.xml")) unlink("users_files/user".$_SESSION['user_id']."_params.xml");
}

function get_user_samples() {
	$pointeur=opendir('users_files');
	$files_list = array();
	while ($file = readdir($pointeur)) {
		if ($file != "." && $file != ".." && substr_count($file,$_SESSION['user_id']."_sample")) {
			$files_list[] = $file;
		}
	}
	closedir($pointeur);
	return $files_list;
}

function define_genus() {
	switch($_SESSION['genus_n']) {
		case 'heli1': case 'heli2': case 'heli3': case 'heli4':
			return 'Helicotylenchus';
		case 'aphas': return 'Aphasmatylenchus';
		default: return 'Helicotylenchus';
	}
}

function get_value_by_key($array,$key)
{
 foreach($array as $k=>$each)
 {
  if($k==$key)
  {
   return $each;
  }

  if(is_array($each))
  {
   if($return = get_value_by_key($each,$key))
   {
    return $return;
   }
  }

 }

}
?>

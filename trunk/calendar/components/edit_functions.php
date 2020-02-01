<?
//-----------------------------------------------------------------------------------------------
// New Functions designed by Daniel Tisza-Nitsch and Arthur Gressick
// ** These functions were created to simplify the uploading of information to the database.
//    With these functions, you can send information to the database in one single function call
//      to insert or update information, as well as creating an audit trail for tracking.
//-----------------------------------------------------------------------------------------------


// The basic normal set trings function.  This works for almost everything.
function set_strs($str,$field_info,$info_old,$aud,$table,$id) { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != encode($_POST[$field_info]) || strlen($info_old) != strlen(encode($_POST[$field_info]))) 
	{
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". encode($_POST[$field_info]) ."' ");
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". encode($_POST[$field_info]) ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// The checkbox functions.  This works for almost everything.
function set_strs_checkbox($str,$field_info,$info_old,$aud,$table,$id) { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	$info_old = (($info_old == 1) ? 'on' : '');
	if($info_old != $_POST[$field_info]) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". ($_POST[$field_info] == 'on' ? 1 : 0) ."' ");
	}
	if($info_old != $_POST[$field_info]) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". ($_POST[$field_info] == 'on' ? 1 : 0) ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// Sets the password fields to MD5 hashes and checks against that.  NO AUDIT for security purposes
function set_strs_password($str,$field_info,$info_old,$aud,$table,$id) { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	$pwd = sha1($_POST[$field_info]);
	if($info_old != $pwd) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". $pwd ."' ");
	}
	// No audit on the password.
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// Sets the strings, but formats the input for Year-Month-Day (yyyy-mm-dd) format
function set_strs_date($str,$field_info,$info_old,$aud,$table,$id,$doaudit='true',$format='Y-m-d') { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != $_POST[$field_info] || date($format,strtotime($info_old)) != date($format,strtotime($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". ($_POST[$field_info] != '' ? date($format,strtotime($_POST[$field_info])) : '0000-00-00') ."' ");
	}
	if($doaudit) {
		if($info_old != $_POST[$field_info] || date($format,strtotime($info_old)) != date($format,strtotime($_POST[$field_info]))) {
			$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". ($_POST[$field_info] != '' ? date($format,strtotime($_POST[$field_info])) : '') ."')"));
		}
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// Sets the strings, but formats the input for Hour:min:sec (23:59:59) format
function set_strs_time($str,$field_info,$info_old,$aud,$table,$id,$format='H:i:s') { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != $_POST[$field_info] || date($format,strtotime($info_old)) != date($format,strtotime($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". ($_POST[$field_info] != '' ? date($format,strtotime($_POST[$field_info])) : '00:00:00') ."' ");
	}
	if($info_old != $_POST[$field_info] || date($format,strtotime($info_old)) != date($format,strtotime($_POST[$field_info]))) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". ($_POST[$field_info] != '' ? date($format,strtotime($_POST[$field_info])) : '') ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// Sets the strings, but formats the input for Year-Month-Day Hour:min:sec (yyyy-mm-dd 23:59:59) format
function set_strs_datetime($str,$field_info,$info_old,$aud,$table,$id,$format='Y-m-d H:i:s') { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != $_POST[$field_info] || date($format,strtotime($info_old)) != date($format,strtotime($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". ($_POST[$field_info] != '' ? date($format,strtotime($_POST[$field_info])) : '0000-00-00 00:00:00') ."' ");
	}
	if($info_old != $_POST[$field_info] || date($format,strtotime($info_old)) != date($format,strtotime($_POST[$field_info]))) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". ($_POST[$field_info] != '' ? date($format,strtotime($_POST[$field_info])) : '') ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}


// This is the script that does the official uploads into the DB.
function update_record($str, $aud, $table, $id, $error=true) { //This function does the insert into the database for the Audit - Reference the set_audit_str
	if($str != "") {
		$finstr[0] = "UPDATE ". $table ." SET " . $str . "WHERE ID=". $id;
		if(!db_query($finstr[0],"Insert update statement")) {
			//if($error) { errorPage('An error has occured while trying to update the "'.$table.'" table.'); }
			echo $finstr[0];
		}
	}
	if($aud != "") {
		$finstr[1] = "INSERT INTO Audit (idUser, idType, idRecord, chrTablename, chrColumnName, txtOldValue, txtNewValue) VALUES ". $aud;
		db_query($finstr[1],"Insert audit statement");
	}
	return($finstr);
}


	//-----------------------------------------------------------------------------------------------
	// New Function designed by Daniel Tisza-Nitsch
	// ** Random key generator.  This was make a rediculously secure key to search for values on.
	//-----------------------------------------------------------------------------------------------
	function makekey() {
		$email = (isset($_SESSION['chrEmail']) ? $_SESSION['chrEmail'] : 'unknown@emailadsa.com');
		return sha1(uniqid(mt_rand(1000000,9999999).$email.time(), true));
	}
?>
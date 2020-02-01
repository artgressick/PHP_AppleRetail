<?php

// This is needed for the Date Functions
if(phpversion() > '5.0.1') { date_default_timezone_set('America/Los_Angeles'); }

require_once($BF. 'components/JSON.php');
$json = new Services_JSON();

// The configuration file that connects us to the mysql servers
include('appleretail-conf.php');

// set up error reporting
require_once($BF. 'components/ErrorHandling/error_handler.php');
	

if(!isset($host)) {
	error_report("Include database conf failed");
	$connected = false;
} else {
	$connected = true;
	if($mysqli_connection = @mysqli_connect($host, $user, $pass)) {
		if(!@mysqli_select_db($mysqli_connection, $db)) {
			error_report("mysqli_select_db(): " . mysqli_error($mysqli_connection));
		}
	} else {
		error_report("mysqli_connect(): " . mysqli_connect_error($mysqli_connection));
	}
}
// clean up so that these variables aren't exposed through the debug console
unset($host, $user, $pass, $db);

// Set and use the session
session_name('appleretail');
session_start();

// If Logout is set in the URL bar, destroy the session and cookies.
if(isset($_REQUEST['logout'])) {
	setcookie(session_name(), "", 0, "/");
	$_SESSION = array();
	session_unset();
	session_destroy();
	header("Location: ".$BF."index.php");
}

auth_check();


// Security Check, and get Permissions if user is logged in.
	if (isset($_SESSION['idUser']) && $_SESSION['idUser'] != "") {
	
		$Security = database_query("SELECT * FROM Users WHERE ID=".$_SESSION['idUser'],".lib File: Get Users Security",1);
		
		// Unset any unneeded variables
		unset($Security['ID']);
		unset($Security['chrFirst']);
		unset($Security['chrLast']);
		unset($Security['chrEmail']);
		unset($Security['chrPassword']);
		
		// See if user has been deleted, then force a logout
		if ($Security['bDeleted'] == true) {
			header("Location: " . $BF . "index.php?logout=1");
			die();
		}
	}


function maintenance_page() {
?>
	<h1>We're Sorry...</h1>
	<p>Could not connect to the database server.  We could be experiencing trouble, or the site may be down for maintenance.</p>
	<p>You can press the Refresh button to see if the site is available again.</p>
<?
	die();
}

function error_report($message) {
	ob_start();
	print_r(debug_backtrace());
	$trace = ob_get_contents();
	ob_end_clean();

	mail(constant('BUG_REPORT_ADDRESS'), '[ShowMan] Error',
		"- ERROR\n----------------\n" . $message . "\n\n\n- STACK\n----------------\n" . $trace
		);

	maintenance_page();		
}


function database_query($query, $description, $fetch=0, $ignore_warnings=false, $connection=null) {

	global $mysqli_connection, $database_time;
	if($connection == null) {
		$connection = $mysqli_connection;
	}

	$begin_time = microtime(true);
	$result = mysqli_query($connection, $query);
	$end_time = microtime(true);

	$database_time += ($end_time-$begin_time);

	if(!is_bool($result)) {
		$num_rows = mysqli_num_rows($result);
		$str = $num_rows . " rows";
	} else {
		$affected = mysqli_affected_rows($connection);
		$str = $affected . " affected";
	}

	if ($result === false) {
		_error_debug(array('error' => mysqli_error($connection), 'query' => $query), "MySQL ERROR: " . $description, __LINE__, __FILE__, E_ERROR);
	} else {
		
		if(mysqli_warning_count($connection) && !$ignore_warnings) {
			$warnings = mysqli_get_warnings($connection);
			_error_debug(array('query' => $query, 'warnings' => $warnings), "MySQL WARNING(S): " . $description, __LINE__, __FILE__, E_WARNING);
		} else {
			_error_debug(array('query' => $query), "MySQL (" . $str . ", " . (round(($end_time-$begin_time)*1000)/1000) . " sec): " . $description, __LINE__, __FILE__);
		}
	}
	return(($fetch != 0 ? mysqli_fetch_assoc($result) : $result));
}


function auth_check()
{
	$auth = false;
	global $BF;
	
	if (isset($_SESSION['idUser'])) {  // if this variable is set, they are already authenticated in this session
		$auth = true;
	} else {
		include($BF. 'includes/auth_check.php');
	}

}

function ErrorPage($lnkHome,$msg) {
	global $BF;
	if(isset($msg)) {$_SESSION['chrErrorMsg'] = $msg;}
	if(isset($lnkHome)) {$_SESSION['lnkHome'] = $lnkHome;}
	header("Location: ".$BF."error.php");
	die;
}



//-----------------------------------------------------------------------------------------------
// New Functions designed by Jason Summers and written by Daniel Tisza-Nitsch
// ** These functions were created to simplify the uploading of information to the database.
//    With these functions, you can send encode/decode all quotes from a given text and ONLY the quotes.
//      This script assumes that you are setting up database tables to accept UTF-8 characters for all 
//		entities.
//-----------------------------------------------------------------------------------------------

function encode($val) {
	$val = str_replace("'",'&#39;',stripslashes($val));
	$val = str_replace('"',"&quot;",$val);
	return $val;
}

function decode($val) {
	$val = str_replace('&quot;','"',$val);
	$val = str_replace("&apos;","'",$val);
	$val = str_replace("&#39;","'",$val);
	return $val;
}

/*  This is to create temp variables that SHOULD be erased from the sessions which might not be */
if(isset($_SESSION['tmp'])) {
	foreach($_SESSION['tmp'] as $k => $v) {
		if(!isset($_SESSION['tmp'][$k]['count'])) { 
			$_SESSION['tmp'][$k]['count'] = 3; 
		} else {
			if(--$_SESSION['tmp'][$k]['count'] == 0) { unset($_SESSION['tmp'][$k]); }
		}
		if(count($_SESSION['tmp']) == 0) { unset($_SESSION['tmp']); }
	}
}

function tmp_val($name,$type,$value) {
	if($type == 'get') { 
		return $_SESSION['tmp'][$name]['value']; 
	} else if($type == 'set') {
		if(!isset($_SESSION['tmp'])) { $_SESSION['tmp'] = array(); }
		$_SESSION['tmp'][$name]['value'] = $value;
		$_SESSION['tmp'][$name]['count'] = 3;
	}
}

//-----------------------------------------------------------------------------------------------
// New Functions designed by Jason Summers and written by Jason Summers, based off of code for encode/decode
// ** These functions were created to change the display of the Audit table entries for TinyMCE Entries.
//    With these functions, you can send audit_encode/audit_decode all brackets from a given text and ONLY the brackets.
//      This script assumes that you are setting up database tables to accept UTF-8 characters for all 
//		entities.
//-----------------------------------------------------------------------------------------------

function audit_encode($val) {
	$val = str_replace("<",'&lt;',stripslashes($val));
	$val = str_replace('>',"&gt;",$val);
	$val = str_replace("'",'&#39;',stripslashes($val));
	$val = str_replace('"',"&quot;",$val);
	return $val;
}

function audit_decode($val) {
	$val = str_replace('&lt;','<',$val);
	$val = str_replace("&gt;",">",$val);
	$val = str_replace('&quot;','"',$val);
	$val = str_replace("&apos;","'",$val);
	$val = str_replace("&#39;","'",$val);
	return $val;
}

//-----------------------------------------------------------------------------------------------
// New Function designed by Daniel Tisza-Nitsch
// ** These function was created to simplify the deleting process on the list pages
//-----------------------------------------------------------------------------------------------

function deleteButton($id,$message) {
	global $BF;
	?>
		<div class='deleteImage' onmouseover='document.getElementById("deleteButton<?=$id?>").src="<?=$BF?>images/button_delete_on.png"' onmouseout='document.getElementById("deleteButton<?=$id?>").src="<?=$BF?>images/button_delete.png"'><a href="javascript:warning(<?=$id?>, '<?=str_replace("&","&amp;",$message)?>');"><img id='deleteButton<?=$id?>' src='<?=$BF?>images/button_delete.png' alt='delete button' /></a>
	<?
}

//-----------------------------------------------------------------------------------------------
// New Function designed by Daniel Tisza-Nitsch
// ** Random key generator.  This was make a rediculously secure key to search for values on.
//-----------------------------------------------------------------------------------------------
function makekey() {
	$email = (isset($_SESSION['chrEmail']) ? $_SESSION['chrEmail'] : 'unknown@emailadsa.com');
    return sha1(uniqid(mt_rand(1000000,9999999).$email.time(), true));
}

//-----------------------------------------------------------------------------------------------
// New Functions designed by Daniel Tisza-Nitsch and Arthur Gressick
// ** These functions were created to simplify the uploading of information to the database.
//    With these functions, you can send information to the database in one single function call
//      to insert or update information, as well as creating an audit trail for tracking.
//-----------------------------------------------------------------------------------------------

// The basic normal set trings function.  This works for almost everything.
function set_strs($str,$field_info,$info_old,$aud,$table,$id) { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != encode($_POST[$field_info]) || strlen($info_old) != strlen(encode($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". encode($_POST[$field_info]) ."' ");
	}
	if($info_old != encode($_POST[$field_info]) || strlen($info_old) != strlen(encode($_POST[$field_info]))) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". audit_encode($info_old) ."','". audit_encode($_POST[$field_info]) ."')"));
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
function set_strs_date($str,$field_info,$info_old,$aud,$table,$id, $format='Y-m-d') { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != date($format,strtotime($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". date($format,strtotime($_POST[$field_info])) ."' ");
	}
	if($info_old != $_POST[$field_info]) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". $_POST[$field_info] ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// Sets the strings, but formats the input for Hour:min:sec (23:59:59) format
function set_strs_time($str,$field_info,$info_old,$aud,$table,$id,$format='H:i:s') { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != date($format,strtotime($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". date($format,strtotime($_POST[$field_info])) ."' ");
	}
	if($info_old != $_POST[$field_info]) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". $_POST[$field_info] ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// Sets the strings, but formats the input for Year-Month-Day Hour:min:sec (yyyy-mm-dd 23:59:59) format
function set_strs_datetime($str,$field_info,$info_old,$aud,$table,$id,$format='Y-m-d H:i:s') { //This function does the additions to an update script
	$tmpStr = $tmpAud = "";
	if($info_old != date($format,strtotime($_POST[$field_info]))) {
		$tmpStr = (($str == '' ? '' : ',')." ". $field_info. "='". date($format,strtotime($_POST[$field_info])) ."' ");
	}
	if($info_old != $_POST[$field_info]) {
		$tmpAud = ((($aud == '' ? '' : ',')." ('". $_SESSION['idUser'] ."',2,'" . $id . "','". $table ."','". $field_info ."','". $info_old ."','". $_POST[$field_info] ."')"));
	}
	$tmp = array(($str .= $tmpStr),($aud .= $tmpAud));
	return($tmp);
}

// This is the script that does the official uploads into the DB.
function update_record($str, $aud, $table, $id) { //This function does the insert into the database for the Audit - Reference the set_audit_str
	$finstr[0] = "UPDATE ". $table ." SET " . $str . "WHERE ID=". $id;
	database_query($finstr[0],"Insert mysql statement");
	if ($aud != "") {
		$finstr[1] = "INSERT INTO Audit (idUser, idType, idRecord, chrTablename, chrColumnName, txtOldValue, txtNewValue) VALUES ". $aud;
		database_query($finstr[1],"Insert update statement");
	}
	return($finstr);
}
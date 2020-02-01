<?php

// This is needed for the Date Functions
if(phpversion() > '5.0.1') { date_default_timezone_set('America/Los_Angeles'); }

// The configuration file that connects us to the mysql servers
include('appleretail-conf.php');

// set up error reporting
require_once($BF. 'calendar/components/ErrorHandling/error_handler.php');

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
session_name($PROJECT_NAME);
session_start();

// If Logout is set in the URL bar, destroy the session and cookies.
if(isset($_REQUEST['logout'])) {
	$logoutReason = $_REQUEST['logout'];
	setcookie(session_name(), "", 0, "/");
	$_SESSION = array();
	session_unset();
	session_destroy();
	if(!in_csv($logoutReason,'1,2,3')) {
		$logoutReason = 0;
	}
	header("Location: ".$BF."calendar/logout.php?id=".$logoutReason);
	die();
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

if(!isset($_SESSION['NewFeaturePopup']) && isset($_SESSION['idUser'])) {
	$popup = db_query("SELECT txtAnnoucement FROM NSONewFeatures WHERE bShow AND ID=1","Gettin Popup",1);
	if($popup['txtAnnoucement'] != '') {
		$bodypop = " javascript:newwin = window.open(\"".$BF."calendar/newfeatures.php\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();";
		$_SESSION['NewFeaturePopup'] = 1;
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



function error_report($message) {
	ob_start();
	print_r(debug_backtrace());
	$trace = ob_get_contents();
	ob_end_clean();

	$emailto = (defined('BUG_REPORT_ADDRESS') ? constant('BUG_REPORT_ADDRESS') : 'bugs@techitsolutions.com');
	mail($emailto, '['.$PROJECT_NAME.'] Error',
		"- ERROR\n----------------\n" . $message . "\n\n\n- STACK\n----------------\n" . $trace
		);

?>
	<h1>We're Sorry...</h1>
	<p>Could not connect to the database server.  We could be experiencing trouble, or the site may be down for maintenance.</p>
	<p>You can press the Refresh button to see if the site is available again.</p>
<?
	die();
}

function db_query($query, $description, $fetch=0, $ignore_warnings=false, $connection=null) {

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

function auth_check($sitm='',$file='',$option='') {
	global $BF;
	if(!isset($_SESSION['idUser'])) {  // if this variable is set, they are already authenticated in this session
		include($BF. 'calendar/includes/auth_check.php');
	} else { // Checking Security
		// First Lets see if they have been logged in longer then 12 hours
		if ($_SESSION['dtLogin'] <= date('m/d/Y H:i:s',strtotime("-12 hours"))) {
			header("Location: index.php?logout=3");
			die();
		} 
		if ($_SESSION['dtLastSecurityCheck'] <= date('m/d/Y H:i:s',strtotime("-15 minutes"))) {  // Do we need to do a security check update

			$q = "SELECT CA.idSecurity, CS.bGlobal, CA.bShowOrangeEvents
					FROM Users AS U
					JOIN CalendarAccess AS CA ON CA.idUser=U.ID AND !CA.bDeleted
					JOIN CalSecurity AS CS ON CA.idSecurity=CS.ID
					WHERE !CA.bDeleted AND !CS.bDeleted AND !U.bDeleted && U.ID='".$_SESSION['idUser']."'";
			$tmp = db_query($q,'Checking Access',1);				
			$_SESSION['bShowOrangeEvents'] = $tmp['bShowOrangeEvents'];
			if(isset($tmp['bGlobal']) && $tmp['bGlobal'] != 1) { // Not Global
				$_SESSION['bGlobal'] = 0;
				$security = db_query("SELECT F.ID, chrLevels 
										FROM CalSecFiles AS F
										LEFT JOIN CalSecuritySelections AS S ON F.ID=S.idCalSecFile AND S.idCalSecurity=".$tmp['idSecurity']." 
										ORDER BY F.ID","Get Security Options");
				unset($_SESSION['Security']);
				while($row = mysqli_fetch_assoc($security)) {
					$_SESSION['Security'][$row['ID']] = $row['chrLevels'];
				}
			} else if(isset($tmp['bGlobal']) && $tmp['bGlobal'] == 1) { // Is Global
				$_SESSION['bGlobal'] = 1;
			} else { // No Record
				errorPage('You do not have authorization to access this page.');
			}
			
			$_SESSION['dtLastSecurityCheck'] = date('m/d/Y H:i:s');
		} 
		
		// Do we have permission to access this page?  Check Against $_SESSION['idLevel']
		if(!access_check($file,$option)) {
			noAccess();
		}
	}
}

function in_csv($needle,$haystack) { return preg_match('/(^|,)'.$needle.'(,|$)/',$haystack); } 

function access_check($file='',$option='') {
	if(isset($_SESSION['bGlobal']) && !$_SESSION['bGlobal']) {
		return in_csv($option,$_SESSION['Security'][$file]);
	} else if(isset($_SESSION['bGlobal']) && $_SESSION['bGlobal']) {
		return true; 
	} else {
		return false;
	}
}

//-----------------------------------------------------------------------------------------------
// New Functions designed by Jason Summers and written by Daniel Tisza-Nitsch
// ** These functions were created to simplify the uploading of information to the database.
//    With these functions, you can send encode/decode all quotes from a given text and ONLY the quotes.
//      This script assumes that you are setting up database tables to accept UTF-8 characters for all 
//		entities.
//-----------------------------------------------------------------------------------------------

function encode($val,$extra="") {
	$val = str_replace("'",'&#39;',stripslashes($val));
	$val = str_replace('"',"&quot;",$val);
	if($extra == "tags") { 
		$val = str_replace("<",'&lt;',stripslashes($val));
		$val = str_replace('>',"&gt;",$val);
	}
	if($extra == "amp") { 
		$val = str_replace("&",'&amp;',stripslashes($val));
	}
	return $val;
}

function decode($val,$extra="") {
	$val = str_replace('&quot;','"',$val);
	$val = str_replace("&#39;","'",$val);
	if($extra == "tags") { 
		$val = str_replace('&lt;',"<",$val);
		$val = str_replace("&gt;",'>',$val);
	}
	if($extra == "amp") { 
		$val = str_replace("&amp;",'&',stripslashes($val));
	}
	return $val;
}

//-----------------------------------------------------------------------------------------------
// New Function designed by Jason Summers
// ** These function was created to call the error page and pass information to it.
//-----------------------------------------------------------------------------------------------
function errorPage($msg) {
	global $BF;
	if(isset($msg)) {$_SESSION['chrErrorMsg'] = $msg;}
	header("Location: ".$BF."calendar/error.php");
	die;
}

function noAccess() {
	global $BF;
	header("Location: ".$BF."calendar/noaccess.php");
	die;
}

#############################################################################################
## New Function for hyperlinks and images
## function linkto Example:
## linkto(
##			 'address' => 'edit.php',						// Automatically looks in the current folder.  If there is a '/' in
##															//  the address, it will look for the address in the ROOT/address folder.
##															//  Ex:  address'=>' myprofile/edit.php  -->  ROOT/myprofile/edit.php
##			 'display' => 'Display Name',					// Text shown for link
##			 'title' => 'Title',							// Displays this text when mouse is hovered over field 
##			 'id' => 'Field ID',							// ID of field for JS, (ie. chrFirst)
##			 'class' => 'Field Class',						// Class for the hyperlink
##			 'style' => 'CSS Style',						// Extra Styles for the hyperlink
##			 'extra' => 'Extra Code, Javascript, etc',		// For additional JS, to Disable Field, or any additional options can be entered here.
##			 'img' => 'picture.jpg',						// Adds in an image for you defaulting to ROOT/images/
##			 'imgclass' => 'Field Class',					// Class for the image
##			 'imgstyle' => 'CSS Style'						// Extra Styles for the image
##			);
####################################################################
function linkto($args) {
	if(is_array($args)) { 
		global $BF;

		$addysrc = (preg_match('/\//',$args['address']) ? (preg_match('/^\//',$args['address']) ? $BF.substr($args['address'],1) : $BF.$args['address']) : $args['address']);
		$address = $addysrc;
		$display = (isset($args['display']) ? $args['display'] : '');
		$imgsrc = (isset($args['img']) && preg_match('/\//',$args['img']) ? $BF.$args['img'] : (isset($args['img']) ? $BF.'images/'.$args['img'] : ''));
		$img = (isset($args['img']) ? $imgsrc : '');
		$id = (isset($args['id']) ? $args['id'] : '');
		$extra = (isset($args['extra']) ? $args['extra'] : '');
		$title = (isset($args['title']) ? $args['title'] : $display);
	
		$class = (isset($args['class']) ? $args['class'] : '');
		$style = (isset($args['style']) ? $args['style'] : '');
		$imgclass = (isset($args['imgclass']) ? $args['imgclass'] : '');
		$imgstyle = (isset($args['imgstyle']) ? $args['imgstyle'] : '');
		
		if($img == '') { 
			return "<a href='".$address."' title='".$title."' ".($id!=''? " id='".$id."'":'').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'').">".$display."</a>";
		} else {
			preg_match('/(\w)*\.(\w)*$/',$img,$title);
			return "<a href='".$address."'".($id!=''? " id='".$id."'":'').($display!=''? " title='".$title."'" : '').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'')."><img src='".$img."' alt='".$title[0]."'".($display!=''? " title='".$display."'" :'').($imgclass!=''? " class='".$imgclass."'" :'').($imgstyle!=''? " style='".$imgstyle."'" :'')." /></a>";
		}
	} else {
		return '<script type="text/javascript">alert("No Arguments were supplied to the Linkto function");</script>';
	}
}

#############################################################################################
## New Function for images
## function img Example:
## img(
##			 'src' => 'edit.png',							// Adds in an image for you defaulting to ROOT/images/
##															//  the address, it will look for the address in the ROOT/address folder.
##															//  Ex:  address'=>' myprofile/edit.png  -->  ROOT/myprofile/edit.png
##			 'alt' => 'Display Name',						// Displays this text when mouse is hovered over field 
##			 'id' => 'Field ID',							// ID of field for JS, (ie. chrFirst)
##			 'class' => 'Field Class',						// Class for the hyperlink
##			 'style' => 'CSS Style',						// Extra Styles for the hyperlink
##			 'extra' => 'Extra Code, Javascript, etc'		// For additional JS, to Disable Field, or any additional options can be entered here.
##			);
####################################################################
function img($args) {
	if(is_array($args)) { 
		global $BF;

		$imgsrc = (preg_match('/\//',$args['src']) ? $BF.$args['src'] : $BF.'images/'.$args['src']);
		$src = $imgsrc;
		if(isset($args['alt'])) {
			$alt = $args['alt'];
		} else {
			preg_match('/(?!(.*\/)).*(?=(\.\w*$))/',$args['src'],$title);
			$alt = $title[0];
		}
		$id = (isset($args['id']) ? $args['id'] : '');
		$extra = (isset($args['extra']) ? $args['extra'] : '');
	
		$class = (isset($args['class']) ? $args['class'] : '');
		$style = (isset($args['style']) ? $args['style'] : '');
		
		return "<img src='".$src."' alt='".$alt."' title='".$alt."'".($id!=''? " id='".$id."'":'').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'')." />";
	} else {
		return '<script type="text/javascript">alert("No Arguments were supplied to the Img function");</script>';
	}
}
	
####################################################################
## function form_button Example:
## form_button(array(
##			 'type' => 'button OR submit',					// Default is button, For Submit Field use submit
##			 'title' => 'Roll Over Title',					// Displays this text when mouse is hovered over button 
##			 'value' => encode(Field Value),			// Value of the button, be sure to encode this
##			 'name' => 'Field Name',						// Name of button for Post, (ie. submit)
##			 'id' => 'Field ID',							// ID of button for JS, (ie. submit)
##			 'class' => 'Field Class',						// Class of Text Field
##			 'style' => 'CSS Style',						// Extra Styles for Text Box
##			 'extra' => 'Extra Code, Javascript, etc',		// For additional JS, to Disable Field, or any additional options can be entered here.
##			));
####################################################################
function form_button($args) {
	if(is_array($args)) { 
			
		$name = (isset($args['name']) ? $args['name'] : '');
		$value = (isset($args['value']) ? $args['value'] : '');
		$title = (isset($args['title']) ? $args['title'] : $value);
		$id = (isset($args['id']) ? $args['id'] : $name);
		
		$class = (isset($args['class']) ? $args['class'] : '');
		$style = (isset($args['style']) ? $args['style'] : '');
		
		$extra = (isset($args['extra']) ? $args['extra'] : '');
		
		return "<span><input type='".(isset($args['type'])?$args['type']:'button')."' ".($name!=''? " name='".$name."'":'').($id!=''? " id='".$id."'":'').($title!=''? " title='".$title."'" :'').($value!=''? " value='".$value."'" :'').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'')." /></span>";
	} else {
		return '<script type="text/javascript">var name=\''.$name.'\';if(name==\'\') { alert("No Arguments were supplied to the button"); }</script>';
	}
}


#############################################################################################
# New Function for the info and error messages
#   Call messages to add in the errors div for JS and for the Info/Error php messages
#   CSS included for portability
#############################################################################################
if (!isset($_SESSION['infoMessages'])) { $_SESSION['infoMessages'] = array(); }
if (!isset($_SESSION['errorMessages'])) { $_SESSION['errorMessages'] = array(); }
function messages() {
	if(isset($_SESSION['infoMessages']) && count($_SESSION['infoMessages'])) { 
		foreach($_SESSION['infoMessages'] as $v) {
			?><table class='infMessage' cellpadding='0' cellspacing='0'><tr><td class='icon'><!-- Icon --></td><td class='msg'><?=$v?></td></tr></table><?
		}
		unset($_SESSION['infoMessages']);
	}
	if(isset($_SESSION['errorMessages']) && count($_SESSION['errorMessages'])) { 
		foreach($_SESSION['errorMessages'] as $v) {
			?><table class='errMessage' cellpadding='0' cellspacing='0'><tr><td class='icon'><!-- Icon --></td><td class='msg'><?=$v?></td></tr></table><?
		}
		unset($_SESSION['errorMessages']);
	}
?><div id='errors'></div>
<?
}
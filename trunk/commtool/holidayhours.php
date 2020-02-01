<?php
//  DO NOT EDIT
	$BF='../';
	$auth_not_required = true;
	require($BF. '_lib.php');

	// Before we show the page do we have some Session Store Information?
	if (!isset($_SESSION['idStore']) || $_SESSION['idStore'] == "" || $_SESSION['idStore'] == 0) {
		$_SESSION['refer'] = $_SERVER['REQUEST_URI'];
		header('Location: '.$BF.'stores.php');
		die();
	}	
	// Checks to see if we have a id and it is Numeric
	(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) ? ErrorPage("commtool/index.php","Invalid Page") : "");
	$info = database_query("SELECT chrRFLName,txtInstructions FROM RFLEmails WHERE !bDeleted ".(isset($_REQUEST['bPreview']) && $_REQUEST['bPreview']==1 ? "": " AND bVisable ")." AND ID=". $_REQUEST['id'],"getting email info",1);
	($info['chrRFLName'] == "" ? ErrorPage("commtool/index.php","Invalid Page") : "");
	include($BF. "includes/meta.php");
	//dtn: THis is the mail Mime includes
	$er = error_reporting(0); 		//dtn: This is added in so that we don't get spammed with PEAR::isError() messages in our tail -f ..
	include_once('Mail.php');		//dtn: This is the main mail addon so that we can use the mime emailer
	include_once('Mail/mime.php');	//dtn: This is the actual mime part of the emailer	

	/*  This is the query to get the distro list for the current e-mail.*/
	$tmp =split( '/', $_SERVER[SCRIPT_FILENAME]);
	
	$pgnam = $tmp[count($tmp)-1];

	$query ="SELECT chrEmail
			FROM RFLContacts
			JOIN RFLDistros ON RFLDistros.idRFLContact = RFLContacts.ID
			JOIN RFLEmails ON RFLDistros.idRFLEmail = RFLEmails.ID
			WHERE RFLEmails.ID=".$_REQUEST['id']." AND !RFLContacts.bDeleted AND !RFLDistros.bDeleted";	
	$results = database_query($query, 'Get distro list');
	
         $list='';
         while ($row = mysqli_fetch_assoc($results))
          {
           		$list .=$row['chrEmail'].", ";
          }
       $list=substr($list, 0, strlen($list)-2);	
	

	if(count($_POST)) { // When doing isset, use a required field.  Faster than the php count funtion.
// END DO NOT EDIT
		
		// Build the message here, Any field that you have listed in OUTPUT TO SCREEN section must be in here as well 
		// Copy and paste an exsiting line and change the name in the $_SESSION brackets to match the name of the field in section OUTPUT TO SCREEN
		// i.e. --- <br /><br />Store Name:  ".encode($_SESSION['chrStoreName'])."  --- "Store Name" would change to what the field represents 
		//   and change "chrStoreName" to name of field in sections below

		$msg = "Store Number:||| ".encode($_SESSION['chrStoreNum']).":::
		<br /><br />Store Name:||| ".encode($_SESSION['chrStoreName']).":::
		<br /><br />Thursday Regular Store Hours:||| ".encode($_POST['chrThurs']).":::
		<br /><br />Thursday Tax Holiday Store Hours:||| ".encode($_POST['chrThrt']).":::
		<br /><br />Friday Regular Store Hours:||| ".encode($_POST['chrFrid']).":::
		<br /><br />Friday Tax Holiday Store Hours:||| ".encode($_POST['chrFrit']).":::
		<br /><br />Saturday Regular Store Hours:||| ".encode($_POST['chrSat']).":::
		<br /><br />Saturday Tax Holiday Store Hours:||| ".encode($_POST['chrSatt']).":::
		<br /><br />Sunday Regular Store Hours:||| ".encode($_POST['chrSun']).":::
		<br /><br />Sunday Tax Holiday Store Hours:||| ".encode($_POST['chrSunt']).":::";
// DO NOT EDIT
		$msg = encode($msg);
		
		$tmp = database_query("SELECT chrRFLName FROM RFLEmails WHERE ID=".$_REQUEST['id'],"Getting E-mail Subject",1);
// END DO NOT EDIT
		
		// You may add any additional information to $addsubject to include it into the subject line of the e-mail.  To use a field from OUTPUT TO SCREEN or above, 
		//   you can concat the line like the following example:  $addsubject = "Possible Text " . $_SESSION['chrFieldName'] . " More Text";
		//	 Make sure you change the information to match what you would like to be seen.
		
		$addsubject = "";
		
// DO NOT EDIT
		$subject = decode($tmp['chrRFLName']); //dtn: Subject goes here.  Decode it from the DB to make sure we get rid of any apostrophies or quotes

		include($BF. "commtool/includes/email_include.php");

	}
	/*dtn:  This is where any javascript should go */
?>
	<script language="JavaScript" src="../includes/forms.js"></script>
	
	<script language="javascript">
		function error_check() {
			if(total != 0) { reset_errors(); } 

			var total=0;

			total += ErrorCheck('chrStoreNumber', "You must enter a Store Number.");
			total += ErrorCheck('chrStore', "You must enter a Store Name.");
			if (document.getElementById('chrCC').value != '') { 
				total += ErrorCheck("chrCC","You must enter a vaild Apple Email address","apple_email");
			}
			total += ErrorCheck('chrThurs', "You must answer the question to submit this template.");
			total += ErrorCheck('chrThrt', "You must answer the question to submit this template.");
			total += ErrorCheck('chrFrid', "You must answer the question to submit this template.");
			total += ErrorCheck('chrFrit', "You must answer the question to submit this template.");
			total += ErrorCheck('chrSat', "You must answer the question to submit this template.");
			total += ErrorCheck('chrSatt', "You must answer the question to submit this template.");
			total += ErrorCheck('chrSun', "You must answer the question to submit this template.");
			total += ErrorCheck('chrSunt', "You must answer the question to submit this template.");

			if(total == 0) { document.getElementById('idForm').submit(); }
		}
	</script>
	
<?
	include($BF. "includes/top.php");
	include($BF. 'includes/noticemail.php');
	include($BF. "commtool/includes/nav.php");
	include($BF. "commtool/includes/top_email.php"); //Table started here, 3 columns wide.

// END DO NOT EDIT
// Below is the OUTPUT TO SCREEN Section, there are 3 columns, The following example would give you two more text fields.

/*
					
*/
// OUTPUT TO SCREEN
?>
					<tr>
						<td class="left">
							<div class='FormName'>Thursday Regular Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrThurs" id="chrThurs" rows="10" wrap="virtual"></textarea></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Thursday Tax Holiday Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrThrt" id="chrThrt" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>	
						<td class="left">
							<div class='FormName'>Friday Regular Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFrid" id="chrFrid" rows="10" wrap="virtual"></textarea></div>
						</td>
					<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Friday Tax Holiday Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFrit" id="chrFrit" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Saturday Regular Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSat" id="chrSat" rows="10" wrap="virtual"></textarea></div>
						</td>
					<td class="gutter"></td>	
						<td class="right">
							<div class='FormName'>Saturday Tax Holiday Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSatt" id="chrSatt" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>	
						<td class="left">
							<div class='FormName'>Sunday Regular Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSun" id="chrSun" rows="10" wrap="virtual"></textarea></div>
						</td>
					<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Sunday Tax Holiday Store Hours<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSunt" id="chrSunt" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
<?
// END OUTPUT TO SCREEN

// DO NOT EDIT BELOW THIS LINE
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>

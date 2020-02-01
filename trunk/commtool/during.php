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
		<br /><br />What store(s) did you visit today?:||| ".encode($_POST['chrVisit']).":::
		<br /><br />What are you hearing from customers and what are the most frequent questions being asked?:||| ".encode($_POST['chrHear']).":::
		<br /><br />How is the process for selling iPhones (first come, first served) working? What key learnings have you had about the sales process?:||| ".encode($_POST['chrServ']).":::
		<br /><br />Are customers buying and leaving or are they staying around? If they're staying around, how long are they staying and what are they doing once they've made their purchase?:||| ".encode($_POST['chrPurch']).":::
		<br /><br />What questions has the team been getting that they have had difficulty answering?:||| ".encode($_POST['chrDiff']).":::
		<br /><br />What are customers most excited about (key WOW factors)?:||| ".encode($_POST['chrWow']).":::
		<br /><br />What percentage (approximately) of customers are signing up for workshops?:||| ".encode($_POST['chrWork']).":::
		<br /><br />Approximately how many iPhone have been sold?:||| ".encode($_POST['chrSold']).":::
		<br /><br />Please share any key learnings or best practices.:||| ".encode($_POST['chrBest']).":::";
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
// END DO NOT EDIT
	// Here you may add error checking for a required field Please use the following example and paste it in the section marked ERROR CHECK
	// total += ErrorCheck('chrFIELDNAME', "Message you would like to be displayed.");
	
// ERROR CHECK
		total += ErrorCheck('chrVisit', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrHear', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrServ', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrPurch', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrDiff', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrWow', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrWork', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrSold', "You must answer the question to submit this survey.");
		total += ErrorCheck('chrBest', "You must answer the question to submit this survey.");
// END ERROR CHECK

// DO NOT EDIT
			if(total == 0) { document.getElementById('idForm').submit(); }
		}

	</script>
<?	include($BF. "includes/top.php");
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
						<td colspan="3">
							<div class='FormName'>What store(s) did you visit today?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrVisit" id="chrVisit" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div class='FormName'>What are you hearing from customers and what are the most frequent questions being asked?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrHear" id="chrHear" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>	
					<tr>	
						<td colspan="3">
							<div class='FormName'>How is the process for selling iPhones (first come, first served) working? What key learnings have you had about the sales process?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrServ" id="chrServ" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>	
					<tr>	
						<td colspan="3">
							<div class='FormName'>Are customers buying and leaving or are they staying around? If they're staying around, how long are they staying and what are they doing once they've made their purchase?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrPurch" id="chrPurch" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>	
					<tr>	
						<td colspan="3">
							<div class='FormName'>What questions has the team been getting that they have had difficulty answering?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrDiff" id="chrDiff" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>	
					<tr>	
						<td colspan="3">
							<div class='FormName'>What are customers most excited about (key WOW factors)?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrWow" id="chrWow" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>	
					<tr>	
						<td colspan="3">
							<div class='FormName'>What percentage (approximately) of customers are signing up for workshops?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrWork" id="chrWork" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>	
						<td colspan="3">
							<div class='FormName'>Approximately how many iPhone have been sold?<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrSold" id="chrSold" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>	
						<td colspan="3">
							<div class='FormName'>Please share any key learnings or best practices.<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrBest" id="chrBest" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
<?
// END OUTPUT TO SCREEN

// DO NOT EDIT BELOW THIS LINE
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>

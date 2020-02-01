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
		<br /><br />Employee Information- Employee Number:||| ".encode($_POST['chrOne']).":::
		<br /><br />Employee Information- First Name:||| ".encode($_POST['chrTwo']).":::
		<br /><br />Employee Information- Last Name:||| ".encode($_POST['chrThree']).":::
		<br /><br />Employee Information- Phone Number:||| ".encode($_POST['chrFour']).":::
		<br /><br />Employee Information- Comments:||| ".encode($_POST['chrFive']).":::
		<br /><br />Referral Information- First Name:||| ".encode($_POST['chrSix']).":::
		<br /><br />Referral Information- Preferred First name:||| ".encode($_POST['chrSeven']).":::
		<br /><br />Referral Information- Last Name:||| ".encode($_POST['chrEight']).":::
		<br /><br />Referral Information- Email Address:||| ".encode($_POST['chrNine']).":::
		<br /><br />Referral Information- Phone Number:||| ".encode($_POST['chrTen']).":::
		<br /><br />Referral Information- Address 1:||| ".encode($_POST['chrEleven']).":::
		<br /><br />Referral Information- Address 2:||| ".encode($_POST['chrTwelve']).":::
		<br /><br />Referral Information- City:||| ".encode($_POST['chrThirt']).":::
		<br /><br />Referral Information- State:||| ".encode($_POST['chrFourt']).":::
		<br /><br />Referral Information- Zip Code:||| ".encode($_POST['chrFift']).":::
		<br /><br />Referral Information- Current Employer:||| ".encode($_POST['chrSixt']).":::
		<br /><br />Referral Information- Current Job Title:||| ".encode($_POST['chrSevt']).":::
		<br /><br />Referral Information- Position Referring:||| ".encode($_POST['chrEigt']).":::";
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
			total += ErrorCheck('chrOne', "You must enter Employee ID NUmber.");
			total += ErrorCheck('chrTwo', "You must enter a First Name.");
			total += ErrorCheck('chrThree', "You must enter a Last Name.");
			total += ErrorCheck('chrFour', "You must enter a Phone Number.");
			total += ErrorCheck('chrFive', "You must enter Comments.");
			total += ErrorCheck('chrSix', "You must enter a First Name.");
			total += ErrorCheck('chrEight', "You must enter a Last Name.");
			total += ErrorCheck('chrNine', "You must enter an Email Address.");
			total += ErrorCheck('chrTen', "You must enter a Phone Number.");
			total += ErrorCheck('chrEleven', "You must enter an Address.");
			total += ErrorCheck('chrThirt', "You must enter a City.");
			total += ErrorCheck('chrFourt', "You must enter a State.");
			total += ErrorCheck('chrFift', "You must enter a Zip Code.");
			
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
							<div class='FormName'>Employee ID Number <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrOne" id="chrOne" maxlength="30" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Employee First Name <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwo" id="chrTwo" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Employee Last Name <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrThree" id="chrThree" maxlength="30" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Employee Phone Number <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFour" id="chrFour" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Employee Comments <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFive" id="chrFive" maxlength="100" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Referral First Name <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSix" id="chrSix" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Referral Preferred First Name <span class='FormRequired'>(Optional)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSeven" id="chrSeven" maxlength="30" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Referral Last Name <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrEight" id="chrEight" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Referral Email Address <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrNine" id="chrNine" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Referral Phone Number <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTen" id="chrTen" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Referral Address 1 <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrEleven" id="chrEleven" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Referral Address 2 <span class='FormRequired'>(Optional)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwelve" id="chrTwelve" maxlength="50" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>City <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrThirt" id="chrThirt" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>State <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFourt" id="chrFourt" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Zip Code <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFift" id="chrFift" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Current Employer <span class='FormRequired'>(Optional)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSixt" id="chrSixt" maxlength="30" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Current Job Title <span class='FormRequired'>(Optional)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSevt" id="chrSevt" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Position Referring <span class='FormRequired'>(Optional)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrEigt" id="chrEigt" maxlength="30" /></div>
						</td>
					</tr>
				
<?
// END OUTPUT TO SCREEN

// DO NOT EDIT BELOW THIS LINE
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>

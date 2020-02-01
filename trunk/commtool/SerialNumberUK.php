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
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrOne']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwo']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrThree']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrFour']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrFive']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrSix']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrSeven']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrEight']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrNine']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTen']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrEleven']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwelve']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrThirt']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrFourt']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrFift']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrSixt']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrSevt']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrEigt']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrNint']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwen']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTweno']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwent']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTwenh']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwentf']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTwenff']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwens']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTwenv']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTwene']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTwenn']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTrty']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTrto']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTrtt']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTrth']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTrtf']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTrtz']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTrts']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTrtv']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrTrte']).":::
		<br /><br />iPhone Serial Number:||| ".encode($_POST['chrTrtn']).":::
		<br /><br />iPhone SIM ICCID Number:||| ".encode($_POST['chrFrty']).":::";
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
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrOne" id="chrOne" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwo" id="chrTwo" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrThree" id="chrThree" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFour" id="chrFour" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFive" id="chrFive" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSix" id="chrSix" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSeven" id="chrSeven" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrEight" id="chrEight" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrNine" id="chrNine" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTen" id="chrTen" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrEleven" id="chrEleven" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwelve" id="chrTwelve" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrThirt" id="chrThirt" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFourt" id="chrFourt" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFift" id="chrFift" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSixt" id="chrSixt" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSevt" id="chrSevt" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrEigt" id="chrEigt" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrNint" id="chrNint" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwen" id="chrTwen" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTweno" id="chrTweno" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwent" id="chrTwent" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwenh" id="chrTwenh" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwentf" id="chrTwentf" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwenff" id="chrTwenff" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwens" id="chrTwens" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwenv" id="chrTwenv" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwene" id="chrTwene" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTwenn" id="chrTwenn" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrty" id="chrTrty" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrto" id="chrTrto" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrtt" id="chrTrtt" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrth" id="chrTrth" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrtf" id="chrTrtf" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrtz" id="chrTrtz" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrts" id="chrTrts" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrtv" id="chrTrtv" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrte" id="chrTrte" maxlength="11" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>iPhone SIM ICCID Number<span class='FormRequired'>(Twenty Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTrtn" id="chrTrtn" maxlength="20" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>iPhone Serial Number<span class='FormRequired'>(Eleven Characters)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrFrty" id="chrFrty" maxlength="11" /></div>
						</td>
					</tr>
<?
// END OUTPUT TO SCREEN

// DO NOT EDIT BELOW THIS LINE
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>

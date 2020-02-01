<?php
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
//	$er = error_reporting(0); 		//dtn: This is added in so that we don't get spammed with PEAR::isError() messages in our tail -f ..
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
		
		//dtn:  Build the message here

		$msg = "Store Number:||| ".encode($_SESSION['chrStoreNum']).":::
		<br /><br />Store Name:||| ".encode($_SESSION['chrStoreName']).":::
		<br /><br />Requester's Name:||| ".encode($_POST['chrClaim']).":::
		<br /><br />Event Date:||| ".encode($_POST['chrSerial']).":::
		<br /><br />Event:||| ".encode($_POST['chrRebate']).":::
		<br /><br />T-Shirt Size:||| ".encode($_POST['chrCustName']).":::
		<br /><br />Contact:||| ".encode($_POST['chrTelephone']).":::
		<br /><br />Delivery Address:||| ".encode($_POST['chrAddress']).":::
		<br /><br />Venue- Where will the table be set up?:||| ".$_POST['bBelow'].":::
		<br /><br />Employers- How many are expected at this event?:||| ".$_POST['bBelowA'].":::
		<br /><br />Expected Turnout:||| ".encode($_POST['chrTurnout']).":::
		<br /><br />Target Audience- Specify Position:||| ".encode($_POST['Notes']).":::";
		
		$msg = encode($msg);

		$tmp = database_query("SELECT chrRFLName FROM RFLEmails WHERE ID=".$_REQUEST['id'],"Getting E-mail Subject",1);

		$subject = decode($tmp['chrRFLName']." - ".$_SESSION['chrStoreNum']." - ".$_SESSION['chrStoreName']); //dtn: Subject goes here.  Decode it from the DB to make sure we get rid of any apostrophies or quotes
		include($BF. "commtool/includes/email_include.php");

	}	

	/*dtn:  This is where any javascript should go */
?>
	<script language="JavaScript" src="../includes/forms.js"></script>

	<script language="javascript">
		function error_check() {
			if(total != 0) { reset_errors(); } 

			var total=0;

			//total += ErrorCheck('chrPart', "You must enter a Part.");

			total += ErrorCheck('chrStoreNumber', "You must enter a Store Number.");
			total += ErrorCheck('chrStore', "You must enter a Store Name.");

			total += ErrorCheck('chrRebate', "You must enter the type of event.");
			total += ErrorCheck('chrCustName', "You must enter T-Shirt Size.");
			total += ErrorCheck('chrTelephone', "You must enter a Contact name.");
			total += ErrorCheck('Notes', "You must specify the target audience.");


			if (document.getElementById('chrClaim').value == "" && document.getElementById('chrSerial').value == "") {
				total += ErrorCheck('chrClaim', "You must enter the Requestor's Name.");
				total += ErrorCheck('chrSerial', "You must enter the event date.");
			}

		if(total == 0) { document.getElementById('idForm').submit(); }

		}

	</script>
	
	
<?	include($BF. "includes/top.php");
	include($BF. 'includes/noticemail.php');
	include($BF. "commtool/includes/nav.php");
	include($BF. "commtool/includes/top_email.php"); ?>
	
					<tr>
						<td class="left">
							<div class='FormName'>Requester's Name <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrClaim" id="chrClaim" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Event Date<span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrSerial" id="chrSerial" maxlength="50" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Event <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrRebate" id="chrRebate" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>T-Shirt Size <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrCustName" id="chrCustName" maxlength="50" /></div>
						</td>
					</tr>					
					<tr>
						<td class="left">
							<div class='FormName'>Contact <span class='FormRequired'>(Required)</span></div> 
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTelephone" id="chrTelephone" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">					
							<div class='FormName'>Delivery Address <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress" id="chrAddress" maxlength="50" /></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Venue- Where will the table be set up?<span class='FormRequired'>(Required)</span></div> 
							<div class='FormField' id="bBelow"><input type="radio" name="bBelow" id="bBelowYes" value="In Store" /> In Store &nbsp; <input type="radio" name="bBelow" id="bBelowNo" value="Off-site" /> Off-site</div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Employers- How many are expected at this event?<span class='FormRequired'>(Required)</span></div> 
							<div class='FormField' id="bBelowA"><input type="radio" name="bBelowA" id="bBelowYes" value="Apple Retail Only" /> Apple Retail Only &nbsp; <input type="radio" name="bBelowA" id="bBelowNo" value="Job Fair (Other Employers Present)" /> Job Fair (Other Employers Present)</div>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Expected Turnout <span class='FormRequired'>(Required)</span></div> 
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTurnout" id="chrTurnout" maxlength="50" /></div>
						</td>
					</tr>
					<tr>	
						<td colspan="3">
							<div class='FormName'>Target Audience- Specify Position <span class='FormRequired'>(Required) (You can enter other notes in this box)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="Notes" id="Notes" rows="20" wrap="virtual"></textarea></div>
						</td>
					</tr>

<?
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>

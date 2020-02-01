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
		
		//dtn:  Build the message here

		$msg = "Store Number:||| ".encode($_SESSION['chrStoreNum']).":::
		<br /><br />Store Name:||| ".encode($_SESSION['chrStoreName']).":::
		<br /><br />Check Number:||| ".encode($_POST['chrCheckNum']).":::
		<br /><br />Check Amount:||| ".encode($_POST['chrCheckAmount']).":::
		<br /><br />Transaction Number:||| ".encode($_POST['chrTransNum']).":::
		<br /><br />Transaction Date:||| ".date('m/d/Y', strtotime($_POST['dTransaction'])).":::
		<br /><br />Customer Name:||| ".encode($_POST['chrCustName']).":::
		<br /><br />Is this check BELOW $250.00?||| ".$_POST['bBelow'].":::
		<br /><br />Customer address in POS transaction:||| ".nl2br(encode($_POST['txtPOSAddy'])).":::
		<br /><br />CORRECTED Customer address:||| ".nl2br(encode($_POST['txtCorrectAddy'])).":::
		<br /><br />Notes:||| ".nl2br(encode($_POST['Notes'])).":::";
		$msg = encode($msg);

		$tmp = database_query("SELECT chrRFLName FROM RFLEmails WHERE ID=".$_REQUEST['id'],"Getting E-mail Subject",1);

		$subject = decode($tmp['chrRFLName'] .", Customer: ". encode($_POST['chrCustName'])); //dtn: Subject goes here.  Decode it from the DB to make sure we get rid of any apostrophies or quotes

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
			total += ErrorCheck('chrTransNum', "You must enter a Transaction Number.");
			total += ErrorCheck('chrCheckAmount', "You must enter a Check Amount.");
			total += ErrorCheck('chrCustName', "You must enter a Customer Name.");
			if (document.getElementById('bBelowYes').checked == false && document.getElementById('bBelowNo').checked == false) {
			
				total ++;
				document.getElementById('errors').innerHTML += "<div class='ErrorMessage'>You must indicate whether the check is below $250 or not.</div>";
				document.getElementById('bBelow').style.background = "#FFDFE6"; 
			
			}
			if(total == 0) { document.getElementById('idForm').submit(); }
		}

	</script>
<?	include($BF. "includes/top.php");
	include($BF. 'includes/noticemail.php');
	include($BF. "commtool/includes/nav.php");
	include($BF. "commtool/includes/top_email.php"); //Table started here, 3 columns wide. ?>

					<tr>
						<td class="left">
							<div class='FormName'>Check Number <span class='FormRequired'>(If Available)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrCheckNum" id="chrCheckNum" maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Transaction Number <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrTransNum" id="chrTransNum" maxlength="50" ></div>
						</td>
					</tr>
					<tr>
						<td class="left">
							<div class='FormName'>Check Amount <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrCheckAmount" id="chrCheckAmount"  maxlength="50" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Transaction Date <span class='FormRequired'>(Use Format: MM/DD/YYYY)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="dTransaction" id="dTransaction"  maxlength="15" /></div>
						</td>
					</tr>
					<tr>
						<td>
							<div class='FormName'>Customer Name <span class='FormRequired'>(Required)</span></div> 
							<div class='FormField'><input class="FormTextBox" type="text" name="chrCustName" id="chrCustName" maxlength="80" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Is this check BELOW $250.00? <span class='FormRequired'>(Required)</span></div> 
							<div class='FormField' id="bBelow"><input type="radio" name="bBelow" id="bBelowYes" value="Yes" /> Yes &nbsp; <input type="radio" name="bBelow" id="bBelowNo" value="No" /> No</div>
						</td>						
					</tr>
					<tr>
						<td>
							<div class='FormName'>Customer address in POS transaction</div> 
							<div class='FormField'><textarea class="FormTextArea" name="txtPOSAddy" id="txtPOSAddy" rows="10" wrap="virtual"></textarea></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>CORRECTED Customer address</div> 
							<div class='FormField'><textarea class="FormTextArea" name="txtCorrectAddy" id="txtCorrectAddy" rows="10" wrap="virtual"></textarea></div>
						</td>						
					</tr>
					<tr>
						<td colspan="3">
							<div class='FormName'>Notes <span class='FormRequired'>(You can enter other notes in this box)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="Notes" id="Notes" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
<?
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>

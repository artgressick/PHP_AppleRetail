<?php	$BF='../';	$auth_not_required = true;	require($BF. '_lib.php');	// Before we show the page do we have some Session Store Information?	if (!isset($_SESSION['idStore']) || $_SESSION['idStore'] == "" || $_SESSION['idStore'] == 0) {		$_SESSION['refer'] = $_SERVER['REQUEST_URI'];		header('Location: '.$BF.'stores.php');		die();	}		// Checks to see if we have a id and it is Numeric	(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) ? ErrorPage("commtool/index.php","Invalid Page") : "");	$info = database_query("SELECT chrRFLName,txtInstructions FROM RFLEmails WHERE !bDeleted ".(isset($_REQUEST['bPreview']) && $_REQUEST['bPreview']==1 ? "": " AND bVisable ")." AND ID=". $_REQUEST['id'],"getting email info",1);	($info['chrRFLName'] == "" ? ErrorPage("commtool/index.php","Invalid Page") : "");	include($BF. "includes/meta.php");	//dtn: THis is the mail Mime includes	$er = error_reporting(0); 		//dtn: This is added in so that we don't get spammed with PEAR::isError() messages in our tail -f ..	include_once('Mail.php');		//dtn: This is the main mail addon so that we can use the mime emailer	include_once('Mail/mime.php');	//dtn: This is the actual mime part of the emailer		/*  This is the query to get the distro list for the current e-mail.*/	$tmp =split( '/', $_SERVER[SCRIPT_FILENAME]);		$pgnam = $tmp[count($tmp)-1];	$query ="SELECT chrEmail			FROM RFLContacts			JOIN RFLDistros ON RFLDistros.idRFLContact = RFLContacts.ID			JOIN RFLEmails ON RFLDistros.idRFLEmail = RFLEmails.ID			WHERE RFLEmails.ID=".$_REQUEST['id']." AND !RFLContacts.bDeleted AND !RFLDistros.bDeleted";		$results = database_query($query, 'Get distro list');	         $list='';         while ($row = mysqli_fetch_assoc($results))          {           		$list .=$row['chrEmail'].", ";          }       $list=substr($list, 0, strlen($list)-2);			if(count($_POST)) { // When doing isset, use a required field.  Faster than the php count funtion.				//dtn:  Build the message here		$msg = "Store Number:||| ".encode($_SESSION['chrStoreNum']).":::		<br /><br />Store Name:||| ".encode($_SESSION['chrStoreName']).":::		<br /><br />Customer Name:||| ".encode($_POST['chrCustName']).":::		<br /><br />Discovery Information:||| ".nl2br(encode($_POST['txtCorrectAddy'])).":::		<br /><br />Quantity Requested:||| ".nl2br(encode($_POST['txtPOSAddy'])).":::		<br /><br />Payment Method:||| ".$_POST['bBelow'].":::		<br /><br />Comments:||| ".nl2br(encode($_POST['Notes'])).":::";		$msg = encode($msg);		$tmp = database_query("SELECT chrRFLName FROM RFLEmails WHERE ID=".$_REQUEST['id'],"Getting E-mail Subject",1);		$subject = decode($tmp['chrRFLName'] .", Customer: ". encode($_POST['chrCustName'])); //dtn: Subject goes here.  Decode it from the DB to make sure we get rid of any apostrophies or quotes		include($BF. "commtool/includes/email_include.php");	}	/*dtn:  This is where any javascript should go */?>	<script language="JavaScript" src="../includes/forms.js"></script>	<script language="javascript">		function error_check() {			if(total != 0) { reset_errors(); } 			var total=0;			total += ErrorCheck('chrStoreNumber', "You must enter a Store Number.");			total += ErrorCheck('chrStore', "You must enter a Store Name.");			if (document.getElementById('chrCC').value != '') { 				total += ErrorCheck("chrCC","You must enter a vaild Apple Email address","apple_email");			}			total += ErrorCheck('chrCustName', "You must enter a Customer Name.");			total += ErrorCheck('txtCorrectAddy', "You must enter Discovery Information.");			total += ErrorCheck('txtPOSAddy', "You must enter the Quantity Requested.");			if (document.getElementById('bBelowYes').checked == false && document.getElementById('bBelowNo').checked == false && document.getElementById('bBelowMaybe').checked == false) {							total ++;				document.getElementById('errors').innerHTML += "<div class='ErrorMessage'>You must enter the payment method.</div>";				document.getElementById('bBelow').style.background = "#FFDFE6"; 						}			if(total == 0) { document.getElementById('idForm').submit(); }		}	</script><?	include($BF. "includes/top.php");	include($BF. 'includes/noticemail.php');	include($BF. "commtool/includes/nav.php");	include($BF. "commtool/includes/top_email.php"); //Table started here, 3 columns wide. ?>					<tr>						<td colspan="3">							<div class='FormName'>Customer Name <span class='FormRequired'>(Required)</span></div> 							<div class='FormField'><input class="FormTextBox" type="text" name="chrCustName" id="chrCustName" maxlength="80" /></div>						</td>                 	</tr>						<td colspan="3">							<div class='FormName'>Discovery Information <span class='FormRequired'></span>(Required)</div> 							<div class='FormField'><textarea class="FormTextArea" name="txtCorrectAddy" id="txtCorrectAddy" rows="20" wrap="virtual"></textarea></div>						</td>											</tr>					<tr>						<td colspan="3">							<div class='FormName'>Quantity Requested <span class='FormRequired'></span>(Required)</div>  							<div class='FormField'><textarea class="FormTextArea" name="txtPOSAddy" id="txtPOSAddy" rows="10" wrap="virtual"></textarea></div>						</td>					</tr>                    <tr>                        <td colspan="3">							<div class='FormName'>Payment Method<span class='FormRequired'>(Required)</span></div> 							<div class='FormField' id="bBelow"><input type="radio" name="bBelow" id="bBelowYes" value="Credit/Debit Card" /> Credit/Debit Card &nbsp; <input type="radio" name="bBelow" id="bBelowNo" value="Purchase Order" /> Purchase Order &nbsp; <input type="radio" name="bBelow" id="bBelowMaybe" value="Bank Transfer" /> Bank Transfer</div>						</td>					</tr>                    <tr>						<td colspan="3">							<div class='FormName'>Comments <span class='FormRequired'>(You can enter other notes in this box)</span></div>							<div class='FormField'><textarea class="FormTextArea" name="Notes" id="Notes" rows="10" wrap="virtual"></textarea></div>						</td>					</tr><?	include($BF. "commtool/includes/bottom_email.php");	include($BF. "commtool/includes/bottom.php");?>
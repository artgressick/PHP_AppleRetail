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
		<br /><br />NFR Product being replaced:||| ".$_POST['bBelow'].":::
		<br /><br />Number of Copie(s) Requested:||| ".encode($_POST['chrCopies']).":::
		<br /><br />Name of the Employee(s) Who Will Receive the Product:||| ".encode($_POST['chrEmployee']).":::
		<br /><br />Comments:||| ".encode($_POST['Notes']).":::";
				$msg = encode($msg);

		$tmp = database_query("SELECT chrRFLName FROM RFLEmails WHERE ID=".$_REQUEST['id'],"Getting E-mail Subject",1);

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
			if (document.getElementById('bBelowYes').checked == false && document.getElementById('bBelowNo').checked == false && document.getElementById('bBelowMaybe').checked == false) {
			
				total ++;
				document.getElementById('errors').innerHTML += "<div class='ErrorMessage'>You must indicate the NFR Product being replaced.</div>";
				document.getElementById('bBelow').style.background = "#FFDFE6"; 
			
			}
			total += ErrorCheck('chrCopies', "You must enter the number of copies requested.");
			total += ErrorCheck('chrEmployee', "You must enter an Employeee Name.");

			if(total == 0) { document.getElementById('idForm').submit(); }
		}

	</script>
<?	include($BF. "includes/top.php");
	include($BF. 'includes/noticemail.php');
	include($BF. "commtool/includes/nav.php");
	include($BF. "commtool/includes/top_email.php"); //Table started here, 3 columns wide. ?>
					<tr>
						<td class="left">
							<div class='FormName'>NFR Product being replaced<span class='FormRequired'>(Required)</span></div> 
							<div class='FormField' id="bBelow"><input type="radio" name="bBelow" id="bBelowYes" value="iLife '08" /> iLife '08 &nbsp; <input type="radio" name="bBelow" id="bBelowNo" value="iWork '08" /> iWork '08</div>
						</td>
						<td class="gutter"></td>
						<td class="right"></td>
							<div class='FormName'>Number of Copie(s) Requested <span class='FormRequired'>(Required)</span></div> 
							<div class='FormField'><input class="FormTextBox" type="text" name="chrCopies" id="chrCopies" maxlength="50" /></div>
						</td>
					</tr>
										<tr>
						<td colspan="3">
							<div class='FormName'>Name of the Employee(s) Who Will Receive the Product <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="chrEmployee" id="chrEmployee" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div class='FormName'>Comments <span class='FormRequired'>(You can enter other notes in this box)</span></div>
							<div class='FormField'><textarea class="FormTextArea" name="Notes" id="Notes" rows="10" wrap="virtual"></textarea></div>
						</td>
					</tr>


<?
	include($BF. "commtool/includes/bottom_email.php");
	include($BF. "commtool/includes/bottom.php");
?>
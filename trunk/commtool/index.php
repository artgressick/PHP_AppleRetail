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
	
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "commtool/includes/nav.php");
	
	// Grab the Landing Page
	
	$LandingPage = database_query("SELECT txtHTML FROM LandingPage WHERE ID=1","Get Landing Page",1);
	
?>

	<form enctype="multipart/form-data" action = "" method = "post" id = "idForm">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
              		<td align="left" background="<?=$BF?>images/ebuilder_topbg.gif"><img src="<?=$BF?>images/ebuilder_topleft.gif" width="9" height="55" /></td>
              		<td width="100%" align="center" valign="top" background="<?=$BF?>images/ebuilder_topbg.gif"><div style="margin-top:3px; font-size:12px;">Welcome to Escalator</div></td>
              		<td align="right" background="<?=$BF?>images/ebuilder_topbg.gif"><img src="<?=$BF?>images/ebuilder_topright.gif" width="9" height="55" /></td>
            	</tr>
            	<tr>
              		<td colspan="3" class="emailformaddress">
		<!-- This section is needed for the rest of the pages to keep intact -->
				</td>
			 </tr>
			 <tr>
				<td colspan="3" class="emailbody">
		<!-- This is the end of the section that is needed for the rest of the pages to keep intact -->
 				<?=decode($LandingPage['txtHTML'])?>
          	</td>
       	</tr>
  	</table>
  	</form>
<?
	include($BF. "commtool/includes/bottom.php");
?>

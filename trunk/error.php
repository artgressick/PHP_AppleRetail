<?php
	$BF='';
	$auth_not_required = true;
	require($BF. '_lib.php');
	if(isset($_POST['submit'])) {
		if($_POST['lnkHome'] == "") {
			$link = $BF."index.php";		
		} else {
			$link = $BF.$_POST['lnkHome'];	
		}
		header('Location: '.$link);
		die();
	}
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	if(isset($_SESSION['lnkHome'])) {
		$linkHome = $_SESSION['lnkHome'];
		unset($_SESSION['lnkHome']);
	} else { $linkHome = ""; }
?>
			<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
                <tr>
                    <td width="100%">
						<form id='idForm' name='idForm' method='post' action=''>
						<div style="text-align:center; font-size:14px;"><strong>An Error as occured! This is usually due to missing or incomplete information.  Please try again.</strong></div>
<?
	if(isset($_SESSION['chrErrorMsg'])) {
?>
						<div style="text-align:center; font-size:12px; padding-top:20px;"><strong>Error Details:</strong> <?=$_SESSION['chrErrorMsg']?></div>
<?
		unset($_SESSION['chrErrorMsg']);
	}
?>
						<div style="text-align:center; padding-top:20px;"><input type="button" id="back" name="back" value="Back" onclick="javascript: history.go(-1)" />&nbsp;&nbsp;&nbsp;<input type="submit" id="submit" name="submit" value="Home" /></div>
						<input type="hidden" id="lnkHome" name="lnkHome" value="<?=$linkHome?>">
						</form>
					</td>
                </tr>
            </table>
<?
	include($BF. "includes/bottom.php");
?>
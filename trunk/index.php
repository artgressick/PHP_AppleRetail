<?php

	$BF='';
	$auth_not_required = true;
	require($BF. '_lib.php');

// Before we show the page do we have some Session Store Information?
if (!isset($_SESSION['idStore']) || !is_numeric($_SESSION['idStore']) || $_SESSION['idStore'] == 0) {
	$_SESSION['refer'] = $_SERVER['REQUEST_URI'];
	header('Location: '.$BF.'stores.php');
	die();
}	
	
	include($BF. "includes/meta.php");

	$q = "SELECT txtContent FROM CMSPages WHERE ID=1";
	$info = database_query($q, "Getting Front Page", 1);

	include($BF. "includes/top.php");
?>

			<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
                <tr>
                    <td width="100%">
						<div><?=decode($info['txtContent'])?></div></td>
                </tr>
            </table>



<?
	include($BF. "includes/bottom.php");
?>
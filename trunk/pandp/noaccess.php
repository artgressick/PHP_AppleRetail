<?php
	$BF='../';
	$auth_not_required = true;
	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
	
	include($BF. "includes/top.php");
?>

			<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
                <tr>
                    <td width="100%">
						<div>Sorry<?=(isset($_SESSION['chrFirst']) ? " ".$_SESSION['chrFirst'] : "")?>, however you do not have access to the P and P admin section.  If you feel this is a error please contact the administrator.</div>
					</td>
                </tr>
            </table>

<?
	include($BF. "includes/bottom.php");
?>
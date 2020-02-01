<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?=(isset($title) && $title != '' ? $title .' - ' : '')?>Retail Ops</title>
		<link href="<?=$BF?>includes/global.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' language="JavaScript" src="<?=$BF?>includes/nav.js"></script>
		<script type='text/javascript'>var BF = '<?=$BF?>';</script>
<?		# If the "Stuff in the Header" function exists, then call it
		if(function_exists('sith')) { sith(); } 
?>
	</head>
	<body onload='dropdown.startnav("nav");<?=(isset($bodyParams) ? $bodyParams : '')?>'>
<?
		// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // This is to display the SESSION variables, unrem to use
?>
		<table width="964" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
			<tr>
				<td width="964" height="9" colspan="3"><?=img(array('src'=>'border-top.gif'))?></td>
			</tr>
			<tr>
				<td width="4" style="background: url(<?=$BF?>images/border-left.gif) repeat-y;"><?=img(array('src'=>'border-left.gif'))?></td>
				<td width="956">
					<div align="center">
						<table width="924" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=img(array('src'=>'topbanner.gif'))?></td>
							</tr>
							<tr>
								<td>
									<table width="924" border="0" cellspacing="0" cellpadding="0" style='white-space: nowrap;'>
										<tr>
											<td bgcolor="#A2BF67"><?=img(array('src'=>'nav_left_off.gif'))?></td>
											<td bgcolor="#A2BF67" style='vertical-align: middle;'>
												<div class="navstyle" id="nav">
													<ul>
														<li style='margin: 0;'><?=linkto(array('address'=>'/index.php','display'=>'Store Ops Home','style'=>'border-left:0;'))?></li>
														<li><?=linkto(array('address'=>'/commtool/','display'=>'Escalator'))?></li>
														<li><?=linkto(array('address'=>'/pandp/','display'=>'P &amp; P'))?></li>
														<li><?=linkto(array('address'=>'/storehours/','display'=>'Store Hours'))?></li>
														<!--<li><?=linkto(array('address'=>'/iphone/','display'=>'iPhone Registration'))?></li>-->
														<!--<li><?=linkto(array('address'=>'#','display'=>'NSO & Remodel','id'=>'id-dropmenu4'))?></li>-->
														<!--<li><?=linkto(array('address'=>'#','display'=>'NSO & Remodels','id'=>'id-dropmenu5','extra'=>'rel="dropmenu5"'))?></li>-->
													</ul>
												</div>
											</td>
											<td style='background: #A2BF67; text-align: right; width: 100%; padding-right: 10px;'>
												<div class="navstyle" id="nav2">
													<ul>
	<?
						if (isset($_SESSION['idUser']) || isset($_SESSION['idStore'])) {
							$border = "";
	?>
														<li><?=linkto(array('address'=>'/storehours/?logout=1','display'=>'Log-out','style'=>'border-left: 0;'))?></li>
	<?
						} else { $border = "border-left: 0;"; }
	?>
														<li><?=linkto(array('address'=>'/storehours/admin/','display'=>'Admin Section'))?></li>
													</ul>
												</div>
	
											</td>
											<td align="right" bgcolor="#A2BF67" style='width: 1cm;'><?=img(array('src'=>'nav_right_off.gif'))?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<div style='margin: 10px; text-align:left;'>
						<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
							<tr>
								<td width="196" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td height="24" style="background: url(<?=$BF?>images/email_bottomfooter.gif); text-align:center; font-size: 12px; font-weight:bold;">Administration</td>
										</tr>
										<tr>
											<td style="background: url(<?=$BF?>images/email_background.gif);">
												<div class='open'>
													<div class="email_admin_msg"><?=linkto(array('address'=>'/storehours/admin/','display'=>'Holidays'))?></div>
													<div class="email_admin_msg"><?=linkto(array('address'=>'/storehours/admin/storehours/','display'=>'Store Hours'))?></div>
													<div class="email_admin_msg"><?=linkto(array('address'=>'/storehours/admin/storeholidayhours/','display'=>'Store Holiday Hours'))?></div>
													<div class="email_admin_msg"><?=linkto(array('address'=>'/storehours/admin/storehours/excel_export.php','display'=>'Export Store Hours'))?></div>
												</div>
											</td>
										</tr>
										<tr>
											<td><?=img(array('src'=>'email_bottomfooter.gif'))?></td>
										</tr>
									</table>

								</td>
								<td width="25"><!-- SPACER --></td>
								<td width="703" valign="top">
									<div class='header2'><?=$header_title?></div>
									<div class='innerbody'>
<?	if(!isset($custom_directions)) { ?>
										<div class='instructions'><?=$directions?></div>
										<?=messages()?>
<?	} ?>
<!-- Begin code -->
<?
	# This is where we will put in the code for the page.
	(isset($sitm) && $sitm != '' && function_exists($sitm) ? $sitm() : sitm());
?>
<!-- End code -->
									</div>
								</td>
							</tr>
						</table>	
					</div>
				</td>
				<td width="4" style="background: url(<?=$BF?>images/border-right.gif) repeat-y;"><?=img(array('src'=>'border-right.gif'))?></td>
			</tr>
			<tr>
				<td width="964" height="17" colspan="3"><?=img(array('src'=>'border-bottom.gif'))?></td>
			</tr>
		</table>
		<table width="964" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="50%" style="padding-left: 5px; padding-top:5px;" valign="top">
					<div style="padding-bottom:3px;">Copyright &copy; <?=date('Y')?> Apple Inc. All rights reserved.</div>
					<div>Maintained by Retail Store Operations</div>
				</td>
				<td width="50%" align="right" style="padding-right:5px; padding-top:5px;"></td>
			</tr>
		</table>
<?
	# Any aditional things can go down here including javascript or hidden variables
	# "Stuff on the Bottom"
	if(function_exists('sotb')) { sotb(); } 
?>
	</body>
</html>

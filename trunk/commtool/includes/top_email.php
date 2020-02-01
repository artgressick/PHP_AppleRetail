<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        	<tr>
          		<td align="left" background="<?=$BF?>images/ebuilder_topbg.gif"><img src="<?=$BF?>images/ebuilder_topleft.gif" width="9" height="55" /></td>
          		<td width="100%" align="center" valign="top" background="<?=$BF?>images/ebuilder_topbg.gif"><div style="margin-top:6px; font-size:12px;"><?=$info['chrRFLName']?></div></td>
          		<td align="right" background="<?=$BF?>images/ebuilder_topbg.gif"><img src="<?=$BF?>images/ebuilder_topright.gif" width="9" height="55" /></td>
        	</tr>
        	<tr>
          		<td colspan="3" class="emailformaddress"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              	<td class='mockEmailLabel'>To:</td>
              	<td class='mockEmailForm'><div class='outer'><div class='inner'>Distro List<?=($_SESSION['chrStoreName'] != "" ? ", ".$_SESSION['chrStoreName'] : "")?></div></div></td>
            </tr>
            <tr>
              	<td class='mockEmailLabel'>CC:</td>
              	<td class='mockEmailForm'><input name="chrCC" id="chrCC" type="text" size="75" /></td>
            </tr>
            <tr>
              	<td class='mockEmailLabel' style="padding-bottom:8px;">Subject:</td>
              	<td class='mockEmailForm' style="padding-bottom:8px;"><div class='outer'><div class='inner'><?=$info['chrRFLName']?></div></div></td>
            </tr>
        </table>

	<!-- This section is needed for the rest of the pages to keep intact -->
	</td>
</tr>
<tr>
	<td colspan = "3" class = "greenEmailInstructions"><?=$info['txtInstructions']?></td>
</tr>
<tr>
	<td colspan="3" class="emailbody">
			<div class='innerbody'>
			<!-- This is the main page form -->
				<div id="errors"></div>
				<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
					<tr>
						<td class="left">
							<div class='FormName'>Store Number <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrStoreNumber" id="chrStoreNumber" maxlength="50" disabled="disabled" value="<?=$_SESSION['chrStoreNum']?>" /></div>
						</td>
						<td class="gutter"></td>
						<td class="right">
							<div class='FormName'>Store Name <span class='FormRequired'>(Required)</span></div>
							<div class='FormField'><input class="FormTextBox" type="text" name="chrStore" id="chrStore" maxlength="50" disabled="disabled" value="<?=$_SESSION['chrStoreName']?>" /></div>
						</td>
					</tr>			

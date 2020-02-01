<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bCommtool must be set to true, else show noaccess.php page
		if ($Security['bCommtool'] == false) {
				header("Location: " . $BF . "commtool/noaccess.php");
				die();
		}	
	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name, this is not for sorting but for formatting
	include($BF. 'components/list/sortList.php'); 

	if (!isset($_REQUEST['idGeo']) || $_REQUEST['idGeo'] == "") { $_REQUEST['idGeo'] = "%"; }

	$q = "SELECT RFLEmails.ID as ID, RFLEmails.chrRFLName as chrName, RFLCategories.chrEmailCategory as chrCategory, EmailMessages.idStatus, Geos.chrGeo
		  FROM EmailMessages
		  JOIN RFLEmails ON EmailMessages.idType=RFLEmails.ID
		  JOIN RFLCategories ON RFLEmails.idRFLCategory=RFLCategories.ID
		  JOIN Geos ON RFLEmails.idGeo=Geos.ID
		  WHERE !RFLEmails.bDeleted AND !RFLCategories.bDeleted AND RFLEmails.idGeo LIKE '". $_REQUEST['idGeo'] ."'
		  ORDER BY chrGeo, chrCategory, chrName, idStatus
		 ";

	$results = database_query($q,"Getting Results");
	
		$emails = array();
	while ($row = mysqli_fetch_assoc($results)) {
		if (!isset($emails[$row['ID']])) {
			$emails[$row['ID']] = array();
			$emails[$row['ID']]['chrCategory'] = $row['chrCategory'];
			$emails[$row['ID']]['chrName'] = $row['chrName'];
			$emails[$row['ID']]['chrGeo'] = $row['chrGeo'];
			$emails[$row['ID']][1] = 0;
			$emails[$row['ID']][2] = 0;
			$emails[$row['ID']][3] = 0;
		}
	 $emails[$row['ID']][$row['idStatus']] += 1;
	}

	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
?>
	<div class='listHeader'>Email Totals</div>
	<div class='greenFilter' style="text-align:right;">Filter by Geo: 
<?
			$Geos = database_query("SELECT ID, chrGeo FROM Geos ORDER BY chrGeo","Getting Geos");
?>
		<select id="idGeo" name='idGeo' onchange='location.href="index.php?idGeo="+this.value'>
			<option value="%"<?=($_REQUEST['idGeo'] == "%" ? ' selected="selected"' : "" )?>>Show All</option>
		<? while ($row = mysqli_fetch_assoc($Geos)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idGeo'] ? ' selected="selected"' : "" )?>><?=$row['chrGeo']?></option>
		<?	} ?>
		</select>
	
	</div>

	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="500" height="300" id="chartId">
		<param name="allowScriptAccess" value="always" />
		<param name="movie" value="<?=$BF?>commtool/includes/ScrollStackedColumn2D.swf"/>		
		<param name="FlashVars" value="&chartWidth=710&chartHeight=300&dataURL=makeallxml.php?filter=<?=($_REQUEST['idGeo'] == '%' ? '' : $_REQUEST['idGeo'])?>" />
		<param name="quality" value="high" />
		<embed src="<?=$BF?>commtool/includes/ScrollStackedColumn2D.swf" FlashVars="&chartWidth=710&chartHeight=300&dataURL=makeallxml.php?filter=<?=($_REQUEST['idGeo'] == '%' ? '' : $_REQUEST['idGeo'])?>" quality="high" width="710" height="300" name="chartId" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>


	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<th>Email Type</th>
			<th style="text-align:right;">Open</th>
			<th style="text-align:right;">Pending</th>
			<th style="text-align:right;">Closed</th>
		</tr>
<?
	$count=0;
	$totalOpen=0;
	$totalPending=0;
	$totalClosed=0;
	$cat = "";

	foreach ($emails as $k => $v) {
	$totalOpen += $emails[$k][1];
	$totalPending += $emails[$k][2];
	$totalClosed += $emails[$k][3];	
	
	$link = "search.php?id=".$k;
	
	if ($cat != $emails[$k]['chrCategory']) {
?>
			<tr>
				<td colspan='4' style="background-color: #A2BF67; color:#FFFFFF; height:15px; font-weight:bold;<?=($count>1?' border-top:#999 1px solid;':"")?>"><?=$emails[$k]['chrCategory']?></td>
			</tr>
<?	
		$cat = $emails[$k]['chrCategory'];
	}
?>
			<tr id='tr<?=$k?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$k?>");' onmouseout='UnRowHighlight("tr<?=$k?>");'>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><span style="padding-left:15px;"><?=$emails[$k]['chrName']?> (<?=$emails[$k]['chrGeo']?>)</span></td>
				<td style='cursor: pointer; text-align:right;' onclick='location.href="<?=$link?>";'><?=number_format($emails[$k][1])?></td>
				<td style='cursor: pointer; text-align:right;' onclick='location.href="<?=$link?>";'><?=number_format($emails[$k][2])?></td>
				<td style='cursor: pointer; text-align:right;' onclick='location.href="<?=$link?>";'><?=number_format($emails[$k][3])?></td>
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='4' height="20">No Emails to display</td>
			</tr>
<?	} else { ?>

			<tr style="background-color: #FFF; height:20px; font-weight:bold;<?=($count>1?' border-top:#999 1px solid;':"")?>">
				<td style="font-weight:bold;border-top:#999 1px solid;">Totals</td>
				<td style='text-align:right;font-weight:bold;border-top:#999 1px solid;'><?=number_format($totalOpen)?></td>
				<td style='text-align:right;font-weight:bold;border-top:#999 1px solid;'><?=number_format($totalPending)?></td>
				<td style='text-align:right;font-weight:bold;border-top:#999 1px solid;'><?=number_format($totalClosed)?></td>
			</tr>
<? } ?>
	</table>
<?
	include($BF. "commtool/includes/bottom.php");
?>

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
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "dtStamp"; } //dtn: This sets the default column order.  Asc by default.
	
	$where = "";
	$filterset=false;
	
	if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") { $where .= " AND RFLEmails.ID=".$_REQUEST['id']; $filterset=true; } else { $_REQUEST['id'] = ""; }
	if (isset($_REQUEST['chrSearch']) && $_REQUEST['chrSearch'] != "") { $where .= " AND EmailMessages.txtMessage LIKE '%".encode($_REQUEST['chrSearch'])."%'"; $filterset=true; } else { $_REQUEST['chrSearch'] = ""; }
	if (isset($_REQUEST['dDate']) && $_REQUEST['dDate'] != "") { $where .= " AND EmailMessages.dtStamp BETWEEN adddate(now(),".$_REQUEST['dDate'].") AND now()"; $filterset=true; } else { $_REQUEST['dDate'] = ""; }
	if (isset($_REQUEST['idStatus']) && $_REQUEST['idStatus'] != "") { $where .= " AND EmailMessages.idStatus=".$_REQUEST['idStatus']; $filterset=true; } else { $_REQUEST['idStatus'] = ""; }
	if (isset($_REQUEST['idStore']) && $_REQUEST['idStore'] != "") { $where .= " AND EmailMessages.idSender=".$_REQUEST['idStore']; $filterset=true; } else { $_REQUEST['idStore'] = ""; }
	if (isset($_REQUEST['idGeo']) && $_REQUEST['idGeo'] != "") { $where .= " AND RFLEmails.idGeo=". $_REQUEST['idGeo']; $filterset=true; } else { $_REQUEST['idGeo'] = ""; }

	if ($filterset == true) {
	
		$q = "SELECT EmailMessages.ID as ID, RFLEmails.chrRFLName as chrName, RFLCategories.chrEmailCategory as chrCategory, EmailStatus.chrStatus, Geos.chrGeo, RetailStores.chrStoreNum, 
				RetailStores.chrName AS chrStore, EmailMessages.dtStamp, EmailMessages.txtMessage
			  FROM EmailMessages
			  JOIN EmailStatus ON EmailMessages.idStatus=EmailStatus.ID
			  JOIN RetailStores ON EmailMessages.idSender=RetailStores.ID
			  JOIN RFLEmails ON EmailMessages.idType=RFLEmails.ID
			  JOIN RFLCategories ON RFLEmails.idRFLCategory=RFLCategories.ID
			  JOIN Geos ON RFLEmails.idGeo=Geos.ID
			  WHERE !RFLEmails.bDeleted AND !RFLCategories.bDeleted". $where ."
			  ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	
		$results = database_query($q,"Getting Results");
		$info = mysqli_fetch_assoc($results);
		mysqli_data_seek($results,0);
		$_SESSION['queryForExcel'] = $q;
	}

	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
?>

	<table style='width: 100%;'>
		<tr>
			<td><div class='listHeader'>Email Totals</div></td>
			<td style='text-align: right;'>(<a href='search-excel.php'>Export to Excel</a>)</td>
		</tr>
	</table>
	<form id="form1" name="form1" method="post" action="">
	<div class='greenFilter' style="padding:5px; text-align:right;">
		 Filter By: 
<?
			$Types = database_query("SELECT RFLEmails.ID, chrRFLName as chrName, chrGeo FROM RFLEmails JOIN Geos ON RFLEmails.idGeo=Geos.ID ORDER BY chrGeo, chrName","Getting Types");
?>
		<select id="id" name='id' style="width:75px;">
			<option value=""<?=($_REQUEST['id'] == "" ? ' selected="selected"' : "" )?>>Type</option>
		<? while ($row = mysqli_fetch_assoc($Types)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['id'] ? ' selected="selected"' : "" )?>><?=$row['chrName']?> (<?=$row['chrGeo']?>)</option>
		<?	} ?>
		</select> 
		<select id="dDate" name='dDate' style="width:75px;">
			<option value=""<?=($_REQUEST['dDate'] == "" ? ' selected="selected"' : "" )?>>Date</option>
			<option value="-30"<?=($_REQUEST['dDate'] == "-30" ? ' selected="selected"' : "" )?>>Last 30 Days</option>
			<option value="-60"<?=($_REQUEST['dDate'] == "-60" ? ' selected="selected"' : "" )?>>Last 60 Days</option>
			<option value="-90"<?=($_REQUEST['dDate'] == "-90" ? ' selected="selected"' : "" )?>>Last 90 Days</option>
		</select>
<?
			$Status = database_query("SELECT ID, chrStatus FROM EmailStatus ORDER BY ID","Getting Status");
?>
		<select id="idStatus" name='idStatus' style="width:75px;">
			<option value=""<?=($_REQUEST['idStatus'] == "" ? ' selected="selected"' : "" )?>>Status</option>
		<? while ($row = mysqli_fetch_assoc($Status)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idStatus'] ? ' selected="selected"' : "" )?>><?=$row['chrStatus']?></option>
		<?	} ?>
		</select> 
<?
			$Stores = database_query("SELECT ID, chrName FROM RetailStores ORDER BY chrName","Getting Stores");
?>
		<select id="idStore" name='idStore' style="width:75px;">
			<option value=""<?=($_REQUEST['idStore'] == "" ? ' selected="selected"' : "" )?>>Store</option>
		<? while ($row = mysqli_fetch_assoc($Stores)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idStore'] ? ' selected="selected"' : "" )?>><?=$row['chrName']?></option>
		<?	} ?>
		</select>
<?
			$Geos = database_query("SELECT ID, chrGeo FROM Geos ORDER BY chrGeo","Getting Geos");
?>
		<select id="idGeo" name='idGeo' style="width:75px;">
			<option value=""<?=($_REQUEST['idGeo'] == "" ? ' selected="selected"' : "" )?>>Geo</option>
		<? while ($row = mysqli_fetch_assoc($Geos)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idGeo'] ? ' selected="selected"' : "" )?>><?=$row['chrGeo']?></option>
		<?	} ?>
		</select>
		Search: 
		<input type="text" id="chrSearch" name="chrSearch" style="width:75px;" value="<?=encode($_REQUEST['chrSearch'])?>" />
		<input type="submit" name="submit" id="submit" value="GO" />
	</div>
	<div style="display:none;"></form></div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
<? 
			$requests = 'id='.$_REQUEST['id'].'&chrSearch='.$_REQUEST['chrSearch'].'&dDate='.$_REQUEST['dDate'].'&idStatus='.$_REQUEST['idStatus'].'&idStore='.$_REQUEST['idStore'].'&idGeo='.$_REQUEST['idGeo'];
			
			sortList('Store Num', 'chrStoreNum', '', $requests );
			sortList('Store Name', 'chrStore', '', $requests);
			sortList('Email Type', 'chrName', '', $requests);
			sortList('Geo', 'chrGeo', '', $requests);
			sortList('Date Sent', 'dtStamp', '', $requests);
			sortList('Status', 'chrStatus', '', $requests);
			
?>
		</tr>
<? $count=0;	
if ($filterset == true) {
	while ($row = mysqli_fetch_assoc($results)) { 
	$link = "emaildetails.php?id=".$row['ID'];
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrStoreNum']?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrStore']?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrName']?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrGeo']?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=date('n/j/Y g:i a',strtotime($row['dtStamp']))?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrStatus']?></td>
			</tr>
<?	} 
}
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='6' height="20">No Emails to display, Please check that you have entered at least one item to filter by.</td>
			</tr>
<?	} ?>

	</table>

	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="500" height="300" id="chartId">
		<param name="allowScriptAccess" value="always" />
		<param name="movie" value="<?=$BF?>commtool/includes/ScrollStackedColumn2D.swf"/>		
		<param name="FlashVars" value="&chartWidth=710&chartHeight=300&dataURL=makexml.php?params=<?=$_REQUEST['id']?>||<?=urlencode($info['chrName'])?>" />
		<param name="quality" value="high" />
		<embed src="<?=$BF?>commtool/includes/ScrollStackedColumn2D.swf" FlashVars="&chartWidth=710&chartHeight=300&dataURL=makexml.php?params=<?=$_REQUEST['id']?>||<?=urlencode($info['chrName'])?>" quality="high" width="710" height="300" name="chartId" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
<?
	include($BF. "commtool/includes/bottom.php");
?>

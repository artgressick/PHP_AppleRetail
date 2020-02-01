<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bPandP must be set to true, else show noaccess.php page
		if ($Security['bPandP'] == false) {
				header("Location: " . $BF . "pandp/noaccess.php");
				die();
		}
		
	if (!isset($_REQUEST['idGeo']) && !isset($_SESSION['idAdminGeo']) || !is_numeric($_REQUEST['idGeo'])) { 
		$_REQUEST['idGeo'] = 1;
	} else if(!isset($_REQUEST['idGeo']) && isset($_SESSION['idAdminGeo'])) {
		$_REQUEST['idGeo'] = $_SESSION['idAdminGeo'];
	}
	
	$_SESSION['idAdminGeo'] = $_REQUEST['idGeo'];	
	include($BF. 'components/list/sortList.php'); 

	if (count($_POST)) {

		if ($_POST['bParent'] == 1) {
			$set = database_query("
				UPDATE PNPPages 
				SET bPVisable='".$_POST['bVisable']."',
				idUser='".$_SESSION['idUser']."'
				WHERE idParent=".$_POST['id'],"Updating Parent bPVisable Value");
		}	

			$set = database_query("
				UPDATE PNPPages 
				SET bVisable='".$_POST['bVisable']."',
				idUser='".$_SESSION['idUser']."'
				WHERE ID=".$_POST['id'],"Updating bVisable Value");
			
			$q = "INSERT INTO Audit SET 
				idType=2, 
				idRecord='". $_POST['id'] ."',
				chrColumnName='bVisable',
				txtNewValue='". ($_POST['bVisable'] == 1 ? "Visible" : "Not Visible")  ."',
				txtOldValue='". ($_POST['bVisable'] == 1 ? "Not Visible" : "Visible")  ."',
				dtDateTime=now(),
				chrTableName='PNPPages',
				idUser='". $_SESSION['idUser'] ."'
			";
			database_query($q,"Insert audit");

	}


	
	$query = "SELECT PNPPages.ID, chrTitle, bParent, chrGeo, bVisable, bPVisable, dtCreated, dtUpdated, chrFirst, chrLast, idCUser,
			(SELECT CONCAT(chrFirst,' ',chrLast) AS chrUser FROM Users WHERE ID=idCUser) AS chrCUser
	FROM PNPPages
	LEFT JOIN Geos ON PNPPages.idGeo=Geos.ID
	JOIN Users ON PNPPages.idUser=Users.ID
	WHERE !PNPPages.bDeleted AND idGeo='".$_REQUEST['idGeo']."' 
	ORDER BY dOrder,!bParent,dOrderChild,chrTitle";
	
	$results = database_query($query, 'Getting Pages');
	
	$q = "SELECT * FROM Geos ORDER BY ID";
	$geos = database_query($q, "Getting all Geos");
	
	
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/overlays.js"></script>
<script type='text/javascript'>

function showMagicBox(obj,id, msg) {
	var x = findPosX(obj);
	var y = findPosY(obj);	
	document.getElementById("magicBoxMsg"+id).style.top = (y + document.getElementById("magicBox"+id).offsetHeight)+"px";
	document.getElementById("magicBoxMsg"+id).style.left = (x)+"px";
	document.getElementById("magicBoxMsg"+id).style.display = "block";
//	document.getElementById("magicBox").innerHTML = msg;
}
function hideMagicBox(id) {
	document.getElementById("magicBoxMsg"+id).style.display = "none";
//	document.getElementById("magicBox").style.display = "none";
}

function findPosX(obj) {
    var curleft = 0;
    if(obj.offsetParent) {
        while(1) {
            curleft += obj.offsetLeft;
            if(!obj.offsetParent)
            	break;
            obj = obj.offsetParent;
        }
    } else if(obj.x) {
        curleft += obj.x;
	}
    return curleft;
}

function findPosY(obj) {
    var curtop = 0;
    if(obj.offsetParent) {
        while(1) {
            curtop += obj.offsetTop;
            if(!obj.offsetParent)
            	break;
            obj = obj.offsetParent;
        }
    } else if(obj.y) {
        curtop += obj.y;
    }
    return curtop;
    
}

</script>

<?	
	include($BF. "includes/top.php");
	include($BF. "pandp/includes/admin_nav.php"); 
	$TableName = "PNPPages"; //dtn:  This is the Database table that you will be setting the bDeleted statuses on.
	include($BF. 'includes/overlay.php');
?>

	<form name='idForm' id='idForm' action='' method="post">
	<div class='listHeader'>Policies & Procedure Pages</div>
	<div class='greenFilter'>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td style="text-align:left;"><input type='button' value='Add Parent' onclick='window.location.href="add.php?id=1"' /> <input type='button' value='Add Child' onclick='window.location.href="add.php?id=0"' /></td>
			<td style="text-align:right;">
				<select id="idGeo" name="idGeo" onchange='location.href="index.php?idGeo="+this.value'>
					<option value=''>Select Geo</option>
				<? while ($row = mysqli_fetch_assoc($geos)) { ?>
					<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idGeo'] ? ' selected="selected"' : "" )?>><?=$row['chrGeo']?></option>
				<?	} ?>
				</select>			
			</td>
		</tr>
	</table>
	</div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>	
			<th width="5" colspan="2"></th>
			<th colspan="2">Page Name</th>
			<th>Last Updated On</th>
			<th width="50">History</th>
			<th width="5"><img src="<?=$BF?>images/options.gif"></th>
		</tr>
		



<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = "edit.php?id=".$row['ID']; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td id='magicBox<?=$row['ID']?>' class='options'>
					<img id='info<?=$row['ID']?>' src='<?=$BF?>images/info.png' alt='Information' onmouseover="showMagicBox(this,<?=$row['ID']?>, '')" onmouseout="hideMagicBox(<?=$row['ID']?>)" />
				</td>
			
<?
		if (($row['bPVisable']==1 && $row['bParent']==0) || $row['bParent']==1) {
			$visablelink = "onclick=\"
							document.getElementById('id').value='" . $row['ID'] . "';
							document.getElementById('bParent').value='" . $row['bParent'] . "';
							document.getElementById('bVisable').value='" . ($row['bVisable'] == 1 ? "0" : "1") . "'; 
							document.getElementById('idForm').submit();\" style='cursor:pointer;'";
			} else { $visablelink = ""; } 
			
		if ( $row['bVisable'] == 1 ) { $imagename = "on"; } else { $imagename = "off"; }
		if ( $row['bPVisable']==0 && $row['bParent']==0 ) { $imagename = "parentoff"; }
?>
			
				<td class='options'>
					<img id='visiblebutton<?=$row['ID']?>' src='<?=$BF?>images/<?=$imagename?>.png' alt='Page is <?=($row['bVisable'] == 1 ? "" : "Not ")?>Visible' <?=$visablelink?> />
				</td>	
				<?
				if ($row['bParent'])
				{
				?>
					<td colspan = "2" style='cursor: pointer; font-weight: bold;' onclick='location.href="<?=$link?>";'><?=$row['chrTitle']?> (<?=$row['chrGeo']?>)</td>
				<?
				}
				else 
				{
				?>
					<td style = "width: 5px"></td><td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrTitle']?><div id='magicBoxMsg<?=$row['ID']?>' "style="display:none; float: left;"  class='magicBox'><?=$MagicBoxInfo?></div></td>
				<? 
				}
				?>
				<td><span style="font-size:10px;"><?=date('n/j/Y - g:i a',strtotime($row['dtUpdated']))?> by <?=$row['chrFirst']?> <?=$row['chrLast']?></span></td>
				<td><span style="font-size:10px;"><a href="<?=$BF?>pandp/admin/history.php?id=<?=$row['ID']?>">[ History ]</a></span></td>
				<td class='options'><?=deleteButton($row['ID'],$row['chrTitle'])?></td>	
			</tr>
<?
			$MagicBoxInfo = "<strong>Created on:</strong><br />&nbsp;&nbsp;".date('n/j/Y - g:i a',strtotime($row['dtCreated']))."<br /><br /><strong>Created By:</strong><br />&nbsp;&nbsp;".$row['chrCUser'];
?>
			<div id='magicBoxMsg<?=$row['ID']?>' "style="display:none;"  class='magicBox'><?=$MagicBoxInfo?></div>
<?
	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='6' height="20">No Pages To Display</td>
			</tr>
			
<?	} ?>
	</table>

	<input type="hidden" name="bVisable" id="bVisable" />
	<input type="hidden" name="id" id="id" />
	<input type="hidden" name="bParent" id="bParent" />
	<input type="hidden" name="idGeo" id="idGeo" value="<?=$_REQUEST['idGeo']?>">
	</td></tr></table></form>
	<?
	include($BF."includes/bottom.php");
?>
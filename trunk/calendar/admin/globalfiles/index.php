<?
	include('_controller.php');

	function sitm() { 
		global $BF,$info,$groups;
		
?>
	<form action="" method="post" id="idForm" enctype="multipart/form-data" onsubmit="return error_check()" style='padding-top:10px;'>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<table id='Files' cellspacing="0" cellpadding="5" style='width:100%;'>
						<tbody id="Filestbody">
							<tr>
								<td style='font-weight:bold;border-top:1px solid #CCC; border-left:1px solid #CCC;vertical-align:bottom;'>File 1:&nbsp;&nbsp;</td>
								<td id='Filesfile1' style='border-top:1px solid #CCC;vertical-align:bottom;'><input type='file' name='chrFilesFile1' id='chrFilesFile1' /></td>
								<td style='padding-left:10px; border-top:1px solid #CCC;'>Type:<br /><select name='chrFilesType1' id='chrFilesType1' onchange='shfilegroup(1,this.value);'><option value='doc'>Document</option><option value='pic'>Picture</option></select></td>
								<td style='padding-left:10px; border-top:1px solid #CCC;'><div id='group1'>Group:<br /><select name='chrFilesGroup1' id='chrFilesGroup1'><?=$groups?></select></div><!-- BLANK --></td>
								<td style='padding-left:10px; border-top:1px solid #CCC; border-right:1px solid #CCC;'>Title:<br /><input type='text' name='chrFilesTitle1' id='chrFilesTitle1' style='width:100%;'/>
							</tr>
							<tr>
								<td style='vertical-align:top;text-align:right; border-bottom:1px solid #CCC; border-left:1px solid #CCC;' colspan='2'>Description:</td>
								<td colspan='3' style='padding-left:10px; border-bottom:1px solid #CCC; border-right:1px solid #CCC;'><textarea name='txtFilesDesc1' id='txtFilesDesc1' cols='30' rows='2' style='width:100%;'></textarea></td>
							</tr>
						</tbody>
					</table>
					<div style='padding: 5px 10px;'><input type='button' onclick='javascript:newOption(2,"Files");' value='Add Another File' /></div>
					<input type='hidden' name='intFiles' id='intFiles' value='1' />
				</td>
			</tr>
		</table>
<?
		$q = "SELECT NSOs.ID, RetailStores.chrName AS chrStoreName, RetailStores.chrStoreNum, chrNSOType
				FROM NSOs
				JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
				JOIN RetailStores ON NSOs.idStore=RetailStores.ID
				WHERE !NSOs.bDeleted
				". (isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? ' AND NSOTypes.ID='.$_REQUEST['idNSOType'] : '') ."
				ORDER BY NSOs.dBegin,NSOs.chrCalendarEvent";
		$results = db_query($q,"getting NSOs");
		
		$cols = 2;
		$rows = ceil(mysqli_num_rows($results) / $cols);	
		$count=0;
?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td valign="top" width="<?=100/$cols?>%">
<?
		while ($row = mysqli_fetch_assoc($results)) {
				
			if($count >= $rows) {
				$count = 0;
?>
				</td>
				<td width="" valign="top">
<?
					}
?>
					<div style="white-space:nowrap;"><?=form_checkbox(array('name'=>'nsoid','array'=>'true','value'=>$row['ID'],'title'=>$row['chrStoreName'].' ('.$row['chrStoreNum'].') - '.$row['chrNSOType'],'checked'=>'true'))?></div>
<?					
				$count++;
			}
?>
				</td>
			</tr>
		</table>
		
		<div style='padding-top:10px;'>
			<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Upload'))?>
		</div>
	</form>
<?
	}
?>
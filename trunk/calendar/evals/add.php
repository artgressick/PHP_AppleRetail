<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$info;
		$tmp = mysqli_fetch_assoc($results);
		mysqli_data_seek($results,0);
?>
		<form method='post' action='' id='idForm' enctype="multipart/form-data" onsubmit="return error_check();">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%"><?=form_text(array('caption'=>'Store Name/Store Number','size'=>'30','name'=>'chrStoreName','value'=>$info['chrName'].' / '.$info['chrStoreNum'],'extra'=>'disabled="disabled"'))?></td>
					<td width="50%"><?=form_text(array('caption'=>'Completed By','name'=>'chrUser','size'=>'30','extra'=>'disabled="disabled"','value'=>$_SESSION['chrFirst'].' '.$_SESSION['chrLast']))?></td>
				</tr>
			</table>
<?
		$i = 1;
		$prevCat = '';
		while($row = mysqli_fetch_assoc($results)) {
			if($prevCat != $row['idEvalCat']) {
				$prevCat = $row['idEvalCat'];
?>
			<hr />		
			<div style="font-size:14px; font-weight:bold;"><?=$row['chrCat']?></div>
<?
			}
?>
			<div style='margin-top: 10px;'>
				<div style='font-weight: bold;'><?=$i++.". ".$row['chrQuestion'].($row['bRequired'] ? ' <span class="FormRequired">(Required)</span>' : '')?></div>
<?			
			if($row['idEvalType'] == 1) {
?>				<div style='padding-left:12px;padding-top:3px;'><input type='text' name='<?=$row['ID']?>' id='<?=$row['ID']?>' style='width: 400px;' /></div><?			
			} else if($row['idEvalType'] == 2) {
?>				<div style='padding-left:12px;padding-top:3px;'><textarea name='<?=$row['ID']?>' id='<?=$row['ID']?>' cols='100' rows='5'></textarea></div><?			
			} else if($row['idEvalType'] == 3) {
?>				<div style='padding-left:12px;padding-top:3px;'><select name='<?=$row['ID']?>' id='<?=$row['ID']?>'>
					<option value=''>-Select Answer-</option>
<?
				$tmp_options = explode('|||',$row['txtOptions']);
				$j = 0;
				foreach($tmp_options as $v) {
?>					<option value='<?=$j?>'><?=$v?></option><?
					$j++;
				}
?>						
				</select></div>
<?
			} else if($row['idEvalType'] == 4) {

				$tmp_options = explode('|||',$row['txtOptions']);
				$j = 0;
				foreach($tmp_options as $v) {
?>				<label for='<?=$row['ID']?>-<?=$j?>' style='padding-left: 10px;'><input type='checkbox' name='<?=$row['ID']?>[]' id='<?=$row['ID']?>-<?=$j?>' value='<?=$j?>' > <?=$v?></label><?
					$j++;
				}

			} else if($row['idEvalType'] == 5) {

				$tmp_options = explode('|||',$row['txtOptions']);
				$j = 0;
				foreach($tmp_options as $v) {
?>				<label for='<?=$row['ID']?>-<?=$j?>' style='padding-left: 10px;'><input type='radio' name='<?=$row['ID']?>[]' id='<?=$row['ID']?>-<?=$j?>' value='<?=$j?>' > <?=$v?></label><?
					$j++;
				}
			
			}
?>
			</div>				
			
<?		} ?>

			<hr />
			<div style="font-size:14px; font-weight:bold;">Upload Files</div>

			<table id='Files' cellspacing="0" cellpadding="0" style='margin-top: 10px;'>
				<tbody id="Filestbody">
				<tr>
					<td>File 1:&nbsp;&nbsp;</td>
					<td id='Filesfile1'><input type='file' name='chrFilesFile1' id='chrFilesFile1' /></td>
				</tr>
				</tbody>
			</table>
			<div style='padding: 5px 10px;'><a href='javascript:newOption(2,"Files");'>Add Another File</a></div>
			<input type='hidden' name='intFiles' id='intFiles' value='1' />
			

			<div style='margin-top: 10px;'> <input type='submit' value='Submit Information' /></div>
			<input type='hidden' name='key' id='key' value='<?=$_REQUEST['key']?>'>
			<input type='hidden' name='id' id='id' value='<?=$info['idEval']?>'>
		</form>

<?	} ?>
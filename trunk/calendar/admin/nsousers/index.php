<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$cols = 3;
		$rows = ceil(mysqli_num_rows($results) / $cols);	
		$count=0;
?>	
					<form action="" method="post" id="idForm" onsubmit="">
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
				if ($row['idInvolved'] != "") {
?>
							<div style="white-space:nowrap;background-color:#DDD;"><?=form_checkbox(array('name'=>'nsoinvolved','array'=>'true','value'=>$row['ID'],'title'=>$row['chrFirst'].' '.$row['chrLast'],'checked'=>'true'))?></div>
<?
				} else {
?>
							<div style="white-space:nowrap;"><?=form_checkbox(array('name'=>'nsoinvolved','array'=>'true','value'=>$row['ID'],'title'=>$row['chrFirst'].' '.$row['chrLast']))?></div>
<?					
				}
				$count++;
			}
?>
							</td>
						</tr>
					</table>
					<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save/Update'))?>
					</form>
<?	} ?>
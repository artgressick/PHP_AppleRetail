<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$directions;

		$filter_results = db_query("SELECT ID,chrNSOType as chrRecord FROM NSOTypes WHERE !bDeleted ORDER BY chrNSOType","Getting nso types");
?>
		<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
			<table cellspacing="0" cellpadding="0" class='instructions' style='width: 100%;'>
				<tr>
					<td><?=$directions?></td>
					<td style='text-align: right;'>
						<?=form_select($filter_results,array('nocaption'=>'true',
									'name'=>'idNSOType',
									'caption'=>'- NSO Types -',
									'value'=>$_REQUEST['idNSOType'],
									'extra'=>' onchange="window.location.href=\'?idNSOType=\'+this.value"'))?>
					</td>
				</tr>
			</table>

<?
		messages();
	if(isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType'])) {	
		$tableHeaders = array(
			'chrNSOTask'			=> array('displayName' => 'Task'),
			'chrNSOType'			=> array('displayName' => 'Event Type'),
			'intDateOffset'			=> array('displayName' => 'Day Offset')
		);
		if(access_check(21,3)) {
			$tableHeaders['opt_other'] = 'order';
		}
		if(access_check(21,4)) {
			$tableHeaders['opt_del'] = 'chrNSOTask';
		}
		
		
	} else {
		$tableHeaders = array(
			'chrNSOTask'			=> array('displayName' => 'Task'),
			'chrNSOType'			=> array('displayName' => 'Event Type'),
			'intDateOffset'			=> array('displayName' => 'Day Offset','default' => 'asc')
		);
		if(access_check(21,4)) {
			$tableHeaders['opt_del'] = 'chrNSOTask';
		}
	}
		
		sortList('NSOTasks',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(21,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
		if(isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType'])) {
		if(access_check(21,3)) {
?>
		<div class='FormButtons' style='padding:5px;'>
			<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save / Refresh Page'))?>
		</div>
<?	
		}
		}
?>
	</form>
<?
	} 
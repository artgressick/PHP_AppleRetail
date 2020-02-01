<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$directions;
	
	
		$_REQUEST['idStore'] = (!isset($_REQUEST['idStore']) ? '' : $_REQUEST['idStore']); 
		$filter_results = db_query("SELECT ID,chrName as chrRecord FROM RetailStores WHERE !bDeleted ORDER BY chrName","Getting Stores");
?>
			<table cellspacing="0" cellpadding="0" class='instructions' style='width: 100%;'>
				<tr>
					<td><?=$directions?></td>
					<td style='text-align: right;'>
						<?=form_select($filter_results,array('nocaption'=>'true',
									'name'=>'idStore',
									'caption'=>'- All Stores -',
									'value'=>$_REQUEST['idStore'],
									'extra'=>' onchange="window.location.href=\'?idStore=\'+this.value"'))?>
					</td>
				</tr>
			</table>

<?
		messages();

		$tableHeaders = array(
			'chrFileTitle'		=> array('displayName' => 'Document Name','default' => 'asc'),
			'chrNSOFileGroup'	=> array('displayName' => 'File Group'),
			'chrStore'			=> array('displayName' => 'Store Name'),
			'dtCreated'			=> array('displayName' => 'Date Added'),
			'opt_link'			=> array('address' => '/calendar/nso/download.php?key=','display' => 'Download')
			);
		
		sortList('CalendarFiles',	# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'view.php?key=',			# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}
?>
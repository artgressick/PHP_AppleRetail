<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$results;

		$tableHeaders = array(
			'chrHoliday'	=> array('displayName' => 'Holiday Name','default' => 'asc'),
			'dBegin'		=> array('displayName' => 'Begin Date'),
			'dEnd'			=> array('displayName' => 'End Date'),
			'bVisible'		=> array('displayName' => 'Visibility'),
			'opt_link'		=> array('address' => 'excel_export.php?key=','display'=>'Export To Excel'),
			'opt_del'		=> 'chrHoliday'
		);
		
		sortList('Holidays',				# Table Name
			$tableHeaders,					# Table Name
			$results,						# Query results
			'edit.php?key=',				# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 	# Additional header CSS here
			''
		);

	}
?>
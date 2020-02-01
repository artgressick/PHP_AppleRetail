<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrCalendarType'			=> array('displayName' => 'Calendar Types','default' => 'asc')
		);
		if(access_check(15,4)) {
			$tableHeaders['opt_del'] = 'chrCalendarType';
		}
		
		sortList('NSOFileTypes',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(14,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}
?>
<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrNSOFileGroup'			=> array('displayName' => 'File Groups','default' => 'asc')
		);
		if(access_check(27,4)) {
			$tableHeaders['opt_del'] = 'chrNSOFileGroup';
		}
		
		sortList('NSOFileGroups',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(27,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>
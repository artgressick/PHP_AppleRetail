<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrSecurity'			=> array('displayName' => 'Security Groups','default' => 'asc')
		);
		if(access_check(32,4)) {
			$tableHeaders['opt_del'] = 'chrSecurity';
		}
		
		sortList('CalSecurity',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(32,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>
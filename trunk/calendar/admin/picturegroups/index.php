<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrNSOPictureGroup'			=> array('displayName' => 'Picture Groups','default' => 'asc')
		);
		if(access_check(26,4)) {
			$tableHeaders['opt_del'] = 'chrNSOPictureGroup';
		}
		
		sortList('NSOPictureGroups',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(26,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>
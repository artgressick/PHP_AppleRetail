<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrNSOType'			=> array('displayName' => 'Types','default' => 'asc')
		);
		if(access_check(20,4)) {
			$tableHeaders['opt_del'] = 'chrNSOType';
		}
		
		sortList('NSOTypes',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(20,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}
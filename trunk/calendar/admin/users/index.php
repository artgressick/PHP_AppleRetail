<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrLast' 					=> array('displayName' => 'Last Name','default' => 'asc'),
			'chrFirst'					=> array('displayName' => 'First Name'),
			'chrSecurity'				=> array('displayName' => 'Security Group')
		);
	if(access_check(14,4)) {
		$tableHeaders['opt_del'] = 'chrFirst,chrLast';
	}
		
		sortList('CalendarAccess',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(14,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}
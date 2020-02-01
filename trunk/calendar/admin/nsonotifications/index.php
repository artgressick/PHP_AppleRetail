<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrLast'					=> array('displayName' => 'Last Name','default' => 'asc'),
			'chrFirst'					=> array('displayName' => 'First Name'),
			'chrEmail'					=> array('displayName' => 'Email Address')
		);
		if(access_check(22,4)) {
			$tableHeaders['opt_del'] = 'chrFirst,chrLast';
		}
		
		sortList('NSONotifications',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(22,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	} 
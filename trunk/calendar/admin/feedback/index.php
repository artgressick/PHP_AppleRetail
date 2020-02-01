<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$directions;
?>
			<table cellspacing="0" cellpadding="0" class='instructions' style='width: 100%;'>
				<tr>
					<td><?=$directions?></td>
				</tr>
			</table>

<?
		messages();
	
		$tableHeaders = array(
			'chrLast'			=> array('displayName' => 'Last Name'),
			'chrFirst'			=> array('displayName' => 'First Name'),
			'txtFeedback'		=> array('displayName' => 'Feedback'),
			'dtCreated'			=> array('displayName' => 'Date Added','default' => 'asc')
		);
		if(access_check(19,4)) {
			$tableHeaders['opt_del'] = 'chrFirst,chrLast';
		}
		
		sortList('NSOFeedback',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'view.php?key=',		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	} 
<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$directions;
	
		

	
		$_REQUEST['idNSOType'] = (!isset($_REQUEST['idNSOType']) ? '' : $_REQUEST['idNSOType']); 
		$filter_results = db_query("SELECT ID,chrNSOType as chrRecord FROM NSOTypes WHERE !bDeleted ORDER BY chrNSOType","Getting nso types");
?>
			<table cellspacing="0" cellpadding="0" class='instructions' style='width: 100%;'>
				<tr>
					<td><?=$directions?></td>
<?
				if(access_check(7,6)) {
?>
					<td style='text-align: right;'>
<?
					if($_REQUEST['idShow'] == 1) {
?>
						<input type='button' value='View Archived' onclick='location.href="index.php?<?=(isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? 'idNSOType='.$_REQUEST['idNSOType'].'&' :'')?>idShow=2";' />
<?						
					} else {
?>
						<input type='button' value='View Current/Upcoming' onclick='location.href="index.php?<?=(isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? 'idNSOType='.$_REQUEST['idNSOType'].'&' :'')?>idShow=1";' />
<?						
					}
?>						
					</td>
<?
				}
?>
					<td style='text-align: right;'>
						<?=form_select($filter_results,array('nocaption'=>'true',
									'name'=>'idNSOType',
									'caption'=>'- Events -',
									'value'=>$_REQUEST['idNSOType'],
									'extra'=>' onchange="window.location.href=\'?'.($_SESSION['bGlobal'] ? 'idShow='.$_REQUEST['idShow'].'&':'').'idNSOType=\'+this.value"'))?>
					</td>
				</tr>
			</table>

<?
			messages();
		if($_SESSION['bShowOrangeEvents']) {
			$tableHeaders = array(
				'chrIcon'					=> array('displayName' => 'Status'),
				'chrStoreName'				=> array('displayName' => 'Store Name'),
				'chrStoreNum'				=> array('displayName' => 'Store Number'),
				'chrRegion'					=> array('displayName' => 'Region'),
				'chrNSOType'				=> array('displayName' => 'Type'),
				'dBegin2'					=> array('displayName' => 'Project Start Date','default'=>'asc'),
				'dEnd2'						=> array('displayName' => 'Store Opens/Reopens')
			);
		} else {
			$tableHeaders = array(
				'chrStoreName'				=> array('displayName' => 'Store Name'),
				'chrStoreNum'				=> array('displayName' => 'Store Number'),
				'chrRegion'					=> array('displayName' => 'Region'),
				'chrNSOType'				=> array('displayName' => 'Type'),
				'dBegin2'					=> array('displayName' => 'Project Start Date','default'=>'asc'),
				'dEnd2'						=> array('displayName' => 'Store Opens/Reopens')
			);
		}
		if(access_check(7,3)) {
			$tableHeaders['opt_link'] = array('address' => '/calendar/nso/edit.php?key=','display' => 'Edit');
		}
		if(access_check(7,4)) {
			$tableHeaders['opt_del'] = 'chrStoreName';
		}
		
		sortList('NSOs',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(8,1)?'landing.php?key=':(access_check(9,1)?'view.php?key=':'')),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);

	$lastupdate = db_query("SELECT MAX(dtUpdated) AS dtUpdated FROM NSOs WHERE !bDeleted","Get Last Update",1);
?>
		<div style='padding-top:23px;font-weight:normal;'>Last Updated made on: <span style=''><?=($lastupdate['dtUpdated'] != '0000-00-00 00:00:00.0' ? date('l, F jS, Y - g:i a',strtotime($lastupdate['dtUpdated'])) : 'N/A')?></span></div>
<?		
	}
?>
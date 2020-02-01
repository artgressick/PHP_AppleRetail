<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
			<form action="" method="post" id="idForm" onsubmit="return error_check()">

				<table cellspacing="0" cellpadding="0" style='width: 100%;'>
					<tr>
						<td style='width: 50%; vertical-align: top;'>

							<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Event Status?','title'=>'Active','required'=>'true','name'=>'bShow','value'=>'1','checked'=>($info['bShow'] ? 'true' : 'false')))?><?=form_checkbox(array('type'=>'radio','title'=>'De-Active','name'=>'bShow','value'=>'0','checked'=>'true','checked'=>(!$info['bShow'] ? 'true' : 'false')))?></div>

							<?=form_select(db_query("SELECT ID, CONCAT(chrFirst,' ',chrLast) as chrRecord
																FROM Users
																WHERE !bDeleted AND FIND_IN_SET(ID,(SELECT txtUsers FROM NSOPeopleAssoc WHERE chrFieldName='idResponsible'))
																ORDER BY chrFirst,chrLast","Getting Users for Person Responsible")
													,array('caption'=>'Person Responsible','required'=>'true', 'name'=>'idResponsible','value'=>$info['idResponsible']))?>
							
							<?	$results = db_query("SELECT Stores.ID, CONCAT(chrName,'/',chrStoreNum) as chrRecord, Geos.chrGeo as optGroup 
														FROM RetailStores AS Stores
														JOIN Geos ON idGeo=Geos.ID
														WHERE !bDeleted 
														ORDER BY Geos.ID, chrName
													","getting stores"); ?>
							<?=form_select($results,array('caption'=>'Retail Stores','required'=>'true', 'name'=>'idStore','value'=>$info['idStore']));?>
							
							<?	$results = db_query("SELECT ID,chrNSOType as chrRecord FROM NSOTypes WHERE !bDeleted ORDER BY chrNSOType","getting nso types"); ?>
							<?=form_select($results,array('caption'=>'NSO Types','required'=>'true', 'name'=>'idNSOType','value'=>$info['idNSOType']));?>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Project Start Date','name'=>'dBegin','value'=>($info['dBegin'] != '' ? date('n/d/Y',strtotime($info['dBegin'])):''),'extra'=>'onkeyup="dateentry(\'Begin\');"'));?>
									</td>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Change Reason','name'=>'chrdBeginChange'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idBeginStatus','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Begin\',this.value);"','checked'=>($info['idBeginStatus']==1?'true':'false')))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idBeginStatus','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Begin\',this.value);"','checked'=>($info['idBeginStatus']==2?'true':'false')))?>
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'SWS Opens','name'=>'dDate2','value'=>($info['dDate2'] != '' ? date('n/d/Y',strtotime($info['dDate2'])):''),'extra'=>'onkeyup="dateentry(\'Date2\');"'));?>
									</td>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Change Reason','name'=>'chrdDate2Change'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idDate2Status','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Date2\',this.value);"','checked'=>($info['idDate2Status']==1?'true':'false')))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idDate2Status','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Date2\',this.value);"','checked'=>($info['idDate2Status']==2?'true':'false')))?>									
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Last Day SWS Open','name'=>'dDate3','value'=>($info['dDate3'] != '' ? date('n/d/Y',strtotime($info['dDate3'])):''),'extra'=>'onkeyup="dateentry(\'Date3\');"'));?>
									</td>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Change Reason','name'=>'chrdDate3Change'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idDate3Status','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Date3\',this.value);"','checked'=>($info['idDate3Status']==1?'true':'false')))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idDate3Status','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Date3\',this.value);"','checked'=>($info['idDate3Status']==2?'true':'false')))?>									
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Store Set Up','name'=>'dDate4','value'=>($info['dDate4'] != '' ? date('n/d/Y',strtotime($info['dDate4'])):''),'extra'=>'onkeyup="dateentry(\'Date4\');"'));?>
									</td>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Change Reason','name'=>'chrdDate4Change'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idDate4Status','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Date4\',this.value);"','checked'=>($info['idDate4Status']==1?'true':'false')))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idDate4Status','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Date4\',this.value);"','checked'=>($info['idDate4Status']==2?'true':'false')))?>									
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Store Opens','name'=>'dEnd','value'=>($info['dEnd'] != '' ? date('n/d/Y',strtotime($info['dEnd'])):''),'extra'=>'onkeyup="dateentry(\'End\');"'));?>
									</td>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Change Reason','name'=>'chrdEndChange'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idEndStatus','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'End\',this.value);"','checked'=>($info['idEndStatus']==1?'true':'false')))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idEndStatus','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'End\',this.value);"','checked'=>($info['idEndStatus']==2?'true':'false')))?>									
									</td>
								</tr>
							</table>
							<?=form_textarea(array('caption'=>'Hotel Information','name'=>'txtHotel','cols'=>'45','rows'=>'5','value'=>$info['txtHotel']));?>

							<?=form_textarea(array('caption'=>'Airport Information','name'=>'txtAirport','cols'=>'45','rows'=>'5','value'=>$info['txtAirport']));?>
						</td>
						<td style='width: 50%; vertical-align: top;'>
<?	
	
	$title_results = db_query("SELECT UserTitles.ID,UserTitles.chrUserTitle,UserTitles.chrFieldName,NSOUserTitleAssoc.idUser,NSOUserTitleAssoc.chrRecord 
		FROM UserTitles
		LEFT JOIN NSOUserTitleAssoc ON NSOUserTitleAssoc.idUserTitle=UserTitles.ID AND NSOUserTitleAssoc.idNSO='".$info['ID']."'
		WHERE !UserTitles.bDeleted
		ORDER BY dOrder
	","getting titles");
	while($row = mysqli_fetch_assoc($title_results)) {
		if(substr($row['chrFieldName'],0,3) == 'chr') {
			if($row['chrRecord'] != '') {
				echo form_text(array('caption'=>$row['chrUserTitle'], 'name'=>$row['chrFieldName'],'value'=>$row['chrRecord'])); 
			} else {
				echo form_text(array('caption'=>$row['chrUserTitle'], 'name'=>$row['chrFieldName']));
			}
		} else if (substr($row['chrFieldName'],0,2) == 'id') {
			if($row['idUser'] != '') {
?>
							<?=form_select(db_query("SELECT ID, CONCAT(chrFirst,' ',chrLast) AS chrRecord
																FROM Users
																WHERE !bDeleted AND FIND_IN_SET(ID,(SELECT txtUsers FROM NSOPeopleAssoc WHERE chrFieldName='".$row['chrFieldName']."'))
																ORDER BY chrFirst,chrLast","Getting Users For ".$row['chrUserTitle'])
													,array('caption'=>$row['chrUserTitle'], 'name'=>$row['chrFieldName'],'value'=>$row['idUser']))?>
		
<?
			} else {
?>
							<?=form_select(db_query("SELECT ID, CONCAT(chrFirst,' ',chrLast) AS chrRecord
																FROM Users
																WHERE !bDeleted AND FIND_IN_SET(ID,(SELECT txtUsers FROM NSOPeopleAssoc WHERE chrFieldName='".$row['chrFieldName']."'))
																ORDER BY chrFirst,chrLast","Getting Users For ".$row['chrUserTitle'])
													,array('caption'=>$row['chrUserTitle'], 'name'=>$row['chrFieldName']))?>
		
<?
			}
		}
		
	}
	
?>
						
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Display Scope?','title'=>'Yes','required'=>'true','name'=>'bScope','value'=>'1','checked'=>($info['bScope'] ? 'true' : 'false')))?><?=form_checkbox(array('type'=>'radio','title'=>'No','name'=>'bScope','value'=>'0','checked'=>'true','checked'=>(!$info['bScope'] ? 'true' : 'false')))?></div>
							<div class='FormName'>Scope</div><textarea name='txtScope' class='mceEditor' style='width:100%;' id='txtScope' rows='25' cols='45' title='Scope'><?=decode($info['txtScope'])?></textarea>
						</td>
					</tr>
				</table>

				<div class='FormButtons'>
					<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
					<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
					<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'chrSeries','value'=>$info['chrSeries']))?>
				</div>


			</form>
<?
	}
?>
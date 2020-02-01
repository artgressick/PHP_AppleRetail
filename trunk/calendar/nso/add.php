<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
			<form action="" method="post" id="idForm" onsubmit="return error_check()">

				<table cellspacing="0" cellpadding="0" style='width: 100%;'>
					<tr>
						<td style='width: 50%; vertical-align: top;'>
						
							<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Event Status?','title'=>'Active','required'=>'true','name'=>'bShow','value'=>'1','checked'=>'false'))?><?=form_checkbox(array('type'=>'radio','title'=>'De-Active','name'=>'bShow','value'=>'0','checked'=>'true','checked'=>'true'))?></div>

							<?=form_select(db_query("SELECT ID, CONCAT(chrFirst,' ',chrLast) as chrRecord
																FROM Users
																WHERE !bDeleted AND FIND_IN_SET(ID,(SELECT txtUsers FROM NSOPeopleAssoc WHERE chrFieldName='idResponsible'))
																ORDER BY chrFirst,chrLast","Getting Users for Person Responsible")
													,array('caption'=>'Person Responsible','required'=>'true', 'name'=>'idResponsible'))?>
							
							<?	$results = db_query("SELECT Stores.ID, CONCAT(chrName,'/',chrStoreNum) as chrRecord, Geos.chrGeo as optGroup 
														FROM RetailStores AS Stores
														JOIN Geos ON idGeo=Geos.ID
														WHERE !bDeleted 
														ORDER BY Geos.ID, chrName
													","getting stores"); ?>
							<?=form_select($results,array('caption'=>'Retail Stores','required'=>'true', 'name'=>'idStore'));?>
							
							<?	$results = db_query("SELECT ID,chrNSOType as chrRecord FROM NSOTypes WHERE !bDeleted ORDER BY chrNSOType","getting nso types"); ?>
							<?=form_select($results,array('caption'=>'NSO Types','required'=>'true', 'name'=>'idNSOType'));?>

							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Project Start Date','name'=>'dBegin','extra'=>'onkeyup="dateentry(\'Begin\');"'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idBeginStatus','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Begin\',this.value);"'))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idBeginStatus','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Begin\',this.value);"'))?>
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'SWS Opens','name'=>'dDate2','extra'=>'onkeyup="dateentry(\'Date2\');"'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idDate2Status','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Date2\',this.value);"'))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idDate2Status','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Date2\',this.value);"'))?>									
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Last Day SWS Open','name'=>'dDate3','extra'=>'onkeyup="dateentry(\'Date3\');"'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idDate3Status','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Date3\',this.value);"'))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idDate3Status','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Date3\',this.value);"'))?>									
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Store Set Up','name'=>'dDate4','extra'=>'onkeyup="dateentry(\'Date4\');"'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idDate4Status','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'Date4\',this.value);"'))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idDate4Status','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'Date4\',this.value);"'))?>									
									</td>
								</tr>
							</table>
							<table style='width:100%; border:1px solid #333;margin-right:15px;margin-bottom:10px;' cellpadding='5' cellspacing='0'>
								<tr>
									<td style='width:50%;padding-bottom:0;'>
										<?=form_text(array('caption'=>'Store Opens','name'=>'dEnd','extra'=>'onkeyup="dateentry(\'End\');"'));?>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<?=form_checkbox(array('name'=>'idEndStatus','title'=>'TBD','value'=>'1','extra'=>'onchange="dateselect(\'End\',this.value);"'))?>&nbsp;&nbsp;&nbsp;<?=form_checkbox(array('name'=>'idEndStatus','title'=>'Canceled','value'=>'2','extra'=>'onchange="dateselect(\'End\',this.value);"'))?>									
									</td>
								</tr>
							</table>

							<?=form_textarea(array('caption'=>'Hotel Information','name'=>'txtHotel','cols'=>'50','rows'=>'5'));?>

							<?=form_textarea(array('caption'=>'Airport Information','name'=>'txtAirport','cols'=>'50','rows'=>'5'));?>

						</td>
						<td style='width: 50%; vertical-align: top;'>
<?	
	
	$title_results = db_query("SELECT UserTitles.ID,UserTitles.chrUserTitle,UserTitles.chrFieldName
		FROM UserTitles
		WHERE !UserTitles.bDeleted
		ORDER BY dOrder
	","getting titles");
	while($row = mysqli_fetch_assoc($title_results)) {
		if(substr($row['chrFieldName'],0,3) == 'chr') {
				echo form_text(array('caption'=>$row['chrUserTitle'], 'name'=>$row['chrFieldName']));
		} else if (substr($row['chrFieldName'],0,2) == 'id') {
?>
							<?=form_select(db_query("SELECT ID, CONCAT(chrFirst,' ',chrLast) AS chrRecord
																FROM Users
																WHERE !bDeleted AND FIND_IN_SET(ID,(SELECT txtUsers FROM NSOPeopleAssoc WHERE chrFieldName='".$row['chrFieldName']."'))
																ORDER BY chrFirst,chrLast","Getting Users For ".$row['chrUserTitle'])
													,array('caption'=>$row['chrUserTitle'], 'name'=>$row['chrFieldName']))?>
<?
		}
	}
	
?>
						
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Display Scope?','title'=>'Yes','required'=>'true','name'=>'bScope','value'=>'1','checked'=>'false'))?><?=form_checkbox(array('type'=>'radio','title'=>'No','name'=>'bScope','value'=>'0','checked'=>'true','checked'=>'true'))?></div>
							<div class='FormName'>Scope</div><textarea name='txtScope' class='mceEditor' style='width:100%;' id='txtScope' rows='25' cols='45' title='Scope'><?=decode($info['txtScope'])?></textarea>
						</td>
					</tr>
				</table>

<div class='FormButtons'>
								<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
								<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>

							</div>


			</form>
<?
	}
?>
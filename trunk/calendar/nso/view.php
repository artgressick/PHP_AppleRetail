<?php
	include('_controller.php');

	function sitm() { 
		global $BF,$info, $NSOTasks, $Notifications, $SiteSurveys, $Evaluations, $Notes, $Pictures, $Documents, $Datehistory, $SupplyItems, $NSOCorpTasks;
		
		if($info['idBeginStatus'] != 0) { if($info['idBeginStatus'] == 1) { $info['dBegin'] = "TBD"; } else { $info['dBegin'] = "Canceled"; }
		} else if($info['dBegin'] != '' && $info['dBegin'] != '0000-00-00') { $info['dBegin'] = date('m/d/Y',strtotime($info['dBegin']));
		} else { $info['dBegin'] = 'N/A'; }

		if($info['idDate2Status'] != 0) { if($info['idDate2Status'] == 1) { $info['dDate2'] = "TBD"; } else { $info['dDate2'] = "Canceled"; }
		} else if($info['dDate2'] != '' && $info['dDate2'] != '0000-00-00') { $info['dDate2'] = date('m/d/Y',strtotime($info['dDate2']));
		} else { $info['dDate2'] = ''; }

		if($info['idDate3Status'] != 0) { if($info['idDate3Status'] == 1) { $info['dDate3'] = "TBD"; } else { $info['dDate3'] = "Canceled"; }
		} else if($info['dDate3'] != '' && $info['dDate3'] != '0000-00-00') { $info['dDate3'] = date('m/d/Y',strtotime($info['dDate3']));
		} else { $info['dDate3'] = ''; }

		if($info['idDate4Status'] != 0) { if($info['idDate4Status'] == 1) { $info['dDate4'] = "TBD"; } else { $info['dDate4'] = "Canceled"; }
		} else if($info['dDate4'] != '' && $info['dDate4'] != '0000-00-00') { $info['dDate4'] = date('m/d/Y',strtotime($info['dDate4']));
		} else { $info['dDate4'] = ''; }
		
		if($info['idEndStatus'] != 0) { if($info['idEndStatus'] == 1) { $info['dEnd'] = "TBD"; } else { $info['dEnd'] = "Canceled"; }
		} else if($info['dEnd'] != '' && $info['dEnd'] != '0000-00-00') { $info['dEnd'] = date('m/d/Y',strtotime($info['dEnd']));
		} else { $info['dEnd'] = 'N/A'; }
		
?>
		<div style='border: 1px solid gray; padding: 5px;'>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class='FormName headerBlock'>Store Name</td>
					<td class='FormDisplay infoBlock'><?=$info['chrStore']?> / <?=$info['chrStoreNum']?></td>
					<td class="tcgutter"></td>
					<td class='FormName headerBlock'>Project Start Date</td>
					<td class='FormDisplay infoBlock'><?=$info['dBegin']?></td>
<?
				if($info['dDate2'] != '') {
?>
					<td class="tcgutter"></td>
					<td class='FormName headerBlock'>SWS Opens</td>
					<td class='FormDisplay infoBlock'><?=$info['dDate2']?></td>
<?
				}
				if($info['dDate3'] != '') {
?>
					<td class="tcgutter"></td>
					<td class='FormName headerBlock'>Last Day SWS Open</td>
					<td class='FormDisplay infoBlock'><?=$info['dDate3']?></td>
<?
				}
?>
				</tr>
				<tr>
					<td class='FormName headerBlock'>NSO Type</td>
					<td class='FormDisplay infoBlock'><?=$info['chrNSOType']?></td>
					<td class="tcgutter"></td>
<?
				if($info['dDate4'] != '') {
?>
					<td class='FormName headerBlock'>Store Set Up</td>
					<td class='FormDisplay infoBlock'><?=$info['dDate4']?></td>
					<td class="tcgutter"></td>
<?
				}
?>
					<td class='FormName headerBlock'>Store Opens</td>
					<td class='FormDisplay infoBlock'><?=$info['dEnd']?></td>

				</tr>
				<tr>
					<td class='FormName headerBlock'>Last Updated</td>
					<td colspan='10' class='FormDisplay infoBlock'><?=($info['dtUpdated'] != '0000-00-00 00:00:00.0' ? date('l, F jS, Y - g:i a',strtotime($info['dtUpdated'])) : 'N/A')?></td>
				</tr>
			</table>
			<table cellspacing="0" cellpadding="0" id='tasks' class='showHideTitle' style='width: 100%;'>
				<tr>
					<td style='font-size: 14px;' onclick='showhide("tasks");'>Tasks</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnTasks','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/tasks.php?tbl=NSOTasks&id=".$info["ID"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='tasksbox' class='showHideBody'>
				<table id='NSOTaskAssoc' class='List' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Event Name</th>
							<th>Projected Completion Date</th>
							<th>Date Offset</th>
							<th style='text-align:center;'>Complete</th>
							<?=(access_check(9,4) ? '<th class="options"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?				$count = 0;
				$i=0;
				while($row = mysqli_fetch_assoc($NSOTasks)) { 
					(access_check(9,3) ? $link = "onclick='window.location.href=\"tasks.php?key=".$row['chrKEY']."&oldkey=".$_REQUEST['key']."\"'" : $link = "");
?>
						<tr id='NSOTaskAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("NSOTaskAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSOTaskAssoctr<?=$row['ID']?>");'>
							<td <?=$link?>><?=$row['chrNSOTask']?></td>
							<td <?=$link?>>
<?
								if($row['chrNSOTask'] == 'NSO Setup') {
									echo date('l, F jS, Y', strtotime($info['dBegin'] . ' +'.$row['intDateOffset'].' Days'));
								} else {
									$offset = $row['intDateOffset'];
									$test = '';
									$dow = date('N', strtotime($info['dBegin'] . ' +'.$row['intDateOffset'].' Days'));
									if($dow == 6) { 
										$offset = $offset - 1;
									} else if ($dow == 7) {
										$offset = $offset - 2;
									}
									echo date('l, F jS, Y', strtotime($info['dBegin'] . ' +'.$offset.' Days'));
								}
?>
							</td>
							<td <?=$link?>><?=$row['intDateOffset']?></td>
							<td style='text-align:center;'><input type='checkbox' name='complete<?=$row['ID']?>' <?=(access_check(9,3) ? '' : "disabled='disabled'")?> id='complete<?=$row['ID']?>' onchange='javascript:changepercent("<?=$row['ID']?>","<?=$info['ID']?>");' <?=($row['intNSOTaskStatus'] == 100 ? 'checked="checked"':'')?> /></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrNSOTask']?>','<?=$row['chrKEY']?>','NSOTaskAssoc');" title="Delete: <?=$row['chrNSOTask']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td>
<?
						}
?>
						</tr>
<?	} ?>
					</tbody>
				</table>
			</div>
			<table cellspacing="0" cellpadding="0" id='corptasks' class='showHideTitle' style='width: 100%;'>
				<tr>
					<td style='font-size: 14px;' onclick='showhide("corptasks");'>Corporate Partner&#39;s Tasks</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnCorpTasks','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/corptasks.php?tbl=NSOCorpTasks&id=".$info["ID"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='corptasksbox' class='showHideBody' style='display:none;'>
				<table id='NSOCorpTaskAssoc' class='List' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Task Name</th>
							<th>Projected Completion Date</th>
							<th>Date Offset</th>
							<th style='text-align:center;'>Complete</th>
							<?=(access_check(9,4) ? '<th class="options"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?				$count = 0;
				$i=0;
				while($row = mysqli_fetch_assoc($NSOCorpTasks)) { 
					(access_check(9,3) ? $link = "onclick='window.location.href=\"corptasks.php?key=".$row['chrKEY']."&oldkey=".$_REQUEST['key']."\"'" : $link = "");
?>
						<tr id='NSOCorpTaskAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("NSOCorpTaskAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSOCorpTaskAssoctr<?=$row['ID']?>");'>
							<td <?=$link?>><?=$row['chrNSOCorpTask']?></td>
							<td <?=$link?>>
<?
								if($row['chrNSOCorpTask'] == 'NSO Setup') {
									echo date('l, F jS, Y', strtotime($info['dBegin'] . ' +'.$row['intDateOffset'].' Days'));
								} else {
									$offset = $row['intDateOffset'];
									$test = '';
									$dow = date('N', strtotime($info['dBegin'] . ' +'.$row['intDateOffset'].' Days'));
									if($dow == 6) { 
										$offset = $offset - 1;
									} else if ($dow == 7) {
										$offset = $offset - 2;
									}
									echo date('l, F jS, Y', strtotime($info['dBegin'] . ' +'.$offset.' Days'));
								}
?>
							</td>
							<td <?=$link?>><?=$row['intDateOffset']?></td>
							<td style='text-align:center;'><input type='checkbox' name='complete<?=$row['ID']?>' <?=(access_check(9,3) ? '' : "disabled='disabled'")?> id='complete<?=$row['ID']?>' onchange='javascript:corpchangepercent("<?=$row['ID']?>","<?=$info['ID']?>");' <?=($row['intNSOTaskStatus'] == 100 ? 'checked="checked"':'')?> /></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrNSOCorpTask']?>','<?=$row['chrKEY']?>','NSOCorpTaskAssoc');" title="Delete: <?=$row['chrNSOCorpTask']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td>
<?
						}
?>
						</tr>
<?	} ?>
					</tbody>
				</table>
			</div>

			<table id='notifications' cellspacing="0" cellpadding="0" class='showHideTitle' style='width: 100%;'>
				<tr>
					<td style='font-size: 14px;' onclick='showhide("notifications");'>Date Change Notifications Group</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnNotifications','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/notifications.php?tbl=NSONotifications&key=".$_REQUEST["key"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='notificationsbox' class='showHideBody' style='display:none;'>
				<table id='NSONotificationAssoc' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Email Address&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?			$count = 0;
			while($row = mysqli_fetch_assoc($Notifications)) { 

?>
						<tr id='NSONotificationAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("NSONotificationAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSONotificationAssoctr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><?=$row['chrLast']?></td>
							<td style='cursor:auto;'><?=$row['chrFirst']?></td>
							<td style='cursor:auto;'><?=$row['chrEmail']?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrFirst'].' '.$row['chrLast']?>','<?=$row['chrKEY']?>','NSONotificationAssoc');" title="Delete: <?=$row['chrFirst'].' '.$row['chrLast']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
<?
			} 
?>
					</tbody>
				</table>
			</div>
<?
	if(($info['idNSOType'] == 1 || $info['idNSOType'] == 11)) {
?>
			<table id='sitesurveys' cellspacing="0" cellpadding="0" class='showHideTitle' style='width: 100%;'>
				<tr>
					<td style='font-size: 14px;' onclick='showhide("sitesurveys");'>Site Survey Complete Distribution Group</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnSiteSurvey','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/sitesurveys.php?tbl=NSOSiteSurveys&key=".$_REQUEST["key"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='sitesurveysbox' class='showHideBody' style='display:none;'>
				<table id='NSOSiteSurveyAssoc' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Email Address&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?			$count = 0;
			while($row = mysqli_fetch_assoc($SiteSurveys)) { 
?>
						<tr id='NSOSiteSurveyAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("NSOSiteSurveyAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSOSiteSurveyAssoctr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><?=$row['chrLast']?></td>
							<td style='cursor:auto;'><?=$row['chrFirst']?></td>
							<td style='cursor:auto;'><?=$row['chrEmail']?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrFirst'].' '.$row['chrLast']?>','<?=$row['chrKEY']?>','NSOSiteSurveyAssoc');" title="Delete: <?=$row['chrFirst'].' '.$row['chrLast']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
<?
			} 
?>
					</tbody>
				</table>
			</div>


<?
	}
?>
			<table id='evaluations' cellspacing="0" cellpadding="0" class='showHideTitle' style='width: 100%;'>
				<tr>
					<td style='font-size: 14px;' onclick='showhide("evaluations");'>Evaluation Complete Distribution Group</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnEvaluations','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/evaluations.php?tbl=NSOEvaluations&key=".$_REQUEST["key"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='evaluationsbox' class='showHideBody' style='display:none;'>
				<table id='NSOEvaluationsAssoc' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Email Address&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?			$count = 0;
			while($row = mysqli_fetch_assoc($Evaluations)) { 
?>
						<tr id='NSOEvaluationsAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("NSOEvaluationsAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSOEvaluationsAssoctr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><?=$row['chrLast']?></td>
							<td style='cursor:auto;'><?=$row['chrFirst']?></td>
							<td style='cursor:auto;'><?=$row['chrEmail']?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrFirst'].' '.$row['chrLast']?>','<?=$row['chrKEY']?>','NSOEvaluationsAssoc');" title="Delete: <?=$row['chrFirst'].' '.$row['chrLast']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
<?
			} 
?>
					</tbody>
				</table>
			</div>
			<table cellspacing="0" cellpadding="0" id='pics' class='showHideTitle' style='width: 100%;'>
				<tr id='supplyitems'>
					<td style='font-size: 14px;' onclick='showhide("supply");'>Supply Items</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnTasks','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/supply.php?tbl=SupplyAssoc&id=".$info["ID"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='supplybox' class='showHideBody' style='display:none;'>	
<?		
		if(mysqli_num_rows($SupplyItems) > 0) {
?>
				<table id='SupplyAssoc' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="sorttable_nosort" style='width:20px;'>&nbsp;</th>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Supply Item&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>Date Added&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Date Updated&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Quantity Sent&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Quantity Received&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($SupplyItems)) {
?>
						<tr id='SupplyAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("SupplyAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("SupplyAssoctr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><?=($row['chrThumbnail']!='' ? miniPopup($BF.'calendar/nso/popupsupply.php?image='.$row['chrThumbnail'],'View') : '')?></td>
							<td style='cursor:auto;'><?=$row['chrItem']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['dtCreated']?></span><?=date('n/d/Y g:i a',strtotime($row['dtCreated']))?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['dtUpdated']?></span><?=date('n/d/Y g:i a',strtotime($row['dtUpdated']))?></td>
							<td style='cursor:auto;'><? if(access_check(9,2) || access_check(9,3)) { ?><input type='text' size='4' maxlength='5' name='intSupply<?=$row['ID']?>' id='intSupply<?=$row['ID']?>' value='<?=$row['intQSent']?>' onkeyup='updatesupply("<?=$row['ID']?>","<?=$info['ID']?>")' /><? } else { ?><?=$row['intQSent']?><? } ?></td>
							<td style='cursor:auto;'><?=$row['intQReceived']?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrItem']?>','<?=$row['chrKEY']?>','SupplyAssoc');" title="Delete: <?=$row['chrItem']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
<?
			}
?>
					</tbody>
			 	</table>
<?
		} else {
?>
				<div style='padding: 4px;text-align: center;border:1px solid #999;'>No Supplies found in the database.</div>
<?
		}
?>
			</div>
			<table id='notes' cellspacing="0" cellpadding="0" class='showHideTitle' style='width: 100%;'>
				<tr>
					<td style='font-size: 14px;' onclick='showhide("notes");'>Notes</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnNotes','value'=>'+','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/addnote.php?tbl=NSONotes&id=".$info["ID"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'")) : '')?></td>
				</tr>
			</table>
			<div id='notesbox' class='showHideBody' style='display:none;'>
<?
			if(mysqli_num_rows($Notes) > 0) {
?>

				<table id='NSONotes' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Note Text&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
<?
				$count = 0;
				while($row = mysqli_fetch_assoc($Notes)) { 
?>
						<tr id='NSONotestr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("NSONotestr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSONotestr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><?=(strlen($row['txtNote']) > 150 ? substr($row['txtNote'],0,150).'...' : $row['txtNote'])?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=(strlen($row['txtNote']) > 50 ? substr($row['txtNote'],0,50).'...' : $row['txtNote'])?>','<?=$row['chrKEY']?>','NSONotes');" title="Delete: <?=(strlen($row['txtNote']) > 50 ? substr($row['txtNote'],0,50).'...' : $row['txtNote'])?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
		
<?
				}
?>
				</table>
<? 
			} else {
?>
				<div style='padding: 4px;text-align: center;border:1px solid #999;'>No Notes found in the database.</div>
<?
			}
?>
			</div>
			<table cellspacing="0" cellpadding="0" id='pics' class='showHideTitle' style='width: 100%;'>
				<tr id='pics'>
					<td style='font-size: 14px;' onclick='showhide("pics");'>Photo Gallery</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnPics','value'=>'+','style'=>'margin: 0;','extra'=>'onclick="window.location.href=\''.$BF.'calendar/nso/nsopictures/?key='.$_REQUEST['key'].'\'"')) : '')?></td>
				</tr>
			</table>
			<div id='picsbox' class='showHideBody' style='display:none;'>	
<?		
		if(mysqli_num_rows($Pictures) > 0) {
?>
				<table id='CalendarFiles' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="sorttable_nosort" style='width:20px;'>&nbsp;</th>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Picture Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>Picture Group&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Date Added&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($Pictures)) {
				$link = 'onclick=\'window.location.href="nsopictures/edit.php?key='.$row['chrKEY'].'";\''; 
?>
						<tr id='CalendarFilestr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("CalendarFilestr<?=$row['ID']?>");' onmouseout='UnRowHighlight("CalendarFilestr<?=$row['ID']?>");'>
							<td><?=miniPopup($BF.'calendar/nso/popup.php?image='.$row['chrThumbnail'],'View')?></td>
							<td <?=$link?>><?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?></td>
							<td <?=$link?>><?=$row['chrNSOPictureGroup']?></td>
							<td <?=$link?>><span style='display:none;'><?=$row['dtCreatedNF']?></span><?=$row['dtCreated']?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?>','<?=$row['chrKEY']?>','CalendarFiles');" title="Delete: <?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
<?
			}
?>
					</tbody>
			 	</table>
<?
		} else {
?>
				<div style='padding: 4px;text-align: center;border:1px solid #999;'>No Pictures found in the database.</div>
<?
		}
?>
			</div>
			<table cellspacing="0" cellpadding="0" id='docs' class='showHideTitle' style='width: 100%;'>
				<tr id='docs'>
					<td style='font-size: 14px;' onclick='showhide("docs");'>Documents</td>
					<td style='text-align: right; width: 1cm;'><?=(access_check(9,2) ? form_button(array('name'=>'btnDocs','value'=>'+','style'=>'margin: 0;','extra'=>'onclick="window.location.href=\''.$BF.'calendar/nso/nsodocuments/?key='.$_REQUEST['key'].'\'"')) : '')?></td>
				</tr>
			</table>
			<div id='docsbox' class='showHideBody' style='display:none;'>	
<?		
		if(mysqli_num_rows($Documents) > 0) {
?>
				<table id='CalendarFiles2' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Document Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>File Group&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Date Added&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class="options sorttable_nosort"><img src='<?=$BF?>images/options.gif' alt='options' /></th>
							<?=(access_check(9,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '')?>
						</tr>
					</thead>
					<tbody>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($Documents)) {
				$link = 'onclick=\'window.location.href="nsodocuments/edit.php?key='.$row['chrKEY'].'";\'';
?>
						<tr id='CalendarFiles2tr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("CalendarFiles2tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("CalendarFiles2tr<?=$row['ID']?>");'>
							<td <?=$link?>><?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?></td>
							<td <?=$link?>><?=$row['chrNSOFileGroup']?></td>
							<td <?=$link?>><span style='display:none;'><?=$row['dtCreatedNF']?></span><?=$row['dtCreated']?></td>
							<td><?=linkto(array('address' => '/calendar/nso/download.php?key='.$row['chrKEY'],'display' => 'Download'))?></td>
<?
						if(access_check(9,4)) {
?>
							<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?>','<?=$row['chrKEY']?>','CalendarFiles2');" title="Delete: <?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
						}
?>
						</tr>
<?
			}
?>
					</tbody>
			 	</table>
<?
		} else {
?>
				<div style='padding: 4px;text-align: center;border:1px solid #999;'>No Documents found in the database.</div>
<?
		}
?>
			</div>
			<table cellspacing="0" cellpadding="0" id='history' class='showHideTitle' style='width: 100%;'>
				<tr id='history'>
					<td style='font-size: 14px; width:100%; white-space:nowrap;' onclick='showhide("history");'>History of Date Changes</td>
					<td style='text-align: right; white-space:nowrap;'>
<?
					$numdates = mysqli_num_rows($Datehistory);
					if($numdates > 5) {
?>
						Showing 5 of <?=$numdates?> changes. <?=form_button(array('name'=>'btnDates','value'=>'Show All Date Changes','style'=>'margin: 0;','extra'=>"onclick='javascript:newwin = window.open(\"popups/showdates.php?key=".$_REQUEST["key"]."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\"); newwin.focus();'"))?>
<?						
					} else { echo "&nbsp;"; }
?>
					</td>
				</tr>
			</table>
			<div id='historybox' class='showHideBody' style='display:none;'>	
<?		
		if($numdates > 0) {
?>
				<table id='DateHistory' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Date/Time Of Change&nbsp;<img src='<?=$BF?>components/list/column_sorted_desc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>Date Changed&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Old Date&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>New Date&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Changed By&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
						</tr>
					</thead>
					<tbody>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($Datehistory)) {
				if($count >= 5) { break; }
				if($row['chrColumnName'] == 'dBegin') {
					$row['chrColumnName'] = 'Project Start Date';
				} else if($row['chrColumnName'] == 'dEnd') {
					$row['chrColumnName'] = 'Store Opens';
				} else if($row['chrColumnName'] == 'dDate2') {
					$row['chrColumnName'] = 'SWS Opens';
				} else if($row['chrColumnName'] == 'dDate3') {
					$row['chrColumnName'] = 'Last Day SWS Open';
				} else if($row['chrColumnName'] == 'dDate4') {
					$row['chrColumnName'] = 'Store Set Up';
				}
				
				if($row['txtOldValue'] != 'TBD' && $row['txtOldValue'] != 'Canceled') {
					if($row['txtOldValue'] != '' && $row['txtOldValue'] != '0000-00-00' && $row['txtOldValue'] != '1969-12-31') { $row['txtOldValue'] = date('n/j/Y',strtotime($row['txtOldValue'])); }
					else { $row['txtOldValue'] = 'N/A'; }
				}
				if($row['txtNewValue'] != 'TBD' && $row['txtNewValue'] != 'Canceled') {
					if($row['txtNewValue'] != '' && $row['txtNewValue'] != '0000-00-00' && $row['txtNewValue'] != '1969-12-31') { $row['txtNewValue'] = date('n/j/Y',strtotime($row['txtNewValue'])); }
					else { $row['txtNewValue'] = 'N/A'; }
				}
?>
						<tr id='DateHistorytr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("DateHistorytr<?=$row['ID']?>");' onmouseout='UnRowHighlight("DateHistorytr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['dtDateTime']?></span><?=date('n/j/Y g:i a',strtotime($row['dtDateTime']))?></td>
							<td style='cursor:auto;'><?=$row['chrColumnName']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['txtOldValue']?></span><?=$row['txtOldValue']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['txtNewValue']?></span><?=$row['txtNewValue']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['chrFirst'].' '.$row['chrLast']?></span><a href='mailto:<?=$row['chrEmail']?>'><?=$row['chrFirst'].' '.$row['chrLast']?></a></td>
						</tr>
<?
			}
?>
					</tbody>
			 	</table>
<?
		} else {
?>
				<div style='padding: 4px;text-align: center;border:1px solid #999;'>No Dates have been changed on this Event.</div>
<?
		}
?>
			</div>
			<iframe id='miniPopupWindow' style='position: absolute; display: none; background: #ccc; border: 1px solid gray;'></iframe>
		</div>
<?
	}
?>
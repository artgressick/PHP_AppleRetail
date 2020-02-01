<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$directions,$info;
	
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
		<table cellspacing="0" cellpadding="0" style='width: 910px;'>
			<tr>
				<td style='width: 300px; vertical-align: top;'>
				<div style='padding-bottom:8px;font-size:16px;font-weight:bold;color:#666699;'><?=$info['chrStore'].' '.$info['chrStoreNum']?></div>
				<div style='padding-bottom:3px;font-weight:bold;'><?=$info['chrAddress'].($info['chrAddress2']!=''?'<br />'.$info['chrAddress2']:'').($info['chrAddress3']!=''?'<br />'.$info['chrAddress3']:'')?>
				<div style='padding-bottom:3px;font-weight:bold;'><?=$info['chrCity']?></div>
				<div style='padding-bottom:3px;font-weight:bold;'><?=$info['chrState']?> <?=$info['chrPostalCode']?> (<?=$info['chrCountry']?>)</div>
				<div style='padding-bottom:18px;font-weight:bold;'>Phone: <?=$info['chrPhone']?></div>
				<div style='padding-bottom:3px;font-weight:normal;'>Project Start Date: <span style='font-weight:bold;'><?=$info['dBegin']?></span></div>
<?
			if($info['dDate2'] != '') { 
?>
				<div style='padding-bottom:3px;font-weight:normal;'>SWS Opens: <span style='font-weight:bold;'><?=$info['dDate2']?></span></div>
<?
			}
			if($info['dDate3'] != '') {
?>
				<div style='padding-bottom:3px;font-weight:normal;'>Last Day SWS Open: <span style='font-weight:bold;'><?=$info['dDate3']?></span></div>
<?
			}
			if($info['dDate4'] != '') {
?>
				<div style='padding-bottom:3px;font-weight:normal;'>Store Set Up: <span style='font-weight:bold;'><?=$info['dDate4']?></span></div>
<?
			}
?>
				<div style='padding-bottom:18px;font-weight:normal;'>Store Opens: <span style='font-weight:bold;'><?=$info['dEnd']?></span></div>
<?
	$picture = db_query("SELECT chrMedium,chrFileTitle FROM CalendarFiles WHERE idCalendarFileType=1 AND idNSO='".$info['ID']."' ORDER BY bPrimary DESC,chrCalendarFile LIMIT 1","getting image",1);

	$positions = db_query("SELECT UserTitles.ID,UserTitles.chrUserTitle,UserTitles.chrFieldName,Users.chrFirst,Users.chrLast, Users.chrEmail,NSOUserTitleAssoc.chrRecord
		FROM UserTitles
		LEFT JOIN NSOUserTitleAssoc ON NSOUserTitleAssoc.idUserTitle=UserTitles.ID AND idNSO='".$info['ID']."'
		LEFT JOIN Users ON Users.ID=NSOUserTitleAssoc.idUser
		WHERE !UserTitles.bDeleted AND UserTitles.ID NOT IN (16,17,18,19,20,21)
		ORDER BY dOrder
	","Getting Users and Tasks");
	
	while($row = mysqli_fetch_assoc($positions)) { 
		if (substr($row['chrFieldName'],0,2) == 'id') {
			if($row['chrFirst'] != '') {
?>
					<div style='padding-bottom:3px;font-weight:normal;'><?=$row['chrUserTitle']?>: <a style='font-weight:bold;' href="mailto:<?=$row['chrEmail']?>"><?=$row['chrFirst'].' '.$row['chrLast']?></a></div>
<?
			}
		} else if(substr($row['chrFieldName'],0,3) == 'chr') {
				if($row['ID'] == 1) {
					$email = db_query('SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'].' AND idUserTitle = 16','Getting E-mail Address',1);
					if($email['chrRecord'] != '') { 
						if($row['chrRecord'] == '') { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$email['chrRecord'].'</a>'; }
						else { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$row['chrRecord'].'</a>'; }
					}
				}
				if($row['ID'] ==9) {
					$email = db_query('SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'].' AND idUserTitle = 17','Getting E-mail Address',1);
					if($email['chrRecord'] != '') { 
						if($row['chrRecord'] == '') { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$email['chrRecord'].'</a>'; }
						else { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$row['chrRecord'].'</a>'; }
					}
				}
				if($row['ID'] == 10) {
					$email = db_query('SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'].' AND idUserTitle = 18','Getting E-mail Address',1);
					if($email['chrRecord'] != '') { 
						if($row['chrRecord'] == '') { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$email['chrRecord'].'</a>'; }
						else { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$row['chrRecord'].'</a>'; }
					}
				}
				if($row['ID'] == 11) {
					$email = db_query('SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'].' AND idUserTitle = 19','Getting E-mail Address',1);
					if($email['chrRecord'] != '') { 
						if($row['chrRecord'] == '') { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$email['chrRecord'].'</a>'; }
						else { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$row['chrRecord'].'</a>'; }
					}
				}
				if($row['ID'] == 12) {
					$email = db_query('SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'].' AND idUserTitle = 20','Getting E-mail Address',1);
					if($email['chrRecord'] != '') { 
						if($row['chrRecord'] == '') { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$email['chrRecord'].'</a>'; }
						else { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$row['chrRecord'].'</a>'; }
					}
				}
				if($row['ID'] == 13) {
					$email = db_query('SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'].' AND idUserTitle = 21','Getting E-mail Address',1);
					if($email['chrRecord'] != '') { 
						if($row['chrRecord'] == '') { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$email['chrRecord'].'</a>'; }
						else { $row['chrRecord'] = '<a href=&#39;mailto:'.$email['chrRecord'].'&#39;>'.$row['chrRecord'].'</a>'; }
					}
				}
				if($row['chrRecord'] != '') {
?>
					<div style='padding-bottom:3px;font-weight:normal;'><?=$row['chrUserTitle']?>: <span style='font-weight:bold;'><?=decode($row['chrRecord'])?></span></div>
<?
				}
		}
	}
?>

					<?=($info['txtHotel'] != '' ? "<div style='padding-bottom:3px;font-weight:normal;'>Hotel Information: <span style='font-weight:bold;'>".nl2br(decode($info['txtHotel']))."</span></div>" : "")?>
					<?=($info['txtAirport'] != '' ? "<div style='padding-bottom:3px;font-weight:normal;'>Airport Information: <span style='font-weight:bold;'>".nl2br(decode($info['txtAirport']))."</span></div>" : "")?>
					<?=($info['bScope'] ? "<div style='padding-top:18px; font-weight:normal;'>".decode($info['txtScope'])."</div>" : "")?>
				</td>
				<td style='width: 500px; vertical-align: top;'>
					<img src='<?=$BF?>calendar/nsopictures/<?=$picture['chrMedium']?>' alt='<?=$picture['chrFileTitle']?>' style='max-width: 500px; max-height: 500px;' />
				</td>
			</tr>
		</table>
		</div>
		<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='margin-top:10px;'>
			<tr>
				<td style='width:60%;'><div class='header3' style='vertical-align:middle;'>Photos <span class='FormRequired'>(Click on the Thumbnail to enlarge)</span></div></td>
				<td style='width:50px;'>&nbsp;</td>
				<td><div class='header3'>Files <span class='FormRequired'>(Click on the Title to Download)</span></div></td>
			</tr>
			<tr>
				<td style='vertical-align:top;'>
					<div style='border:1px solid #808080; overflow: auto; height: 130px; width: 550px;'>
<?
		$photos = db_query('SELECT ID,chrKEY, chrFileTitle, chrCalendarFile, DATE_FORMAT(dtCreated,"%c/%e/%Y - %l:%i %p") AS dtCreated,chrThumbnail FROM CalendarFiles WHERE idCalendarFileType=1 AND idNSO='.$info['ID'].' ORDER BY bPrimary DESC,chrCalendarFile','Getting Photos');
		$numPhotos = mysqli_num_rows($photos);
		if($numPhotos > 0) {
?>
					<table cellspacing='0' cellpadding='0' border='0'>
						<tr>
<?
			$col = 0;
			while($row = mysqli_fetch_assoc($photos)) { 
?>
							<td style='padding: 0 10px; vertical-align: middle; text-align: center;'><?=linkto(array('address'=>'calendar/nso/popup.php?image='.$row['chrCalendarFile'],'img'=>'calendar/nsopictures/'.$row['chrThumbnail']))?></td>
<?
			}
?>
						</tr>
					</table>
<?
} else {
?>
					<div style='padding:10px;text-align:center;color:#333;'>No Photos to Display</div>
<?		
		}
?>
					</div>
				</td>
				<td style='width:50px;'>&nbsp;</td>
				<td style='vertical-align:top;'>
					<div style='border:1px solid #808080;'>
<?
			$files = db_query('SELECT CalendarFiles.ID,CalendarFiles.chrKEY,chrFileTitle,chrCalendarFile,chrNSOFileGroup,DATE_FORMAT(dtCreated,"%c/%e/%Y - %l:%i %p") AS dtCreated
				FROM CalendarFiles
				JOIN NSOFileGroups ON NSOFileGroups.ID=CalendarFiles.idNSOFileGroup
				WHERE idCalendarFileType=2 AND idNSO='.$info['ID'].'
				ORDER BY chrNSOFileGroup,chrCalendarFile'
			,'Getting Docs');
			if(mysqli_num_rows($files) > 0) {
				$group_name = "";
				while($row = mysqli_fetch_assoc($files)) {
					$tmp_group_name = strtolower(str_replace(' ','_',$row['chrNSOFileGroup'])); 
					if($group_name != $tmp_group_name) {
						if($group_name != "") { echo "</div>"; }
						$group_name = $tmp_group_name; 
?>
						<div id='<?=$group_name?>' class='showHideTitle'>
							<div style='float: left; padding: 1px;'><img src='<?=$BF?>calendar/images/folder.png' /></div>
							<div style='padding: 5px;'><?=$row['chrNSOFileGroup']?></div>
						</div>
						<div id='<?=$group_name?>box' class='showHideBody' style='display: none;'>
								<div style='padding: 2px 5px;'>&bull; <a href='<?=$BF?>calendar/nso/download.php?key=<?=$row['chrKEY']?>' style='text-decoration:none;'><?=$row['chrFileTitle']?></a></div>
<?				
					} else {
?>						
								<div style='padding: 2px 5px;'>&bull; <a href='<?=$BF?>calendar/nso/download.php?key=<?=$row['chrKEY']?>' style='text-decoration:none;'><?=$row['chrFileTitle']?></a></div>
<?					
					}
				}
?>
						</div>
<?		
			} else {
?>
					<div style='padding:10px;text-align:center;color:#333;'>No Files to Display</div>
<?		
			}
?>
					</div>
				</td>
			</tr>
		</table>
		<div style='padding-top:23px;font-weight:normal;'>Last Updated: <span style=''><?=($info['dtUpdated'] != '0000-00-00 00:00:00.0' ? date('l, F jS, Y - g:i a',strtotime($info['dtUpdated'])) : 'N/A')?></span></div>
<?
	}
?>

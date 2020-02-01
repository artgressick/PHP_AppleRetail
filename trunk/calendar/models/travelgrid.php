<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?=(isset($title) && $title != '' ? $title .' - ' : '')?>Retail Ops</title>
		<link href="<?=$BF?>includes/global.css" rel="stylesheet" type="text/css" />
		<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' language='JavaScript' src="<?=$BF?>calendar/includes/calendar.js"></script>
		<script type='text/javascript' language="JavaScript" src="<?=$BF?>includes/nav.js"></script>
		<script type='text/javascript'>var BF = '<?=$BF?>';
			function Select(name){
		       theForm = document.getElementById('idForm'); // This is the id of the form
		       var tmpName = name+"[]";
               if(document.getElementById(name+'btn').value == 'Select All') {
		       		document.getElementById(name+'btn').value = 'Un-Select All';
		            var type=1;
		       } else {
		            document.getElementById(name+'btn').value = 'Select All';
		            var type=0;
	           }

		       for(i = 0; i < theForm.length; i++) {
		           if(theForm[i].name == tmpName) {
		    	       //alert(name+theForm[i].value);  // used for testing.  doesn't need to be here.
		               if(document.getElementById(name+theForm[i].value)) {
		                   document.getElementById(name+theForm[i].value).checked = (type==1 ? true : false); // false will unselect, true will select
		               }
		           }
		       }
		   }
		</script>
<?		# If the "Stuff in the Header" function exists, then call it
		if(function_exists('sith')) { sith(); } 
?>
		<script type='text/javascript' src="<?=$BF?>calendar/includes/calendar.js"></script>
	</head>
	<body onload='dropdown.startnav("nav");<?=(isset($bodypop) ? $bodypop : '').(isset($bodyParams) ? $bodyParams : '')?>'>
<?
		// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // This is to display the SESSION variables, unrem to use
?>
		<table width="964" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
			<tr>
				<td width="964" height="9" colspan="3"><?=img(array('src'=>'border-top.gif'))?></td>
			</tr>
			<tr>
				<td width="4" style="background: url(<?=$BF?>images/border-left.gif) repeat-y;"><?=img(array('src'=>'border-left.gif'))?></td>
				<td width="956">
					<div align="center">
						<table width="924" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><?=img(array('src'=>'topbanner.gif'))?></td>
							</tr>
							<tr>
								<td>
									<table width="924" border="0" cellspacing="0" cellpadding="0" style='white-space: nowrap;'>
										<tr>
											<td bgcolor="#A2BF67"><?=img(array('src'=>'nav_left_off.gif'))?></td>
											<td bgcolor="#A2BF67" style='vertical-align: middle;'>
												<div class="navstyle" id="nav">
													<ul>
														<li style='margin: 0;'><?=linkto(array('address'=>'/index.php','display'=>'Store Ops Home','style'=>'border-left:0;'))?></li>
														<li><?=linkto(array('address'=>'/commtool/','display'=>'Escalator'))?></li>
														<li><?=linkto(array('address'=>'/pandp/','display'=>'P &amp; P'))?></li>
														<li><?=linkto(array('address'=>'/storehours/','display'=>'Store Hours'))?></li>
														<!--<li><?=linkto(array('address'=>'/iphone/','display'=>'iPhone Registration'))?></li>-->
														<li><?=linkto(array('address'=>'#','display'=>'NSO & Remodels','id'=>'id-dropmenu5','extra'=>'rel="dropmenu5"'))?></li>
													</ul>
												</div>
											</td>
											<td style='background: #A2BF67; text-align: right; width: 100%; padding-right: 10px;'>
												<div class="navstyle" id="nav2">
													<ul>
<?
													if (isset($_SESSION['idUser']) || isset($_SESSION['idStore'])) {
														$border = "";
?>
														<li><?=linkto(array('address'=>'/calendar/?logout=1','display'=>'Log-out','style'=>'border-left: 0;'))?></li>
<?
													} else { $border = "border-left: 0;"; }
?>
														<?=(access_check(13,1) ? '<li>'.linkto(array('address'=>'/calendar/admin/','display'=>'Admin Section')).'</li>' : '')?>
													</ul>
												</div>
	
												<!--5st drop down menu -->                                                   
												<div id="dropmenu5" class="dropmenudiv">
													<ul>
														<?=(access_check(7,1) ? '<li>'.linkto(array('address'=>'/calendar/nso/','display'=>'Event(s)')).'</li>' : '')?>
														<?=(access_check(10,1) ? '<li>'.linkto(array('address'=>'/calendar/day.php','display'=>'Day View')).'</li>' : '')?>
														<?=(access_check(11,1) ? '<li>'.linkto(array('address'=>'/calendar/week.php','display'=>'Week View')).'</li>' : '')?>
														<?=(access_check(33,1) ? '<li>'.linkto(array('address'=>'/calendar/month.php','display'=>'Month View')).'</li>' : '')?>
														<?=(access_check(12,1) ? '<li>'.linkto(array('address'=>'/calendar/year.php','display'=>'Year View')).'</li>' : '')?>
														<?=(access_check(1,1) ? '<li>'.linkto(array('address'=>'/calendar/','display'=>'Home Page')).'</li>' : '')?>
														<?=(access_check(2,1) ? '<li>'.linkto(array('address'=>'/calendar/learn/','display'=>'Site Tour')).'</li>' : '')?>
														<?=(access_check(3,1) ? '<li>'.linkto(array('address'=>'/calendar/remodel/','display'=>'About Remodels')).'</li>' : '')?>
														<?=(access_check(4,1) ? '<li>'.linkto(array('address'=>'/calendar/sitesurveys/','display'=>'Site Survey')).'</li>' : '')?>
														<?=(access_check(5,1) ? '<li>'.linkto(array('address'=>'/calendar/evals/','display'=>'Evaluations')).'</li>' : '')?>
														<?=(access_check(6,2) ? '<li>'.linkto(array('address'=>'/calendar/feedback/','display'=>'Contact Us')).'</li>' : '')?>
													</ul>
												</div>
											</td>
											<td align="right" bgcolor="#A2BF67" style='width: 1cm;'><?=img(array('src'=>'nav_right_off.gif'))?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<div style='margin: 10px; text-align:left;'>
						<table cellpadding='0' cellspacing='0' class='calframe' align='center' style='margin: 0 auto;'>
							<tr>
								<td id='calmenu' class='calmenu'>
									<form method="post" id='idForm' action="">
<?
									if(!isset($_COOKIE['travelbox'])) { $_COOKIE['travelbox'] = 'show'; }	
?>					
									<div class='clickTitle' onclick='miniMenuDisplay("travel")'>Travel Users</div>
									<div id='travelbox' class='clickBox' style='overflow:auto; display: <?=($_COOKIE['travelbox'] == 'show' ? '' : 'none')?>;'>
									<div style='text-align: center;'>&nbsp;</div>
<?	
	if(!isset($_SESSION['idTravelUsers'])) { $_SESSION['idTravelUsers'] = ""; }
	if(isset($_POST['idTravelUsers']) && $_POST['idTravelUsers'] != "") { $_SESSION['idTravelUsers'] = implode(',',$_POST['idTravelUsers']); }
		else if (count($_POST) && (!isset($_POST['idTravelUsers']) || $_POST['idTravelUsers'] == "")) { $_SESSION['errorMessages'][] = "You must select at least one User."; }

	$results = db_query("SELECT Users.ID,Users.chrFirst,Users.chrLast, CalendarAccess.chrColorText, CalendarAccess.chrColorBG
							FROM Users
							JOIN CalendarAccess ON CalendarAccess.idUser=Users.ID
							WHERE !Users.bDeleted AND bTravelAccess
							GROUP BY Users.ID
							ORDER BY chrLast,chrFirst
						","get cal users"); 
	$userschecked=true;
	while($row = mysqli_fetch_assoc($results)) { 
	if($_SESSION['idTravelUsers'] == "") {
		$UserChecked = true;
	} else if(preg_match('/(^|,)'.$row['ID'].'(,|$)/',$_SESSION['idTravelUsers'])) {
		 $UserChecked = true; 
	} else {
		 $UserChecked = false;
		 $userschecked = false;
	}
?>
										<div style='background: <?=$row['chrColorBG']?>; color: <?=$row['chrColorText']?>;'><?=form_checkbox(array('name'=>'idTravelUsers','array'=>'true','value'=>$row['ID'],'title'=>$row['chrLast'].", ".$row['chrFirst'],'checked'=>$UserChecked))?></div>
<?	} ?>
										<?=form_button(array('type'=>'button','name'=>'idTravelUsersbtn','value'=>($userschecked ? 'Un' : '').'Select All','style'=>'margin-top: 5px;','extra'=>"onclick='Select(\"idTravelUsers\")'"))?>
										<?=form_button(array('type'=>'submit','name'=>'ShowUsers','value'=>'Show Users','style'=>'margin-top: 5px;'))?>
									</div>



					
					
									<div class='clickTitleBottom' onclick='miniMenuDisplay("subscribetrav")'>Subscribe iCal</div>
									<div id='subscribetravbox' class='clickBoxBottom' style='overflow:auto; padding:5px;text-align:center;display: <?=($_COOKIE['subscribetravbox'] == 'show' ? '' : 'none')?>;'>
										<?=linkto(array('address'=>'/calendar/makeical.php?cal=travel','img'=>'/calendar/images/ical_subscription.png'))?>
										<div style='text-align:left;'>To subscribe to the Travel Calendar please click on the image above.</div>
									</div>
										</form>
									</td>
									<td class='calcontent'>	
										<table cellspacing="0" cellpadding="0" class="calmenubar">
											<tr style='background: url(images/cap-middle.gif) repeat-x; width: 100%;'>
												<td class='sides'><?=img(array('src'=>'/calendar/images/cap-left.gif'))?></td>
												<td>
													<? preg_match("/^\w*/",$file_name,$date_type); ?>
													<div class='datetime'><?=linkto(array('address'=>$file_name.'?dBegin='.date('Ymd',strtotime($_REQUEST['dBegin']. " - 1 ".$date_type[0])),'img'=>'/calendar/images/arrow_left.png'))?>
													<?=$display_date?>
													<?=linkto(array('address'=>$file_name.'?dBegin='.date('Ymd',strtotime($_REQUEST['dBegin']. " + 1 ".$date_type[0])),'img'=>'/calendar/images/arrow_right.png'))?></div>
												</td>
									
												<td class='quickjump'>
													Go to: 
<?													$today = date('Y').date('m').date('d');
													(access_check(10,1) ? $options['day.php?dBegin='.$today] = 'Today&#39;s Date' : ''); 
													(access_check(11,1) ? $options['week.php?dBegin='.$today] = 'This Week' : '');
													(access_check(33,1) ? $options['month.php?dBegin='.$today] = 'This Month' : '');
?>				
													<?=form_select($options,array('caption'=>'-Go To-','nocaption'=>'true','name'=>'goto','extra'=>'onchange="window.location.href=this.value"'))?>
												</td>
												<td class='currentdates'>
													<?=(access_check(10,1) ? linkto(array('address'=>'/calendar/day.php?dBegin='.$_REQUEST['dBegin'],'img'=>'/calendar/images/cal_day.gif','display'=>'Day Calendar View')) : '')?>
													<?=(access_check(11,1) ? linkto(array('address'=>'/calendar/week.php?dBegin='.$_REQUEST['dBegin'],'img'=>'/calendar/images/cal_week.gif','display'=>'Week Calendar View')) : '')?>
													<?=(access_check(33,1) ? linkto(array('address'=>'/calendar/month.php?dBegin='.$_REQUEST['dBegin'],'img'=>'/calendar/images/cal_month.gif','display'=>'Month Calendar View')) : '')?>
													<?=(access_check(33,1) || access_check(11,1) || access_check(10,1) ? linkto(array('address'=>'/calendar/'.$date_type[0].'.php?dBegin='.$_REQUEST['dBegin'].'&type=nso','img'=>'/calendar/images/safariback.gif','display'=>'View NSO/Remodel Calendar')) : '')?>
												</td>
												<td class='sides' style='text-align: right;'><?=img(array('src'=>'/calendar/images/cap-right.gif'))?></td>
											</tr>
										</table>
										<?=messages()?>
<!-- Begin code -->
<?
	# This is where we will put in the code for the page.
	(isset($sitm) && $sitm != '' && function_exists($sitm) ? $sitm() : sitm());
?>
<!-- End code -->
									</td>
								</tr>
							</table>
					</div>
				</td>
				<td width="4" style="background: url(<?=$BF?>images/border-right.gif) repeat-y;"><?=img(array('src'=>'border-right.gif'))?></td>
			</tr>
			<tr>
				<td width="964" height="17" colspan="3"><?=img(array('src'=>'border-bottom.gif'))?></td>
			</tr>
		</table>
		<table width="964" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-left: 5px; padding-top:5px;" valign="top">
					<div style="padding-bottom:10px;">This communication is privileged and may contain confidential information intended only for the a specific audience. Any distribution, re-transmission, copying or disclosure of this information is strictly prohibited.  If you have accessed this site in error, please notify Store Operations immediately at <a href='mailto:storeops@apple.com'>storeops@apple.com</a>, and delete this URL from your system. </div>
					<div style='text-align:left;'>Copyright &copy; <?=date('Y')?> Apple Inc. All rights reserved. Maintained by Retail Store Operations.</div>
				</td>
			</tr>
		</table>
<?
	# Any aditional things can go down here including javascript or hidden variables
	# "Stuff on the Bottom"
	if(function_exists('sotb')) { sotb(); } 
?>
	</body>
</html>

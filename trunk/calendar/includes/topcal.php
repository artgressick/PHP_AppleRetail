	<script type='text/javascript' src="<?=$BF?>calendar/includes/calendar.js"></script>
</head>
<body onLoad="<?=(isset($bodyParams) ? $bodyParams : '')?>">

<table width="964" border="0" align="center" cellpadding="0" cellspacing="0"n bgcolor="#FFFFFF">
    <tr>
        <td width="964" height="9" colspan="3"><img src="<?=$BF?>images/border-top.gif" width="964" height="9" /></td>
    </tr>
    <tr>
        <td width="4" background="<?=$BF?>images/border-left.gif"><img src="<?=$BF?>images/border-left.gif" width="4" height="4" /></td>
        <td width="956"><div align="center">
            <table width="924" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><img src="<?=$BF?>images/topbanner.gif" width="924" height="147" /></td>
                </tr>
                <tr>
                    <td><table width="924" border="0" cellspacing="0" cellpadding="0" style='white-space: nowrap;'>
                        <tr>
                            <td bgcolor="#A2BF67"><img src="<?=$BF?>images/nav_left_off.gif" width="7" height="28" /></td>
                            <td bgcolor="#A2BF67" style='vertical-align: middle;'>
								<div class="navstyle" id="nav">
									<ul>
										<li style='margin: 0;'><a style='border-left: 0;' href="<?=$BF?>index.php">Store Ops Home</a></li>
										<li><a href="<?=$BF?>commtool/index.php">Escalator</a></li>
										<li><a href="<?=$BF?>pandp/index.php">P &amp; P</a></li>
										<!--<li><a href="<?=$BF?>iphone/index.php">iPhone Registration</a></li>-->
										<!--<li><a href="#" id="id-dropmenu4" rel="dropmenu4">NSO &amp; Remodel</a></li>-->
									</ul>
								</div>
							</td>
                            <td style='background: #A2BF67; text-align: right; width: 100%; padding-right: 10px;'>
								<div class="navstyle" id="nav2">
									<ul>
<?
			if (isset($_SESSION['idStore'])) {
				$border = "";
?>
										<li><a style='border-left: 0;' href="<?=$BF?>index.php?logout=1">Log-out</a></li>
<?
			} else { $border = "border-left: 0;"; }
?>
										<li><a style='<?=$border?>' href="<?=(preg_match('/\/admin\//',$_SERVER['REQUEST_URI']) ? ".." : ".")?>/admin/">Admin Section</a></li>
									</ul>
								</div>
                           	
								<!--5st drop down menu -->                                                   

                            </td>
                            <td align="right" bgcolor="#A2BF67" style='width: 1cm;'><img src="<?=$BF?>images/nav_right_off.gif" width="7" height="28" /></td>
                        </tr>
                    </table></td>
                </tr>
            </table>
	<div style='margin: 10px;'>		
	

	

	<table cellpadding='0' cellspacing='0' class='calframe'>
		<tr>
			<td id='calmenu' class='calmenu'>
				<form method="post" action="">

	
				<div class='clickTitle' onclick='miniMenuDisplay("caltype")'>Calendar Types</div>
				<div id='caltypebox' class='clickBox' style='display: <?=($_COOKIE['caltypebox'] == 'show' ? '' : 'none')?>;'>
<?	
	$results = database_query("SELECT ID,chrCalendarType,chrColorText,chrColorBG 
		FROM CalendarTypes 
		WHERE !bDeleted
		ORDER BY chrCalendarType
	","cal types"); 
	while($row = mysqli_fetch_assoc($results)) { 
?>
					<div style='background: <?=$row['chrColorBG']?>; color: <?=$row['chrColorText']?>;'><input<?=($_SESSION['idCalTypes'] == "" ? ' checked="checked"' : (preg_match('/(^|,)'.$row['ID'].'(,|$)/',$_SESSION['idCalTypes']) ? ' checked="checked"' : ''))?> type='checkbox' name='idCalTypes[]' value='<?=$row['ID']?>' /> <?=$row['chrCalendarType']?></div>
<?	} ?>
					<input type='submit' value='Show Calendars' style='margin-top: 10px;'>
				</div>


				<div class='clickTitle' onclick='miniMenuDisplay("user")'>Users</div>
				<div id='userbox' class='clickBox' style='display: <?=($_COOKIE['userbox'] == 'show' ? '' : 'none')?>;'>
<?	
	$results = database_query("SELECT ID,chrFirst,chrLast FROM Users WHERE !bDeleted AND bCalAccess ORDER BY chrLast,chrFirst","get cal users"); 
	while($row = mysqli_fetch_assoc($results)) { 
?>
					<div><input<?=($_SESSION['idCalUsers'] == "" ? ' checked="checked"' : (preg_match('/(^|,)'.$row['ID'].'(,|$)/',$_SESSION['idCalUsers']) ? ' checked="checked"' : ''))?> type='checkbox' name='idCalUsers[]' value='<?=$row['ID']?>' /> <?=$row['chrLast'].", ".$row['chrFirst']?></div>
<?	} ?>
					<input type='submit' value='Show Users' style='margin-top: 10px;'>
				</div>


				<div class='clickTitleBottom' onclick='miniMenuDisplay("subscribe")'>Subscribe</div>
				<div id='subscribebox' class='clickBoxBottom' style='display: <?=($_COOKIE['subscribebox'] == 'show' ? '' : 'none')?>;'>
					<a href='<?=$BF?>calendar/makeical.php'><img src="<?=$BF?>calendar/images/ical_subscription.png"></a>
					To subscribe to your calendar please click on the image above.
				</div>

			
		


				</form>
			</td>
			<td class='calcontent'>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?=$title?></title>
		<link href="<?=$BF?>iphone/includes/global.css" rel="stylesheet" media='screen' type="text/css" />
	</head>
<body>

<table id='main' cellpadding="0" cellspacing="0">
	<tr>
		<td class='topleft'></td>
		<td class='top'></td>
		<td class='topright'></td>
	</tr>
	<tr>
		<td class='middle' colspan='3'>
			<div class='content'>
				<div style='margin-bottom: 20px; margin-left: 20px;'>
					<img src='<?=$BF?>iphone/images/nike-apple-black.gif' alt='nikeLogo' />
				</div>
			
				<div style='margin: 0 -9px 10px; padding: 5px 8px 0 10px; background: URL(<?=$BF?>iphone/images/gray3dbar100px.gif); width: 100%; height: 24px; text-align: right; font-size: 10px;'>
					<? if(isset($_SESSION['idUser'])) { ?> <a href='?logout=1'>Log Out</a> <? } ?>
				</div>
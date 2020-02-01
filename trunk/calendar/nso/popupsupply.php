<?
	include('_controller.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Image Popup</title>
<script type='text/javascript'>
	function resizepopup() {
		var img=document.getElementById('popupimage');
		var dad=window.parent.document.getElementById('miniPopupWindow');
		dad.height=img.height;
		dad.width=img.width;
	}

</script>
</head>
<body onload="resizepopup();" style="margin:0; padding:0;">
<?=img(array('src'=>'/calendar/nsosupply/'.$_REQUEST['image'],'id'=>'popupimage','extra'=>'onclick="window.parent.miniPopupShrink(window.parent.document.getElementById(\'miniPopupWindow\'))"'))?>
</body>
</html>

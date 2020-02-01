<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$pageinfo;
	
?>
	<table class='List' id='List' style="border:none;" style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<td><?=decode($pageinfo['txtContent'])?></td>
		</tr>
	</table>
<?
		
	}
?>
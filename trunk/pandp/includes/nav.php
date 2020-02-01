<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
  <tr>
      <td width="196" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td background="<?=$BF?>images/email_bottomfooter.gif" height="24" style="text-align:center; font-size: 12px; font-weight:bold;">P & P Pages</td>
        </tr>
        <tr>
			<td background="<?=$BF?>images/email_background.gif">


<?	

	$query = "SELECT * 
	FROM PNPPages
	WHERE !bDeleted AND bVisable AND bPVisable AND idGeo=".$_SESSION['idGeo']." 
	ORDER BY dOrder,!bParent,dOrderChild,chrTitle";
	
	$results = database_query($query, 'Getting Pages');
	$cat = "";
	$tmpCat = "";	//dtn: Temporary Category place holder to see if the object is part of the last category.
	$oldCat = "";	//dtn: Old Category place holder for the javascript that needs to know when we are out of the category while still remembering what the old category was.
	$cnt = 0;		//dtn: Basic counter to make sure we don't stick the javascript into nothing on the first run through.
?>
<div class="web_page_parent" style="padding:5px 0px 0px 5px;"><a href="search.php" style = "color: #0000FF; text-decoration: none; vertical-align:top;"><strong>Search P & P</strong></a></div>
<?	
	while($row = mysqli_fetch_assoc($results)) { 
		if($row['bParent']) {	
				$oldCat = $tmpCat = $cat;
		
				$cat = base64_encode($row['chrTitle'].$_SESSION['idGeo']."1"); 	//dtn: Remove category name spaces for the JS.		
			if($cnt++ > 0) { 
?>
					</div>
				</div>

				<script type='text/javascript'>	setToggle("<?=$oldCat?>", "closed"); </script>
<?			}

			?>	
				
				<div class='email_folders open' style="padding-top:3px; vertical-align:top;">
				<div class="web_page_parent" style=""><a onclick="toggle('<?=$cat?>');"><img id='img_<?=$cat?>' src="<?=$BF?>images/collapse.gif" /></a><a href="index.php?page=<?=$row['ID']?>" style = "color: #333; text-decoration: none; vertical-align:top;"> <?=$row['chrTitle']?></a></div>
				<div id='<?=$cat?>' class='closed'>
			<? }
			else { ?>
					<div class="web_pages"><a href="index.php?page=<?=$row['ID']?>"><?=$row['chrTitle']?></a></div>
			<? } 

	}
	$oldCat = $tmpCat = $cat;
		if($cnt++ > 0) { 
?>
					</div>
				</div>

				<script type='text/javascript'>	setToggle("<?=$oldCat?>", "closed"); </script>
<?
			} 
?>
          	</td>
        </tr>
        <tr>
          <td><img src="<?=$BF?>images/email_bottomfooter.gif" width="196" height="24" /></td>
        </tr>
      </table></td>
      <td width="25"></td>
      <td width="703" valign="top">

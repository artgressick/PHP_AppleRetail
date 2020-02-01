<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
  <tr>
      <td width="196" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="<?=$BF?>images/email_topnav.gif" width="196" height="55" /></td>
        </tr>
        <tr>
          <td background="<?=$BF?>images/email_background.gif">
				

<?	$tmpCat = "";	//dtn: Temporary Category place holder to see if the object is part of the last category.
	$oldCat = "";	//dtn: Old Category place holder for the javascript that needs to know when we are out of the category while still remembering what the old category was.
	$cnt = 0;		//dtn: Basic counter to make sure we don't stick the javascript into nothing on the first run through.

		$results = database_query("SELECT RFLEmails.ID, chrPHPPage,chrEmailCategory, chrRFLName
			FROM RFLEmails 
			JOIN RFLCategories ON RFLCategories.ID=RFLEmails.idRFLCategory 
			WHERE !RFLEmails.bDeleted AND RFLEmails.bVisable AND RFLEmails.idGeo=".$_SESSION['idGeo']." 
			ORDER BY chrEmailCategory, chrRFLName
		","getting comm tool results");
		
		$prevcat='';
		while($row = mysqli_fetch_assoc($results)) {

			$nav['listCommTool'][$row['ID']]['page'] = $row['chrPHPPage'];
			$nav['listCommTool'][$row['ID']]['category'] = $row['chrEmailCategory'];
			$nav['listCommTool'][$row['ID']]['name']=$row['chrRFLName'];
		}
		
		
		

	foreach($nav['listCommTool'] as $k => $row) { 
		$cat = base64_encode($row['category'].$_SESSION['idGeo']."1"); 	//dtn: Remove category name spaces for the JS.
		if($tmpCat != $cat) {
			if($cnt++ > 0) { 
?>
					</div>
				</div>

				<script type='text/javascript'>	setToggle("<?=$oldCat?>", "closed"); </script>
<?
			} 
			$oldCat = $tmpCat = $cat;
?>

			<div class='email_folders open' style = "padding-top: 3px">
				<a onclick="toggle('<?=$cat?>');"><img id='img_<?=$cat?>' src="<?=$BF?>images/collapse.gif" /><img src="<?=$BF?>images/email_folder.gif" /> <span style="vertical-align: top;"><?=$row['category']?></span></a>
				<div id='<?=$cat?>' class='closed'>

					<div class="email_messages"><a href="<?=$row['page']?>?id=<?=$k?>"><?=$row['name']?></a></div>
			



<?		} else { // IF $tmpCat == $cat then just display the next email in the list ?>
					<div class="email_messages"><a href="<?=$row['page']?>?id=<?=$k?>"><?=$row['name']?></a></div>
<?		}
	}
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
      <td width="703" valign="top" height="100%">

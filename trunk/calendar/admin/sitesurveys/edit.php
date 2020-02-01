<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
		
		$ss_type_results = db_query("SELECT ID,chrEvalType AS chrSSType FROM EvalTypes WHERE !bDeleted","getting eval types");	
		
		$ss_types = '<option value="">-Select Option Type-</option>';
		while($row = mysqli_fetch_row($ss_type_results)) {
			$ss_types .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$ss_types .= "</select>";

		$ss_cat_results = db_query("SELECT ID,chrCat FROM SSCats WHERE !bDeleted ORDER BY intOrder","getting cats");	
		
		$ss_cats = '<option value="">-Select Category-</option>';
		while($row = mysqli_fetch_row($ss_cat_results)) {
			$ss_cats .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$ss_cats .= "</select>";
		
		
		
		$q = "SELECT bRequired,idSSType,dOrder,chrQuestion,txtOptions,idSSCat
			FROM SSQuestions
			WHERE idSS IN (SELECT ID FROM SSs WHERE chrKEY='". $_REQUEST['key'] ."')
			ORDER BY dOrder,idSSType,chrQuestion";
		$results = db_query($q,"getting previous questions");
		
		# This is the messages to show for each of the eval types;
		$ss_messages[1] = "<em>A text box will appear for this question with a space limit of 255 characters.</em>";
		$ss_messages[2] = "<em>A text area will appear for this question.  This will be big enough to hold multiple paragraphs of information.</em>";
		$ss_messages[3] = "<em>A select box appear for this question.  Please fill in the names of the options you would like to use.</em>";
		$ss_messages[4] = "<em>A set of checkboxes will appear for this question.  Please fill in the names of the options you would like to appear for the checkboxes.</em>";
		$ss_messages[5] = "<em>A set of radio boxes will appear for this question.  Please fill in the names of the options you would like to appear for the radio boxes.</em>";			

?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
									<div id='questions' style='margin-bottom: 10px;'>
<?
		$i = 1;
		while($row = mysqli_fetch_assoc($results)) {
?>
										
											<table cellspacing="0" cellpadding="0" class='questions' id='question<?=$i?>'>
												<tr>
													<td class='lheader'><strong>Question <?=$i?></strong></td>
													<td class='loption'><input type='text' name='chrQuestion<?=$i?>' id='chrQuestion<?=$i?>' style='width: 325px;' value='<?=$row['chrQuestion']?>' /></td>
													<td class='rheader'>Required Field</td>
													<td class='roption'><input type='checkbox' name='bRequired<?=$i?>' id='bRequired<?=$i?>'<?=($row['bRequired'] == 1 ? ' checked="checked"' : '')?> /></td>
												</tr>
												<tr>
													<td class='lheader'>Category:</td>
													<td class='loption'><select name='idSSCat<?=$i?>' id='idSSCat<?=$i?>'><?=str_replace('value="'.$row['idSSCat'].'"','value="'.$row['idSSCat'].'" selected="selected"',$ss_cats)?></td>
													<td class='rheader'>Display Order</td>
													<td class='roption'><input type='text' name='dOrder<?=$i?>' id='dOrder<?=$i?>' value='<?=$row['dOrder']?>' style='width: 25px;' /></td>
												</tr>
												<tr>
													<td class='lheader'>Answer Option Types:</td>
													<td class='loption'><select name='idSSType<?=$i?>' id='idSSType<?=$i?>' onchange='showOptions(this.value,<?=$i?>)'><?=str_replace('value="'.$row['idSSType'].'"','value="'.$row['idSSType'].'" selected="selected"',$ss_types)?></td>
													<td class='rheader'>&nbsp;</td>
													<td class='roption'>&nbsp;</td>
												</tr>
												<tr>
													<td colspan='4' id='options<?=$i?>' class='additional'>
														<div id='optionset<?=$i?>'>
														<?=$ss_messages[$row['idSSType']]?>
<?			
				if($row['txtOptions'] != "") {
?>
														<table id='optionsetTbl<?=$i?>' cellpadding="0" cellspacing="0">
<?					$tmp_options = explode('|||',$row['txtOptions']);
					$len = count($tmp_options);
					$k = 1;
					while($k <= $len) {
?>
															<tr>
																<td class='optionlabel'>Option <?=$k?>:</td>
																<td class='optionBox' id='optionBox<?=$i?>-<?=$k?>'><input type='text' name='optionval<?=$i?>-<?=$k?>' id='optionval<?=$i?>-<?=$k?>' value='<?=$tmp_options[$k-1]?>' /></td>
																<td class='optionExtra'><em><a id='removeOption<?=$i?>-<?=$k?>' href='javascript:eraseOption("<?=$i?>-<?=$k?>")'>Remove Option</a></em></td>
															</tr>
<?						$k++;
					}
?>
														</table>
														</div><input type='hidden' name='optionval<?=$i?>' id='optionval<?=$i?>' value='<?=$len?>' /><div style='padding: 5px 10px;'><a href='javascript:newOption(<?=$i?>);'>Add Another Option</a></div> 
<?
				}
?>
														</div>
													</td>
												</tr>
											</table>
											<input type="hidden" name="bDeleted<?=$i?>" id="bDeleted<?=$i?>" value="0" />
											<div style="text-align: right;"><a href="javascript:eraseQuestion(<?=$i?>);" id="addremove1">Remove Question <?=$i?></a></div>
<?	
			$i++;
		} 
?>
										</div>
										<div><a href='javascript:addNew();'>Add New Question</a></div>

										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<input type='hidden' name='intCount' id='intCount' value='<?=--$i?>' />
										</div>



										<script type='text/javascript'>
											var eval_types = '<?=$ss_types?>';
											var eval_cats = '<?=$ss_cats?>';
											var eval_messages = new Array('','<?=implode("','",$ss_messages)?>');
										</script>
									</form>
<?
	}
?>
				</table>
				<div class='FormName'>ファイルを添付</div>
				<div class='FormUploadFile'><input class="FormUploadFile" name='chrAttachment' id='chrAttachment' type="file" value="Choose a file" /></div> 
				<div class='FormButtons'><input style="margin-top:15px;" type="button" value="Emailを送信" onclick='error_check();' /></div>
				<input type="hidden" id="id" name="id" value="<?=$_REQUEST['id']?>" />
			</div>
		</td>
	</tr>
</table>
</form>
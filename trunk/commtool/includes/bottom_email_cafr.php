				</table>
				<div class='FormName'>Joindre un fichier</div>
				<div class='FormUploadFile'><input class="FormUploadFile" name='chrAttachment' id='chrAttachment' type="file" value="Choose a file" /></div> 
				<div class='FormButtons'><input style="margin-top:15px;" type="button" value="Envoyer le courriel" onclick='error_check();' /></div>
				<input type="hidden" id="id" name="id" value="<?=$_REQUEST['id']?>" />
			</div>
		</td>
	</tr>
</table>
</form>
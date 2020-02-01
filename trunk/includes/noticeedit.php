	<div id='overlaypage' class='overlaypage'>
		<div id='gray' class='gray'></div>
		<div id='message' class='message'>
			<div class='notice' id='notice'>
				<div class='green'>NOTICE!</div>
				<div class='body'>
					<div>You have Successfully Edited <br />
		
						Name: <span id='addName' style='color: blue;'></span><br />
					</div>
					<div style='margin-top: 20px; '>
						<input type='hidden' name='moveOn' id='moveOn' />
						<input id='moveone' type='button' value='OK' onclick="document.getElementById('moveTo').value=document.getElementById('moveOn').value; document.getElementById('idForm').submit();" /> &nbsp;&nbsp; <input id='moveone' type='button' value='Cancel Edit' onclick="document.getElementById('overlaypage').style.display='none';" />
					</div>
				</div>
			</div>
		</div>
	</div>
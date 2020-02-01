	<div id='overlaypage' class='overlaypage'>
		<div id='gray' class='gray'></div>
		<div id='message' class='message'>
			<div class='notice' id='notice'>
				<div class='green'>NOTICE!</div>
				<div class='body'>
					<div>You have Successfully Added <br />
		
						Name: <span id='addName' style='color: blue;'></span><br />
					</div>
					<div style='margin-top: 20px; '><strong>What would you like to do?</strong><br />
						<input type='hidden' name='moveBack' id='moveBack' />
						<input type='hidden' name='moveOn' id='moveOn' />
						<input id='addanother' type='button' value='Add Another' onclick="document.getElementById('moveTo').value=document.getElementById('moveBack').value; document.getElementById('idForm').submit();" /> &nbsp;&nbsp; 
						<input id='moveone' type='button' value='Move On' onclick="document.getElementById('moveTo').value=document.getElementById('moveOn').value; document.getElementById('idForm').submit();" /> &nbsp;&nbsp; <input id='moveone' type='button' value='Cancel Add' onclick="document.getElementById('overlaypage').style.display='none';" />
					</div>
				</div>
			</div>
		</div>
	</div>
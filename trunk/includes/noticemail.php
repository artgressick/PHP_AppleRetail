	<div id='overlaypage' class='overlaypage'>
		<div id='gray' class='gray'></div>
		<div id='message' class='message'>
			<div class='notice' id='notice'>
				<div class='green'>NOTICE!</div>
				<div class='body'>
					<div><?=$_SESSION['mailNotice']?></div>
					<div style='margin-top: 20px; '>
						<input type='hidden' name='moveOn' id='moveOn' />
						<input id='moveone' type='button' value='OK' onclick="document.getElementById('overlaypage').style.display='none';" />
					</div>
				</div>
			</div>
		</div>
	</div>
	
<?

	if (isset($_SESSION['mailNotice'])) { ?>
	
		<script type="text/javascript">

			document.getElementById('overlaypage').style.display='block';
			var height = (document.height > window.innerHeight ? document.height : window.innerHeight);
			document.getElementById('gray').style.height = height + "px";
			document.getElementById('message').style.top = window.pageYOffset+"px";	
			
		</script>
<?
		unset($_SESSION['mailNotice']);
	}

 ?>
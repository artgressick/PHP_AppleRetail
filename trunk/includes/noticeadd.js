
	function notice(val1, moveback, moveon) {
		var height = (document.height > window.innerHeight ? document.height : window.innerHeight);
		document.getElementById('gray').style.height = height + "px";
		document.getElementById('message').style.top = window.pageYOffset+"px";
		
		document.getElementById('addName').innerHTML = val1;
		document.getElementById('moveBack').value = moveback;
		document.getElementById('moveOn').value = moveon;
		document.getElementById('overlaypage').style.display = "block";
	}
